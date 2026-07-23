<?php
/**
 * Plugin Name: Nest Assured performance router
 * Description: Avoids loading non-essential back-office integrations on uncached public form views.
 * Version: 1.2.0
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

add_filter('option_active_plugins', static function (array $plugins): array {
    if (is_admin() || wp_doing_ajax() || (defined('WP_CLI') && WP_CLI)) {
        return $plugins;
    }

    $method = isset($_SERVER['REQUEST_METHOD'])
        ? strtoupper(sanitize_text_field(wp_unslash($_SERVER['REQUEST_METHOD'])))
        : 'GET';
    if (! in_array($method, ['GET', 'HEAD'], true)) {
        return $plugins;
    }

    $request_uri = isset($_SERVER['REQUEST_URI'])
        ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI']))
        : '';
    $path = (string) wp_parse_url($request_uri, PHP_URL_PATH);
    $form_paths = ['/enquire/', '/already-a-client/'];

    if (! in_array(trailingslashit($path), $form_paths, true)) {
        return $plugins;
    }

    $back_office_plugins = [
        'updraftplus/updraftplus.php',
        'wordpress-seo/wp-seo.php',
        'wp-mail-smtp/wp_mail_smtp.php',
    ];

    return array_values(array_diff($plugins, $back_office_plugins));
});
