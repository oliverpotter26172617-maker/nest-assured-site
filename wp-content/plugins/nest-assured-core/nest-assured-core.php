<?php
/**
 * Plugin Name: Nest Assured Core
 * Description: Enquiry routing, needs assessment, booking controls and site setup for Nest Assured.
 * Version: 1.5.2
 * Requires at least: 6.7
 * Requires PHP: 8.1
 * Author: Nest Assured
 * Text Domain: nest-assured-core
 * License: GPL-2.0-or-later
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

if (defined('XMLRPC_REQUEST') && XMLRPC_REQUEST) {
    status_header(403);
    header('Content-Type: text/plain; charset=UTF-8', true);
    exit('XML-RPC is disabled.');
}

define('NA_CORE_VERSION', '1.5.2');
define('NA_CORE_FILE', __FILE__);
define('NA_CORE_DIR', plugin_dir_path(__FILE__));
define('NA_CORE_URL', plugin_dir_url(__FILE__));

require_once NA_CORE_DIR . 'includes/class-na-settings.php';
require_once NA_CORE_DIR . 'includes/class-na-enquiry.php';
require_once NA_CORE_DIR . 'includes/class-na-shortcodes.php';
require_once NA_CORE_DIR . 'includes/class-na-content-expansion.php';
require_once NA_CORE_DIR . 'includes/class-na-editorial.php';
require_once NA_CORE_DIR . 'includes/class-na-site-setup.php';

register_activation_hook(__FILE__, ['NA_Site_Setup', 'install']);
register_deactivation_hook(__FILE__, static function (): void {
    wp_clear_scheduled_hook('na_delete_expired_enquiries');
});

add_action('plugins_loaded', static function (): void {
    NA_Settings::init();
    NA_Enquiry::init();
    NA_Shortcodes::init();
    NA_Editorial::init();
    NA_Site_Setup::register_command();
});

add_action('init', static function (): void {
    if (NA_CORE_VERSION === (string) get_option('na_site_build_version')) {
        return;
    }

    NA_Site_Setup::install();
    if (function_exists('wp_cache_clear_cache')) {
        wp_cache_clear_cache();
    }
}, 20);

add_action('wp_enqueue_scripts', static function (): void {
    $post = get_post();
    $has_interaction = is_front_page()
        || ($post instanceof WP_Post && (
            has_shortcode($post->post_content, 'nest_assured_enquiry')
            || has_shortcode($post->post_content, 'nest_assured_assessment')
        ));

    if (! $has_interaction) {
        return;
    }

    $css = NA_CORE_DIR . 'assets/frontend.css';
    $js  = NA_CORE_DIR . 'assets/frontend.js';

    wp_enqueue_style(
        'nest-assured-core',
        NA_CORE_URL . 'assets/frontend.css',
        [],
        is_file($css) ? (string) filemtime($css) : NA_CORE_VERSION
    );
    wp_enqueue_script(
        'nest-assured-core',
        NA_CORE_URL . 'assets/frontend.js',
        [],
        is_file($js) ? (string) filemtime($js) : NA_CORE_VERSION,
        true
    );
});

add_filter('robots_txt', static function (string $output): string {
    header('Content-Type: text/plain; charset=UTF-8', true);
    return $output;
}, PHP_INT_MAX);

add_action('send_headers', static function (): void {
    $request_uri = isset($_SERVER['REQUEST_URI'])
        ? sanitize_text_field(wp_unslash($_SERVER['REQUEST_URI']))
        : '';
    $path = wp_parse_url($request_uri, PHP_URL_PATH);

    if ('/robots.txt' === $path) {
        header('Content-Type: text/plain; charset=UTF-8', true);
    }

    if (! is_admin()) {
        header_remove('X-Powered-By');
        header('X-Content-Type-Options: nosniff', true);
        header('X-Frame-Options: SAMEORIGIN', true);
        header('Referrer-Policy: strict-origin-when-cross-origin', true);
        header('Permissions-Policy: camera=(), geolocation=(), microphone=()', true);

        $frame_sources = ["'self'"];
        $booking_url = NA_Settings::get('booking_url');
        if ('' !== $booking_url) {
            $booking_origin = wp_parse_url($booking_url, PHP_URL_SCHEME) . '://' . wp_parse_url($booking_url, PHP_URL_HOST);
            if (! str_ends_with($booking_origin, '://')) {
                $frame_sources[] = $booking_origin;
            }
        }

        $policy = "default-src 'self'; base-uri 'self'; form-action 'self'; frame-ancestors 'self'; object-src 'none'; "
            . "img-src 'self' data: https:; font-src 'self' data:; style-src 'self' 'unsafe-inline'; "
            . "script-src 'self' 'unsafe-inline'; connect-src 'self'; frame-src " . implode(' ', $frame_sources);
        header('Content-Security-Policy: ' . $policy, true);

        if (is_ssl()) {
            header('Strict-Transport-Security: max-age=31536000; includeSubDomains', true);
        }
    }
}, PHP_INT_MAX);

add_filter('wpsc_known_headers', static function (array $headers): array {
    foreach (['Permissions-Policy', 'Content-Security-Policy'] as $header) {
        if (! in_array($header, $headers, true)) {
            $headers[] = $header;
        }
    }
    return $headers;
});

add_filter('xmlrpc_enabled', '__return_false');
add_filter('xmlrpc_methods', '__return_empty_array');

add_filter('wp_headers', static function (array $headers): array {
    unset($headers['X-Pingback']);
    return $headers;
});

add_filter('rest_endpoints', static function (array $endpoints): array {
    if (is_user_logged_in()) {
        return $endpoints;
    }

    foreach (array_keys($endpoints) as $route) {
        if (preg_match('#^/wp/v2/users(?:/|$)#', (string) $route)) {
            unset($endpoints[$route]);
        }
    }

    return $endpoints;
});

add_filter('wp_robots', static function (array $robots): array {
    if (! is_singular('page')) {
        return $robots;
    }

    if ('1' !== (string) get_post_meta(get_queried_object_id(), '_yoast_wpseo_meta-robots-noindex', true)) {
        return $robots;
    }

    $robots['noindex'] = true;
    $robots['follow'] = true;
    unset($robots['index'], $robots['max-image-preview']);
    return $robots;
});

add_filter('document_title_separator', static function (string $separator): string {
    return defined('WPSEO_VERSION') ? $separator : '|';
});

add_action('wp_head', static function (): void {
    if (defined('WPSEO_VERSION') || ! is_singular('page')) {
        return;
    }

    $description = trim((string) get_post_meta(get_queried_object_id(), '_yoast_wpseo_metadesc', true));
    if ('' === $description) {
        return;
    }

    remove_action('wp_head', 'rel_canonical');

    $title = wp_get_document_title();
    $canonical = get_permalink();
    echo '<meta name="description" content="' . esc_attr($description) . '" />' . "\n";
    echo '<link rel="canonical" href="' . esc_url($canonical) . '" />' . "\n";
    echo '<meta property="og:title" content="' . esc_attr($title) . '" />' . "\n";
    echo '<meta property="og:description" content="' . esc_attr($description) . '" />' . "\n";
    echo '<meta property="og:url" content="' . esc_url($canonical) . '" />' . "\n";
    echo '<meta property="og:type" content="website" />' . "\n";
}, 1);
