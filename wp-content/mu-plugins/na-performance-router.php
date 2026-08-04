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

    // Yoast is deliberately NOT unloaded here any more. Removing it stripped the
    // canonical, Open Graph and Twitter tags from /enquire/, which is precisely the
    // URL that gets pasted into emails, messages and adverts, and it was never the
    // cost it was assumed to be. WP Mail SMTP is also left alone: no mail is sent on
    // a GET, so unloading it saved nothing.
    $back_office_plugins = [
        'updraftplus/updraftplus.php',
    ];

    return array_values(array_diff($plugins, $back_office_plugins));
});

// Stop filtering as soon as the plugin list has been consumed, so nothing can
// read the reduced list and write it back, permanently deactivating the plugin.
add_action('plugins_loaded', static function (): void {
    remove_all_filters('option_active_plugins');
}, PHP_INT_MAX);
