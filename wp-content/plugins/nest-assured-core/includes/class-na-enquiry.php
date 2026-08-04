<?php
/**
 * Enquiry capture and routing.
 *
 * @package NestAssuredCore
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

final class NA_Enquiry
{
    private const POST_TYPE = 'na_enquiry';

    private const FAILURE_OPTION = 'na_enquiry_failures';

    private const RETENTION_RUN_OPTION = 'na_retention_last_run';

    public static function init(): void
    {
        add_action('init', [self::class, 'register_post_type']);
        add_action('admin_notices', [self::class, 'failure_notice']);
        add_action('admin_notices', [self::class, 'retention_notice']);
        add_action('admin_post_nopriv_na_submit_enquiry', [self::class, 'handle_submission']);
        add_action('admin_post_na_submit_enquiry', [self::class, 'handle_submission']);
        add_filter('manage_' . self::POST_TYPE . '_posts_columns', [self::class, 'columns']);
        add_action('manage_' . self::POST_TYPE . '_posts_custom_column', [self::class, 'column_content'], 10, 2);
        add_action('add_meta_boxes_' . self::POST_TYPE, [self::class, 'add_meta_boxes']);
        add_action('init', [self::class, 'schedule_retention']);
        add_action('na_delete_expired_enquiries', [self::class, 'delete_expired_enquiries']);
    }

    public static function register_post_type(): void
    {
        register_post_type(self::POST_TYPE, [
            'labels' => [
                'name'          => 'Nest Assured enquiries',
                'singular_name' => 'Nest Assured enquiry',
                'menu_name'     => 'NA enquiries',
            ],
            'public'              => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_icon'           => 'dashicons-shield-alt',
            'supports'            => [],
            'capability_type'      => 'na_enquiry',
            'map_meta_cap'         => false,
            'capabilities'         => [
                'edit_post'              => 'manage_options',
                'read_post'              => 'manage_options',
                'delete_post'            => 'manage_options',
                'edit_posts'             => 'manage_options',
                'edit_others_posts'      => 'manage_options',
                'delete_posts'           => 'manage_options',
                'delete_private_posts'   => 'manage_options',
                'delete_others_posts'    => 'manage_options',
                'publish_posts'          => 'manage_options',
                'read_private_posts'     => 'manage_options',
                'create_posts'           => 'do_not_allow',
            ],
            'exclude_from_search'  => true,
            'show_in_rest'         => false,
        ]);
    }

    /**
     * @param array<string, string> $columns Existing columns.
     * @return array<string, string>
     */
    public static function columns(array $columns): array
    {
        return [
            'cb'        => $columns['cb'] ?? '',
            'title'     => 'Enquiry',
            'na_branch' => 'Client route',
            'na_route'  => 'Queue',
            'na_status' => 'Delivery',
            'date'      => 'Received',
        ];
    }

    public static function column_content(string $column, int $post_id): void
    {
        $map = [
            'na_branch' => '_na_branch',
            'na_route'  => '_na_route',
            'na_status' => '_na_delivery_status',
        ];

        if (isset($map[$column])) {
            echo esc_html((string) get_post_meta($post_id, $map[$column], true));
        }
    }

    public static function add_meta_boxes(): void
    {
        add_meta_box(
            'na-enquiry-details',
            'Enquiry details',
            [self::class, 'render_details'],
            self::POST_TYPE,
            'normal',
            'high'
        );
    }

    public static function render_details(WP_Post $post): void
    {
        $fields = [
            '_na_client_type'        => 'Client route',
            '_na_full_name'          => 'Full name',
            '_na_email'              => 'Email address',
            '_na_phone'              => 'Phone number',
            '_na_contact_preference' => 'Contact preference',
            '_na_adviser_name'       => 'Mortgage adviser',
            '_na_mortgage_reference' => 'Mortgage reference',
            '_na_mortgage_stage'     => 'Mortgage stage',
            '_na_product_interest'   => 'Product interest',
            '_na_main_concern'       => 'Question or concern',
            '_na_route'              => 'Assigned queue',
            '_na_delivery_status'    => 'Delivery status',
            '_na_delivery_detail'    => 'Delivery detail',
            '_na_submitted_at'       => 'Submitted at',
            '_na_consent'            => 'Consent recorded',
        ];

        echo '<table class="widefat striped" role="presentation"><tbody>';
        foreach ($fields as $meta_key => $label) {
            $value = (string) get_post_meta($post->ID, $meta_key, true);
            if ('' === $value) {
                continue;
            }
            echo '<tr><th style="width:220px">' . esc_html($label) . '</th><td>' . nl2br(esc_html($value)) . '</td></tr>';
        }
        echo '</tbody></table>';
        echo '<p><strong>Handling note:</strong> Treat this record as personal data. Use only for the adviser response and approved CRM workflow.</p>';
    }

    public static function handle_submission(): void
    {
        $redirect_value = isset($_POST['redirect_to'])
            ? esc_url_raw(wp_unslash((string) $_POST['redirect_to']))
            : home_url('/enquire/');
        $redirect = self::safe_redirect($redirect_value);

        if (! isset($_POST['na_nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash((string) $_POST['na_nonce'])), 'na_enquiry')) {
            self::redirect_with_status($redirect, 'security');
        }

        $honeypot = isset($_POST['website'])
            ? sanitize_text_field(wp_unslash((string) $_POST['website']))
            : '';
        if ('' !== trim($honeypot)) {
            self::redirect_with_status($redirect, 'received');
        }

        $branch = sanitize_key(wp_unslash((string) ($_POST['client_type'] ?? '')));
        $name = sanitize_text_field(wp_unslash((string) ($_POST['full_name'] ?? '')));
        $email = sanitize_email(wp_unslash((string) ($_POST['email'] ?? '')));
        $phone = sanitize_text_field(wp_unslash((string) ($_POST['phone'] ?? '')));
        $contact_preference = sanitize_key(wp_unslash((string) ($_POST['contact_preference'] ?? 'either')));
        $contact_preference = in_array($contact_preference, ['either', 'phone', 'email'], true)
            ? $contact_preference
            : 'either';
        $consent = ! empty($_POST['consent']);

        $phone_required = 'phone' === $contact_preference;
        if (! in_array($branch, ['existing', 'new'], true) || '' === $name || ! is_email($email) || ($phone_required && '' === $phone) || ! $consent) {
            self::redirect_with_status($redirect, 'validation');
        }

        $rate_key = 'na_rate_' . hash_hmac('sha256', self::client_ip(), wp_salt('nonce'));
        if (get_transient($rate_key)) {
            self::redirect_with_status($redirect, 'rate');
        }

        $mortgage_stage = sanitize_key(wp_unslash((string) ($_POST['mortgage_stage'] ?? '')));
        $product_interest = sanitize_key(wp_unslash((string) ($_POST['product_interest'] ?? '')));

        $mortgage_stage = in_array($mortgage_stage, ['', 'application', 'offer', 'completion', 'completed', 'unsure'], true)
            ? $mortgage_stage
            : '';
        $product_interest = in_array($product_interest, [
            '',
            'not-sure',
            'life-insurance',
            'income-protection',
            'critical-illness',
            'family-protection',
            'private-medical-insurance',
            'business-protection',
            'general-insurance',
        ], true)
            ? $product_interest
            : '';

        $data = [
            'client_type'       => $branch,
            'full_name'         => $name,
            'email'             => $email,
            'phone'             => $phone,
            'contact_preference'=> $contact_preference,
            'adviser_name'      => ! empty($_POST['adviser_unknown'])
                ? 'Not sure'
                : sanitize_text_field(wp_unslash((string) ($_POST['adviser_name'] ?? ''))),
            'mortgage_reference'=> sanitize_text_field(wp_unslash((string) ($_POST['mortgage_reference'] ?? ''))),
            'mortgage_stage'    => $mortgage_stage,
            'product_interest'  => $product_interest,
            'main_concern'      => sanitize_textarea_field(wp_unslash((string) ($_POST['main_concern'] ?? ''))),
            'source_url'        => esc_url_raw($redirect),
            'submitted_at'      => gmdate('c'),
        ];

        if ('existing' === $branch) {
            $data['product_interest'] = '';
            $data['main_concern'] = '';
        } else {
            $data['adviser_name'] = '';
            $data['mortgage_reference'] = '';
            $data['mortgage_stage'] = '';
        }

        if ('existing' === $branch && '' === $data['adviser_name']) {
            self::redirect_with_status($redirect, 'validation');
        }

        $route = self::resolve_route($data);
        $post_id = wp_insert_post([
            'post_type'   => self::POST_TYPE,
            'post_status' => 'private',
            'post_title'  => sprintf('%s enquiry: %s', ucfirst($branch), $name),
        ], true);

        if (is_wp_error($post_id)) {
            self::redirect_with_status($redirect, 'storage');
        }

        foreach ($data as $key => $value) {
            update_post_meta((int) $post_id, '_na_' . $key, $value);
        }
        // Record what was consented to, not just that consent happened: the wording
        // changes between releases, so a bare flag proves nothing in a dispute.
        update_post_meta((int) $post_id, '_na_consent', 'yes');
        update_post_meta((int) $post_id, '_na_consent_at', gmdate('c'));
        update_post_meta((int) $post_id, '_na_consent_version', NA_CORE_VERSION);
        update_post_meta((int) $post_id, '_na_consent_text_hash', md5(NA_Shortcodes::consent_text()));
        update_post_meta((int) $post_id, '_na_route', $route['queue']);
        update_post_meta((int) $post_id, '_na_branch', $branch);

        // Set the rate limit before delivery, so a slow or failing delivery cannot be
        // used to bypass it by resubmitting while the first request is still running.
        set_transient($rate_key, '1', 10 * MINUTE_IN_SECONDS);

        $delivery_status = self::deliver((int) $post_id, $data, $route);
        update_post_meta((int) $post_id, '_na_delivery_status', $delivery_status);

        self::send_confirmation($data, $delivery_status);

        // Never tell somebody their enquiry reached an adviser when it did not.
        $delivered = in_array($delivery_status, ['crm-delivered', 'email-accepted'], true);
        self::redirect_with_status($redirect, $delivered ? 'received' : 'pending');
    }

    /**
     * @param array<string, string> $data Enquiry data.
     * @return array{queue:string,email:string}
     */
    private static function resolve_route(array $data): array
    {
        if ('new' === $data['client_type']) {
            return [
                'queue' => 'protection-team',
                'email' => NA_Settings::get('protection_email'),
            ];
        }

        $adviser = self::normalise_adviser_name($data['adviser_name']);
        $lines = preg_split('/\r\n|\r|\n/', NA_Settings::get('adviser_routes')) ?: [];

        foreach ($lines as $line) {
            $parts = array_map('trim', explode('|', $line));
            if (count($parts) < 2 || self::normalise_adviser_name($parts[0]) !== $adviser) {
                continue;
            }

            // Fall back to the protection team rather than nowhere when an adviser
            // line carries no email, so a matched route can never be a dead end.
            $adviser_email = isset($parts[2]) ? sanitize_email($parts[2]) : '';

            return [
                'queue' => sanitize_key($parts[1]),
                'email' => is_email($adviser_email) ? $adviser_email : NA_Settings::get('protection_email'),
            ];
        }

        return [
            'queue' => 'existing-client-unmatched-adviser',
            'email' => NA_Settings::get('protection_email'),
        ];
    }

    /**
     * @param array<string, string> $data Enquiry data.
     * @param array{queue:string,email:string} $route Route data.
     */
    private static function deliver(int $post_id, array $data, array $route): string
    {
        $payload = [
            'schema'     => 'nest-assured.enquiry.v1',
            'enquiry_id' => $post_id,
            'route'      => $route['queue'],
            'data'       => $data,
            'consent'    => true,
        ];
        $body = (string) wp_json_encode($payload);
        $webhook = NA_Settings::get('webhook_url');
        $details = [];

        if ('' !== $webhook) {
            $secret = NA_Settings::get('webhook_secret');
            if ('' === $secret) {
                $details[] = 'crm-signing-secret-missing';
            } else {
                // Sign the timestamp with the body so a captured request cannot be
                // replayed indefinitely. Receivers should reject a timestamp outside
                // a five-minute tolerance window.
                $timestamp = (string) time();
                $headers = [
                    'Content-Type'                  => 'application/json',
                    'X-Nest-Assured-Event-ID'       => (string) $post_id,
                    'X-Nest-Assured-Timestamp'      => $timestamp,
                    'X-Nest-Assured-Signature'      => hash_hmac('sha256', $timestamp . '.' . $body, $secret),
                    'X-Nest-Assured-Signature-Type' => 'hmac-sha256-timestamped',
                ];
                $response = wp_safe_remote_post($webhook, [
                    'timeout'     => 12,
                    'redirection' => 2,
                    'headers'     => $headers,
                    'body'        => $body,
                ]);

                if (is_wp_error($response)) {
                    $details[] = 'crm-error-' . sanitize_key($response->get_error_code());
                } else {
                    $code = wp_remote_retrieve_response_code($response);
                    $details[] = 'crm-http-' . absint($code);
                    if ($code >= 200 && $code < 300) {
                        update_post_meta($post_id, '_na_delivery_detail', implode(';', $details));
                        return 'crm-delivered';
                    }
                }
            }
        }

        $subject = sprintf('Nest Assured %s enquiry: %s', $data['client_type'], $data['full_name']);
        $message = "A new Nest Assured enquiry is stored in WordPress.\n\n";
        $message .= 'Queue: ' . $route['queue'] . "\n";
        $message .= 'Client: ' . $data['full_name'] . "\n";
        $message .= 'Email: ' . $data['email'] . "\n";
        $message .= 'Phone: ' . $data['phone'] . "\n";
        $message .= 'Review: ' . admin_url('post.php?post=' . $post_id . '&action=edit');

        // Try the routed adviser, then the protection team, then the site administrator.
        // A stored enquiry that reaches nobody is a lost client, so the chain only ends
        // when every configured recipient has been attempted.
        $recipients = [
            'route'         => $route['email'],
            'protection'    => NA_Settings::get('protection_email'),
            'administrator' => (string) get_option('admin_email'),
        ];

        $attempted = [];
        foreach ($recipients as $label => $recipient) {
            $recipient = trim($recipient);
            if (! is_email($recipient) || in_array($recipient, $attempted, true)) {
                continue;
            }

            $attempted[] = $recipient;

            if (wp_mail($recipient, $subject, $message)) {
                $details[] = 'email-accepted-' . $label;
                update_post_meta($post_id, '_na_delivery_detail', implode(';', $details));
                return 'email-accepted';
            }

            $details[] = 'email-rejected-' . $label;
        }

        if ([] === $details) {
            $details[] = 'no-configured-delivery-channel';
        }
        update_post_meta($post_id, '_na_delivery_detail', implode(';', $details));
        self::record_delivery_failure($post_id, implode(';', $details));

        return 'stored-pending-integration';
    }

    /**
     * @param array<string, string> $data Enquiry data.
     */
    private static function send_confirmation(array $data, string $delivery_status): void
    {
        $delivered = in_array($delivery_status, ['crm-delivered', 'email-accepted'], true);
        if (! $delivered || '1' !== NA_Settings::get('send_confirmations') || ! is_email($data['email'])) {
            return;
        }

        $subject = 'We have received your Nest Assured enquiry';
        $message = "Hello {$data['full_name']},\n\n";
        $message .= "Thank you. Your enquiry has been received and will be reviewed by the right adviser team.\n\n";
        $message .= "This acknowledgement is not insurance advice, a quote or a recommendation.\n\nNest Assured";
        wp_mail($data['email'], $subject, $message);
    }

    public static function schedule_retention(): void
    {
        $days = absint(NA_Settings::get('retention_days'));
        $scheduled = wp_next_scheduled('na_delete_expired_enquiries');

        if ($days > 0 && false === $scheduled) {
            wp_schedule_event(time() + HOUR_IN_SECONDS, 'daily', 'na_delete_expired_enquiries');
            return;
        }

        if (0 === $days && false !== $scheduled) {
            wp_clear_scheduled_hook('na_delete_expired_enquiries');
        }
    }

    public static function delete_expired_enquiries(): void
    {
        $days = absint(NA_Settings::get('retention_days'));
        if ($days < 1) {
            return;
        }

        $batches = 0;
        do {
            $post_ids = get_posts([
                'post_type'              => self::POST_TYPE,
                // A trashed enquiry is still personal data. Restricting this to
                // 'private' left anything an administrator deleted from the list table
                // in the database indefinitely, past the approved retention period.
                'post_status'            => ['private', 'draft', 'pending', 'trash'],
                'posts_per_page'         => 100,
                'fields'                 => 'ids',
                'orderby'                => 'ID',
                'order'                  => 'ASC',
                'no_found_rows'          => true,
                'update_post_meta_cache' => false,
                'update_post_term_cache' => false,
                'date_query'             => [[
                    'column'    => 'post_date_gmt',
                    'before'    => gmdate('Y-m-d H:i:s', time() - ($days * DAY_IN_SECONDS)),
                    'inclusive' => true,
                ]],
            ]);

            foreach ($post_ids as $post_id) {
                wp_delete_post((int) $post_id, true);
            }

            $batches++;
        } while (100 === count($post_ids) && $batches < 10);

        update_option(self::RETENTION_RUN_OPTION, gmdate('c'), false);
    }

    /**
     * Retention depends on WP-Cron, which fires unreliably on a cached, low-traffic
     * site. Surface a stale run rather than assuming deletion is happening.
     */
    public static function retention_notice(): void
    {
        if (! current_user_can('manage_options')) {
            return;
        }

        if (absint(NA_Settings::get('retention_days')) < 1) {
            return;
        }

        $last = get_option(self::RETENTION_RUN_OPTION, '');
        $last_run = is_string($last) && '' !== $last ? strtotime($last) : false;

        if (false !== $last_run && $last_run > time() - (2 * DAY_IN_SECONDS)) {
            return;
        }

        echo '<div class="notice notice-warning"><p><strong>Nest Assured:</strong> the enquiry retention task has not completed in the last 48 hours. '
            . 'Personal data may be held beyond the approved retention period. Check that WP-Cron is running, or configure a server cron.</p></div>';
    }

    private static function normalise_adviser_name(string $name): string
    {
        $normalised = preg_replace('/\s+/', ' ', strtolower(trim($name)));
        return is_string($normalised) ? $normalised : '';
    }

    private static function safe_redirect(string $redirect): string
    {
        return wp_validate_redirect($redirect, home_url('/enquire/'));
    }

    private static function redirect_with_status(string $redirect, string $status): void
    {
        $status = sanitize_key($status);

        // Rejections are otherwise completely silent: no log line, no admin notice and
        // no counter, on the only conversion path the site has.
        if (! in_array($status, ['received', 'pending'], true)) {
            self::record_failure($status);
        }

        wp_safe_redirect(add_query_arg('enquiry', $status, $redirect));
        exit;
    }

    /**
     * Count a rejected submission so repeated failures become visible in wp-admin.
     */
    private static function record_failure(string $status): void
    {
        error_log(sprintf('[nest-assured] enquiry rejected: %s', $status));

        $failures = get_option(self::FAILURE_OPTION, []);
        $failures = is_array($failures) ? $failures : [];
        $failures[$status] = (int) ($failures[$status] ?? 0) + 1;
        $failures['last_failure_at'] = gmdate('c');

        update_option(self::FAILURE_OPTION, $failures, false);
    }

    private static function record_delivery_failure(int $post_id, string $detail): void
    {
        error_log(sprintf('[nest-assured] enquiry %d stored but not delivered: %s', $post_id, $detail));
        self::record_failure('delivery');
    }

    /**
     * Warn in wp-admin when enquiries have been rejected or stranded, so a broken
     * conversion path cannot sit unnoticed behind a private post type.
     */
    public static function failure_notice(): void
    {
        if (! current_user_can('manage_options')) {
            return;
        }

        $failures = get_option(self::FAILURE_OPTION, []);
        if (! is_array($failures) || ! isset($failures['last_failure_at'])) {
            return;
        }

        $last = strtotime((string) $failures['last_failure_at']);
        if (false === $last || $last < time() - (7 * DAY_IN_SECONDS)) {
            return;
        }

        $counts = [];
        foreach ($failures as $status => $count) {
            if ('last_failure_at' !== $status) {
                $counts[] = $status . ': ' . (int) $count;
            }
        }

        echo '<div class="notice notice-error"><p><strong>Nest Assured:</strong> enquiry submissions have failed in the last 7 days ('
            . esc_html(implode(', ', $counts))
            . '). Check the delivery channel settings and the server error log.</p></div>';
    }

    /**
     * The connecting IP.
     *
     * A forwarded-for header is trusted only when the site explicitly declares which
     * header its proxy sets, because a blindly trusted header is attacker-controlled
     * and defeats rate limiting entirely.
     */
    private static function client_ip(): string
    {
        $header = (string) apply_filters('nest_assured_trusted_proxy_header', '');

        if ('' !== $header) {
            $key = 'HTTP_' . strtoupper(str_replace('-', '_', $header));
            if (! empty($_SERVER[$key])) {
                $forwarded = explode(',', (string) wp_unslash($_SERVER[$key]));
                $candidate = trim((string) reset($forwarded));
                if (filter_var($candidate, FILTER_VALIDATE_IP)) {
                    return $candidate;
                }
            }
        }

        return sanitize_text_field(wp_unslash((string) ($_SERVER['REMOTE_ADDR'] ?? 'unknown')));
    }
}
