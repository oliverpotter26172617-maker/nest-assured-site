<?php
/**
 * Integration and compliance settings.
 *
 * @package NestAssuredCore
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

final class NA_Settings
{
    private const OPTION = 'na_settings';

    private const SIGNOFF_OPTION = 'na_compliance_signoff';

    /**
     * Copy fields whose wording requires compliance approval. A change to any of them
     * invalidates an existing sign-off, so approval always refers to specific wording.
     *
     * @var array<int, string>
     */
    private const APPROVED_COPY_FIELDS = [
        'fca_reference',
        'regulatory_copy',
        'privacy_copy',
        'complaints_copy',
        'financial_copy',
        'ollie_bio',
        'faqs_copy',
        'adviser_since',
        'adviser_permissions',
    ];

    public static function init(): void
    {
        add_action('admin_menu', [self::class, 'add_menu']);
        add_action('admin_init', [self::class, 'register_settings']);
        add_action('admin_notices', [self::class, 'configuration_notice']);
        add_action('admin_notices', [self::class, 'status_term_notice']);
        add_action('update_option_' . self::OPTION, [self::class, 'sync_indexing_after_update'], 10, 2);
        add_action('admin_post_na_record_signoff', [self::class, 'handle_signoff']);
    }

    /**
     * @return array<string, mixed>
     */
    public static function all(): array
    {
        $defaults = [
            'protection_email'     => '',
            'webhook_url'          => '',
            'webhook_secret'       => '',
            'adviser_routes'       => '',
            'booking_url'          => '',
            'google_reviews_url'   => '',
            'fca_reference'        => '',
            'regulatory_copy'      => '',
            'privacy_copy'         => '',
            'complaints_copy'      => '',
            'financial_copy'       => '',
            'ollie_bio'            => '',
            'ollie_photo_url'      => '',
            'ollie_photo_id'       => '',
            'faqs_copy'            => '',
            'adviser_since'        => '',
            'adviser_permissions'  => '',
            'contact_phone'        => '',
            'contact_email'        => '',
            // Never default to empty: absint('') is 0, which silently leaves the
            // deletion task unscheduled and retains personal data indefinitely.
            'retention_days'       => '365',
            'send_confirmations'   => '0',
            'purge_on_uninstall'   => '0',
        ];

        $saved = get_option(self::OPTION, []);
        return wp_parse_args(is_array($saved) ? $saved : [], $defaults);
    }

    public static function get(string $key, string $default = ''): string
    {
        $settings = self::all();
        $value = $settings[$key] ?? $default;
        return is_scalar($value) ? (string) $value : $default;
    }

    public static function add_menu(): void
    {
        add_options_page(
            'Nest Assured launch controls',
            'Nest Assured',
            'manage_options',
            'nest-assured-settings',
            [self::class, 'render_page']
        );
    }

    public static function register_settings(): void
    {
        register_setting('na_settings_group', self::OPTION, [
            'type'              => 'array',
            'sanitize_callback' => [self::class, 'sanitize'],
            'default'           => [],
        ]);
    }

    /**
     * @param mixed $input Raw setting input.
     * @return array<string, string>
     */
    public static function sanitize($input): array
    {
        $input = is_array($input) ? $input : [];

        // This callback runs for any update_option() on the option, not just the
        // settings form. Merging over the stored value stops a partial write from
        // wiping approved compliance copy and the webhook secret.
        $existing = get_option(self::OPTION, []);
        $input = wp_parse_args($input, is_array($existing) ? $existing : []);

        $photo_url = esc_url_raw((string) ($input['ollie_photo_url'] ?? ''));
        $photo_id = '' === $photo_url ? 0 : attachment_url_to_postid($photo_url);

        return [
            'protection_email'   => sanitize_email((string) ($input['protection_email'] ?? '')),
            'webhook_url'        => esc_url_raw((string) ($input['webhook_url'] ?? '')),
            'webhook_secret'     => sanitize_text_field((string) ($input['webhook_secret'] ?? '')),
            'adviser_routes'     => sanitize_textarea_field((string) ($input['adviser_routes'] ?? '')),
            'booking_url'        => esc_url_raw((string) ($input['booking_url'] ?? '')),
            'google_reviews_url' => esc_url_raw((string) ($input['google_reviews_url'] ?? '')),
            'fca_reference'      => sanitize_text_field((string) ($input['fca_reference'] ?? '')),
            'regulatory_copy'    => wp_kses_post((string) ($input['regulatory_copy'] ?? '')),
            'privacy_copy'       => wp_kses_post((string) ($input['privacy_copy'] ?? '')),
            'complaints_copy'    => wp_kses_post((string) ($input['complaints_copy'] ?? '')),
            'financial_copy'     => wp_kses_post((string) ($input['financial_copy'] ?? '')),
            'ollie_bio'          => wp_kses_post((string) ($input['ollie_bio'] ?? '')),
            'ollie_photo_url'    => $photo_url,
            'ollie_photo_id'     => (string) $photo_id,
            'faqs_copy'          => wp_kses_post((string) ($input['faqs_copy'] ?? '')),
            'adviser_since'       => sanitize_text_field((string) ($input['adviser_since'] ?? '')),
            'adviser_permissions' => sanitize_text_field((string) ($input['adviser_permissions'] ?? '')),
            'contact_phone'       => sanitize_text_field((string) ($input['contact_phone'] ?? '')),
            'contact_email'       => sanitize_email((string) ($input['contact_email'] ?? '')),
            'retention_days'     => self::sanitize_retention_days($input['retention_days'] ?? ''),
            'send_confirmations' => empty($input['send_confirmations']) ? '0' : '1',
            'purge_on_uninstall' => empty($input['purge_on_uninstall']) ? '0' : '1',
        ];
    }

    /**
     * Copy that is safe to publish.
     *
     * A field being filled in is not the same as it being approved. Regulated
     * wording was rendering the moment somebody typed it, which is how an
     * unapproved biography claiming independent, whole-of-market advice came to
     * be live on the About page while the compliance gate reported everything as
     * outstanding. Nothing regulated now reaches a visitor until a named person
     * has signed off the exact wording, and never if it contains a protected
     * status term.
     */
    public static function approved(string $key): string
    {
        $value = trim(self::get($key));

        if ('' === $value) {
            return '';
        }

        if (! self::is_signed_off()) {
            return '';
        }

        if ([] !== self::prohibited_terms_in($value)) {
            return '';
        }

        return $value;
    }

    /**
     * Protected status terms found in a piece of copy.
     *
     * @return array<int, string>
     */
    public static function prohibited_terms_in(string $text): array
    {
        $terms = ['independent', 'whole of market', 'whole-of-market', 'impartial', 'unbiased', 'no commission', 'never on commission'];
        $haystack = strtolower(wp_strip_all_tags($text));
        $found = [];

        foreach ($terms as $term) {
            if (str_contains($haystack, $term)) {
                $found[] = $term;
            }
        }

        return $found;
    }

    /**
     * Status terms that carry a specific regulatory meaning. Their presence in stored
     * copy is not automatically wrong, but it must be a deliberate, approved choice:
     * an appointed representative advising from a panel is normally restricted rather
     * than independent, and "whole of market" and "panel" are different disclosure
     * categories. Nothing is edited or removed here, only surfaced for a human.
     *
     * @return array<int, string>
     */
    public static function flagged_status_terms(): array
    {
        $terms = ['independent', 'whole of market', 'whole-of-market', 'impartial', 'unbiased', 'no commission', 'never on commission'];
        $haystack = '';

        foreach (self::APPROVED_COPY_FIELDS as $field) {
            $haystack .= ' ' . wp_strip_all_tags(self::get($field));
        }

        // Also read what is actually published. The guard previously scanned only
        // these nine settings, so a breach sitting in page content was invisible
        // to it and the admin screen reported a clean bill of health.
        foreach (get_posts(['post_type' => 'page', 'posts_per_page' => 200, 'post_status' => 'publish']) as $page) {
            $haystack .= ' ' . wp_strip_all_tags($page->post_content);
        }

        $haystack = strtolower($haystack);
        $found = [];

        foreach ($terms as $term) {
            if (str_contains($haystack, $term)) {
                $found[] = $term;
            }
        }

        return $found;
    }

    public static function status_term_notice(): void
    {
        if (! current_user_can('manage_options')) {
            return;
        }

        $found = self::flagged_status_terms();
        if ([] === $found) {
            return;
        }

        $url = admin_url('options-general.php?page=nest-assured-settings');
        echo '<div class="notice notice-error"><p><strong>Nest Assured compliance check:</strong> the approved copy contains regulated status wording ('
            . esc_html(implode(', ', $found))
            . '). Confirm with Sesame that this exact wording is approved for an appointed representative before publishing. '
            . '<a href="' . esc_url($url) . '">Review the launch controls</a>.</p></div>';
    }

    public static function configuration_notice(): void
    {
        if (! current_user_can('manage_options')) {
            return;
        }

        $screen = get_current_screen();
        if ($screen && 'settings_page_nest-assured-settings' === $screen->id) {
            return;
        }

        $missing = self::missing_launch_controls();
        if ([] === $missing) {
            return;
        }

        $url = admin_url('options-general.php?page=nest-assured-settings');
        echo '<div class="notice notice-warning"><p><strong>Nest Assured:</strong> ';
        echo esc_html((string) count($missing)) . ' launch controls still require approved external information. ';
        echo '<a href="' . esc_url($url) . '">Review launch controls</a>.</p></div>';
    }

    /**
     * Controls whose absence is a compliance problem: nothing regulated may be
     * published, and no personal data may be collected, until every one is supplied.
     *
     * @return array<string, string>
     */
    public static function missing_compliance_controls(): array
    {
        $checks = [
            'fca_reference'    => 'Compliance-approved FCA reference',
            'regulatory_copy'  => 'Compliance-approved regulatory wording',
            'privacy_copy'     => 'Compliance-approved privacy notice',
            'complaints_copy'  => 'Compliance-approved complaints wording',
            'financial_copy'   => 'Compliance-approved financial promotions wording',
            'ollie_bio'        => 'Ollie Allen approved biography',
            'ollie_photo_url'  => 'Ollie Allen approved photography',
            'faqs_copy'        => 'Approved FAQs from real adviser conversations',
            'retention_days'   => 'Approved enquiry retention period',
        ];

        return array_filter(
            $checks,
            static fn (string $label, string $key): bool => '' === trim(self::get($key)),
            ARRAY_FILTER_USE_BOTH
        );
    }

    /**
     * Integration plumbing. These are operational rather than regulatory, so they are
     * kept separate: an email-only launch is legitimate and must not require a CRM.
     *
     * @return array<string, string>
     */
    public static function missing_delivery_controls(): array
    {
        $missing = [];

        if (! self::has_delivery_channel()) {
            $missing['delivery_channel'] = 'A working delivery channel (protection team email, or CRM webhook URL and signing secret)';
        }

        if ('' === trim(self::get('adviser_routes'))) {
            $missing['adviser_routes'] = 'Mortgage adviser routing map';
        }

        return $missing;
    }

    /**
     * True when at least one enquiry delivery route is fully configured.
     */
    public static function has_delivery_channel(): bool
    {
        if (is_email(trim(self::get('protection_email')))) {
            return true;
        }

        return '' !== trim(self::get('webhook_url')) && '' !== trim(self::get('webhook_secret'));
    }

    /**
     * Every outstanding control, for admin reporting.
     *
     * @return array<string, string>
     */
    public static function missing_launch_controls(): array
    {
        return self::missing_compliance_controls() + self::missing_delivery_controls();
    }

    /**
     * A hash of the exact approved wording, so a sign-off can only ever refer to the
     * copy that was actually approved.
     */
    public static function approved_copy_hash(): string
    {
        $parts = [];
        foreach (self::APPROVED_COPY_FIELDS as $field) {
            $parts[] = $field . ':' . trim(self::get($field));
        }

        return md5(implode('|', $parts));
    }

    /**
     * @return array{approver: string, date: string, hash: string}|null
     */
    public static function compliance_signoff(): ?array
    {
        $stored = get_option(self::SIGNOFF_OPTION, []);
        if (! is_array($stored) || ! isset($stored['hash'], $stored['approver'], $stored['date'])) {
            return null;
        }

        return [
            'approver' => (string) $stored['approver'],
            'date'     => (string) $stored['date'],
            'hash'     => (string) $stored['hash'],
        ];
    }

    /**
     * True only when a sign-off exists AND it matches the copy currently stored, so
     * editing approved wording automatically withdraws approval.
     */
    public static function is_signed_off(): bool
    {
        $signoff = self::compliance_signoff();

        return null !== $signoff && hash_equals($signoff['hash'], self::approved_copy_hash());
    }

    /**
     * The single gate for publishing regulated content and collecting personal data.
     */
    public static function is_launch_ready(): bool
    {
        return [] === self::missing_compliance_controls()
            && self::has_delivery_channel()
            && self::is_signed_off();
    }

    /**
     * Keep legal and FAQ indexing aligned with approved settings.
     *
     * @param mixed $old_value Previous option value.
     * @param mixed $new_value New option value.
     */
    public static function sync_indexing_after_update($old_value, $new_value): void
    {
        unset($old_value, $new_value);
        $states = [
            'legal' => '' !== trim(self::get('complaints_copy')) && '' !== trim(self::get('financial_copy')),
            'legal/privacy' => '' !== trim(self::get('privacy_copy')),
            'legal/complaints-procedure' => '' !== trim(self::get('complaints_copy')),
            'legal/financial-promotions' => '' !== trim(self::get('financial_copy')),
            'faqs' => '' !== trim(self::get('faqs_copy')),
        ];

        foreach ($states as $path => $approved) {
            $page = get_page_by_path($path, OBJECT, 'page');
            if (! $page instanceof WP_Post) {
                continue;
            }

            if ($approved) {
                delete_post_meta($page->ID, '_yoast_wpseo_meta-robots-noindex');
            } else {
                update_post_meta($page->ID, '_yoast_wpseo_meta-robots-noindex', '1');
            }
        }

        if (function_exists('wp_cache_clear_cache')) {
            wp_cache_clear_cache();
        }
    }

    /**
     * Record or withdraw a compliance sign-off. The approver name and date are stored
     * alongside a hash of the exact wording approved, so the record is auditable and
     * cannot silently outlive an edit to that wording.
     */
    public static function handle_signoff(): void
    {
        if (! current_user_can('manage_options')) {
            wp_die('You do not have permission to record a compliance sign-off.', '', ['response' => 403]);
        }

        check_admin_referer('na_record_signoff');

        $redirect = admin_url('options-general.php?page=nest-assured-settings');

        if (isset($_POST['withdraw'])) {
            delete_option(self::SIGNOFF_OPTION);
            wp_safe_redirect(add_query_arg('na_signoff', 'withdrawn', $redirect));
            exit;
        }

        $approver = sanitize_text_field(wp_unslash((string) ($_POST['approver'] ?? '')));
        if ('' === $approver) {
            wp_safe_redirect(add_query_arg('na_signoff', 'missing-approver', $redirect));
            exit;
        }

        if ([] !== self::missing_compliance_controls()) {
            wp_safe_redirect(add_query_arg('na_signoff', 'incomplete', $redirect));
            exit;
        }

        update_option(self::SIGNOFF_OPTION, [
            'approver' => $approver,
            'date'     => gmdate('c'),
            'hash'     => self::approved_copy_hash(),
        ], false);

        wp_safe_redirect(add_query_arg('na_signoff', 'recorded', $redirect));
        exit;
    }

    public static function render_page(): void
    {
        if (! current_user_can('manage_options')) {
            return;
        }

        $settings = self::all();
        $missing = self::missing_launch_controls();
        ?>
        <div class="wrap">
            <h1>Nest Assured launch controls</h1>
            <p>These values are deliberately not guessed. Add only approved production information.</p>

            <?php if ([] !== $missing) : ?>
                <div class="notice notice-warning inline">
                    <p><strong>Launch remains blocked by:</strong> <?php echo esc_html(implode(', ', array_values($missing))); ?>.</p>
                </div>
            <?php else : ?>
                <div class="notice notice-success inline"><p>All launch controls contain values. Complete human compliance and delivery testing before publishing.</p></div>
            <?php endif; ?>

            <?php self::render_signoff_panel(); ?>

            <form method="post" action="options.php">
                <?php settings_fields('na_settings_group'); ?>
                <table class="form-table" role="presentation">
                    <?php self::text_row('Protection team email', 'protection_email', $settings, 'Used for new enquiries and unmatched existing-client routes. Configure WP Mail SMTP before enabling confirmations.', 'email'); ?>
                    <?php self::text_row('CRM webhook URL', 'webhook_url', $settings, 'The confirmed group CRM endpoint. Enquiries are stored privately when this is blank.', 'url'); ?>
                    <?php self::text_row('Webhook signing secret', 'webhook_secret', $settings, 'Required when the CRM webhook is configured. Sent as an HMAC SHA-256 signature in the X-Nest-Assured-Signature header.', 'password'); ?>
                    <?php self::textarea_row('Adviser routing map', 'adviser_routes', $settings, "One adviser per line: Adviser name|queue-id|email@example.com. Existing clients are matched by adviser name."); ?>
                    <?php self::text_row('Booking embed URL', 'booking_url', $settings, 'Use the real adviser scheduling page URL.', 'url'); ?>
                    <?php self::text_row('Google reviews URL', 'google_reviews_url', $settings, 'Optional. Use only the verified Google Business Profile reviews URL. Trustpilot is intentionally unsupported.', 'url'); ?>
                    <?php self::text_row('FCA reference', 'fca_reference', $settings, 'Enter only the compliance-approved reference.', 'text'); ?>
                    <?php self::textarea_row('Regulatory wording', 'regulatory_copy', $settings, 'Approved firm status and disclosure wording. Basic HTML is allowed.'); ?>
                    <?php self::textarea_row('Privacy notice', 'privacy_copy', $settings, 'Approved controller identity, contact details, lawful basis, processors, retention and data-rights wording. Basic HTML is allowed.'); ?>
                    <?php self::textarea_row('Complaints wording', 'complaints_copy', $settings, 'Approved complaints process and escalation details. Basic HTML is allowed.'); ?>
                    <?php self::textarea_row('Financial promotions wording', 'financial_copy', $settings, 'Approved financial promotions disclosure. Basic HTML is allowed.'); ?>
                    <?php self::textarea_row('Ollie Allen approved biography', 'ollie_bio', $settings, 'Do not add generated biography copy. Add approved text only.'); ?>
                    <?php self::text_row('Ollie Allen approved photograph URL', 'ollie_photo_url', $settings, 'Use only a consented, approved media-library or CDN image URL.', 'url'); ?>
                    <?php self::textarea_row('Approved FAQs', 'faqs_copy', $settings, 'Add only questions and answers sourced from real adviser conversations. Basic HTML is allowed.'); ?>
                    <?php self::text_row('Public phone number', 'contact_phone', $settings, 'Published in the footer, on the contact page and in structured data. Without it the site offers no telephone route, which also affects the complaints channel.', 'text'); ?>
                    <?php self::text_row('Public email address', 'contact_email', $settings, 'A monitored inbox. Published alongside the phone number.', 'email'); ?>
                    <?php self::text_row('Adviser experience line', 'adviser_since', $settings, 'Approved wording for the credentials panel, for example "Since November 2023". Left blank, the fact is not published.', 'text'); ?>
                    <?php self::text_row('Adviser permissions line', 'adviser_permissions', $settings, 'Approved description of the product permissions held. Left blank, the fact is not published.', 'text'); ?>
                    <?php self::number_row('Enquiry retention period', 'retention_days', $settings, 'Approved number of days to retain enquiry records. Expired records are permanently deleted by a daily task.'); ?>
                    <tr>
                        <th scope="row">Confirmation emails</th>
                        <td>
                            <label>
                                <input type="checkbox" name="na_settings[send_confirmations]" value="1" <?php checked('1', (string) $settings['send_confirmations']); ?> />
                                Send confirmation emails after a successful submission
                            </label>
                            <p class="description">Enable only after WP Mail SMTP is connected to a transactional provider and tested.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Delete enquiry data on uninstall</th>
                        <td>
                            <label>
                                <input type="checkbox" name="na_settings[purge_on_uninstall]" value="1" <?php checked('1', (string) ($settings['purge_on_uninstall'] ?? '0')); ?> />
                                Permanently delete every stored enquiry if this plugin is uninstalled
                            </label>
                            <p class="description">Off by default. Client records may be needed for regulatory record-keeping, so they are never destroyed implicitly.</p>
                        </td>
                    </tr>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    /**
     * The explicit compliance sign-off record. Filling in every field is not the same
     * as somebody approving the wording, so publication requires this separate act.
     */
    private static function render_signoff_panel(): void
    {
        $signoff = self::compliance_signoff();
        $current = self::is_signed_off();
        $notice = isset($_GET['na_signoff']) ? sanitize_key(wp_unslash((string) $_GET['na_signoff'])) : '';
        $notices = [
            'recorded'         => ['success', 'Compliance sign-off recorded.'],
            'withdrawn'        => ['warning', 'Compliance sign-off withdrawn. Regulated content and the enquiry form are gated again.'],
            'incomplete'       => ['error', 'Sign-off refused: approved copy is still missing from the controls below.'],
            'missing-approver' => ['error', 'Sign-off refused: enter the name of the person approving the wording.'],
        ];
        ?>
        <div class="card" style="max-width:40rem;padding:1rem 1.25rem;">
            <h2 style="margin-top:0;">Compliance sign-off</h2>

            <?php if (isset($notices[$notice])) : ?>
                <div class="notice notice-<?php echo esc_attr($notices[$notice][0]); ?> inline"><p><?php echo esc_html($notices[$notice][1]); ?></p></div>
            <?php endif; ?>

            <?php if ($current && null !== $signoff) : ?>
                <p><strong>Approved by <?php echo esc_html($signoff['approver']); ?></strong> on
                    <?php echo esc_html(mysql2date(get_option('date_format') . ' H:i', $signoff['date'])); ?> UTC.</p>
            <?php elseif (null !== $signoff) : ?>
                <div class="notice notice-error inline">
                    <p>The approved wording has changed since <?php echo esc_html($signoff['approver']); ?> signed it off.
                        Regulated content and the enquiry form are gated until it is approved again.</p>
                </div>
            <?php else : ?>
                <p>No sign-off has been recorded. Regulated content stays gated and the enquiry form does not render.</p>
            <?php endif; ?>

            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <input type="hidden" name="action" value="na_record_signoff" />
                <?php wp_nonce_field('na_record_signoff'); ?>
                <p>
                    <label for="na-approver">Approved by</label><br />
                    <input class="regular-text" id="na-approver" type="text" name="approver" autocomplete="off" />
                </p>
                <p class="description">Records the approver, the UTC timestamp and a hash of the exact wording approved. Editing any approved copy withdraws the sign-off automatically.</p>
                <p>
                    <button type="submit" class="button button-primary">Record sign-off</button>
                    <?php if (null !== $signoff) : ?>
                        <button type="submit" name="withdraw" value="1" class="button">Withdraw sign-off</button>
                    <?php endif; ?>
                </p>
            </form>
        </div>
        <?php
    }

    /**
     * @param array<string, mixed> $settings Settings values.
     */
    private static function text_row(string $label, string $key, array $settings, string $description, string $type): void
    {
        $value = (string) ($settings[$key] ?? '');
        ?>
        <tr>
            <th scope="row"><label for="na-<?php echo esc_attr($key); ?>"><?php echo esc_html($label); ?></label></th>
            <td>
                <input class="regular-text" id="na-<?php echo esc_attr($key); ?>" type="<?php echo esc_attr($type); ?>" name="na_settings[<?php echo esc_attr($key); ?>]" value="<?php echo esc_attr($value); ?>" autocomplete="off" />
                <p class="description"><?php echo esc_html($description); ?></p>
            </td>
        </tr>
        <?php
    }

    /**
     * @param array<string, mixed> $settings Settings values.
     */
    private static function textarea_row(string $label, string $key, array $settings, string $description): void
    {
        $value = (string) ($settings[$key] ?? '');
        ?>
        <tr>
            <th scope="row"><label for="na-<?php echo esc_attr($key); ?>"><?php echo esc_html($label); ?></label></th>
            <td>
                <textarea class="large-text" rows="5" id="na-<?php echo esc_attr($key); ?>" name="na_settings[<?php echo esc_attr($key); ?>]"><?php echo esc_textarea($value); ?></textarea>
                <p class="description"><?php echo esc_html($description); ?></p>
            </td>
        </tr>
        <?php
    }

    /**
     * @param array<string, mixed> $settings Settings values.
     */
    private static function number_row(string $label, string $key, array $settings, string $description): void
    {
        $value = (string) ($settings[$key] ?? '');
        ?>
        <tr>
            <th scope="row"><label for="na-<?php echo esc_attr($key); ?>"><?php echo esc_html($label); ?></label></th>
            <td>
                <input class="small-text" id="na-<?php echo esc_attr($key); ?>" type="number" min="1" step="1" name="na_settings[<?php echo esc_attr($key); ?>]" value="<?php echo esc_attr($value); ?>" /> days
                <p class="description"><?php echo esc_html($description); ?></p>
            </td>
        </tr>
        <?php
    }

    /**
     * @param mixed $value Raw retention value.
     */
    private static function sanitize_retention_days($value): string
    {
        $days = absint($value);

        // Fall back to the default rather than an empty string, so retention can never
        // be switched off by clearing the field.
        return $days > 0 ? (string) $days : '365';
    }
}
