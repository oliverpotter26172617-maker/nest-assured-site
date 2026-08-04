<?php
/**
 * Removal cleanup.
 *
 * Deleting the plugin previously left the settings option, including the plaintext
 * webhook signing secret, and every stored enquiry in the database indefinitely.
 *
 * Client records are deliberately NOT deleted by default: they may be needed for
 * regulatory record-keeping, and silently destroying them on an accidental plugin
 * delete would be worse than leaving them. Removal happens only when an
 * administrator has explicitly opted in through the launch controls.
 *
 * @package NestAssuredCore
 */

declare(strict_types=1);

if (! defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

$na_settings = get_option('na_settings', []);
$na_purge = is_array($na_settings) && '1' === (string) ($na_settings['purge_on_uninstall'] ?? '0');

if ($na_purge) {
    $na_enquiries = get_posts([
        'post_type'      => 'na_enquiry',
        'post_status'    => ['private', 'draft', 'pending', 'trash'],
        'posts_per_page' => -1,
        'fields'         => 'ids',
    ]);

    foreach ($na_enquiries as $na_enquiry_id) {
        wp_delete_post((int) $na_enquiry_id, true);
    }
}

delete_option('na_settings');
delete_option('na_site_build_version');
delete_option('na_compliance_signoff');
delete_option('na_enquiry_failures');
delete_option('na_retention_last_run');
delete_option('na_overwritten_pages');
delete_option('na_install_lock');

wp_clear_scheduled_hook('na_delete_expired_enquiries');
