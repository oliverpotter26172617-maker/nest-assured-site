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

    public static function init(): void
    {
        add_action('admin_menu', [self::class, 'add_menu']);
        add_action('admin_init', [self::class, 'register_settings']);
        add_action('admin_notices', [self::class, 'configuration_notice']);
        add_action('update_option_' . self::OPTION, [self::class, 'sync_indexing_after_update'], 10, 2);
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
            'retention_days'       => '',
            'send_confirmations'   => '0',
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
            'retention_days'     => self::sanitize_retention_days($input['retention_days'] ?? ''),
            'send_confirmations' => empty($input['send_confirmations']) ? '0' : '1',
        ];
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
     * @return array<string, string>
     */
    public static function missing_launch_controls(): array
    {
        $checks = [
            'protection_email'   => 'Protection team email and SMTP provider',
            'webhook_url'        => 'CRM webhook URL',
            'webhook_secret'     => 'CRM webhook signing secret',
            'adviser_routes'     => 'Mortgage adviser routing map',
            'booking_url'        => 'Adviser diary embed URL',
            'fca_reference'      => 'Compliance-approved FCA reference',
            'regulatory_copy'    => 'Compliance-approved regulatory wording',
            'privacy_copy'       => 'Compliance-approved privacy notice',
            'complaints_copy'    => 'Compliance-approved complaints wording',
            'financial_copy'     => 'Compliance-approved financial promotions wording',
            'ollie_bio'          => 'Ollie Allen approved biography',
            'ollie_photo_url'    => 'Ollie Allen approved photography',
            'faqs_copy'          => 'Approved FAQs from real adviser conversations',
            'retention_days'     => 'Approved enquiry retention period',
        ];

        return array_filter(
            $checks,
            static fn (string $label, string $key): bool => '' === trim(self::get($key)),
            ARRAY_FILTER_USE_BOTH
        );
    }

    public static function is_launch_ready(): bool
    {
        return [] === self::missing_launch_controls();
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
                </table>
                <?php submit_button(); ?>
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
        return $days > 0 ? (string) $days : '';
    }
}
