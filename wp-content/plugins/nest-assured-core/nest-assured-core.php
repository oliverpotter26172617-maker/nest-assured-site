<?php
/**
 * Plugin Name: Nest Assured Core
 * Description: Enquiry routing, needs assessment, booking controls and site setup for Nest Assured.
 * Version: 2.12.1
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

define('NA_CORE_VERSION', '2.12.1');
define('NA_CORE_FILE', __FILE__);
define('NA_CORE_DIR', plugin_dir_path(__FILE__));
define('NA_CORE_URL', plugin_dir_url(__FILE__));

require_once NA_CORE_DIR . 'includes/class-na-settings.php';
require_once NA_CORE_DIR . 'includes/class-na-enquiry.php';
require_once NA_CORE_DIR . 'includes/class-na-shortcodes.php';
require_once NA_CORE_DIR . 'includes/class-na-content-expansion.php';
require_once NA_CORE_DIR . 'includes/class-na-editorial.php';
require_once NA_CORE_DIR . 'includes/class-na-calculators.php';
require_once NA_CORE_DIR . 'includes/class-na-faq.php';
require_once NA_CORE_DIR . 'includes/class-na-guides-expanded.php';
require_once NA_CORE_DIR . 'includes/class-na-guides-library.php';
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
    NA_Calculators::init();
    NA_Faq::init();
    NA_Site_Setup::register_command();
    add_action('admin_notices', ['NA_Site_Setup', 'backup_notice']);
});

add_action('init', static function (): void {
    if (NA_CORE_VERSION === (string) get_option('na_site_build_version')) {
        return;
    }

    // Front-end GETs may trigger the installer: with no WP-CLI available on this
    // stack, curling a route is the only way to deploy seeded content. What must not
    // trigger it is a form submission, which previously paid for forty page upserts
    // and a rewrite flush before the enquiry was handled.
    $is_post = isset($_SERVER['REQUEST_METHOD']) && 'POST' === strtoupper((string) $_SERVER['REQUEST_METHOD']);
    if ($is_post && ! wp_doing_cron() && ! (defined('WP_CLI') && WP_CLI)) {
        return;
    }

    // add_option() is atomic: a concurrent request loses the race and returns, rather
    // than both running the installer and creating duplicate pages (privacy-2, and so
    // on) that the indexing gate does not know about.
    if (! add_option('na_install_lock', time(), '', false)) {
        $lock = (int) get_option('na_install_lock');
        if ($lock > time() - (5 * MINUTE_IN_SECONDS)) {
            return;
        }
        // A stale lock means a previous run died mid-way; take it over.
        update_option('na_install_lock', time(), false);
    }

    try {
        NA_Site_Setup::install();
    } finally {
        delete_option('na_install_lock');
    }

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

    // Nothing is approved yet, so there is no reason to advertise a sitemap of
    // unapproved financial promotions. Crawling stays permitted so the noindex on
    // each page can actually be read.
    if ([] !== NA_Settings::missing_compliance_controls() || ! NA_Settings::is_signed_off()) {
        return $output;
    }

    if (! str_contains($output, 'Sitemap:')) {
        $sitemap = defined('WPSEO_VERSION') ? home_url('/sitemap_index.xml') : home_url('/wp-sitemap.xml');
        $output = rtrim($output) . "\n\nSitemap: " . $sitemap . "\n";
    }

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

    // Feeds bypass the wp_robots filter entirely, so they were the one route out
    // of the pre-launch gate.
    if (is_feed() && ([] !== NA_Settings::missing_compliance_controls() || ! NA_Settings::is_signed_off())) {
        header('X-Robots-Tag: noindex, follow', true);
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

        // Analytics origins are not hardcoded, but the policy must be extensible:
        // script-src 'self' blocks every tag manager, so the tracking calls already
        // present in the theme cannot fire until the chosen origins are added here.
        // Add them with add_filter('nest_assured_csp_sources', ...) at launch.
        $sources = apply_filters('nest_assured_csp_sources', [
            'script-src'  => [],
            'connect-src' => [],
            'img-src'     => [],
            'frame-src'   => [],
        ]);

        $extra = static function (string $directive) use ($sources): string {
            $values = isset($sources[$directive]) && is_array($sources[$directive]) ? $sources[$directive] : [];
            $values = array_filter(array_map('trim', $values));

            return [] === $values ? '' : ' ' . implode(' ', $values);
        };

        $policy = "default-src 'self'; base-uri 'self'; form-action 'self'; frame-ancestors 'self'; object-src 'none'; "
            . "img-src 'self' data: https:" . $extra('img-src') . '; font-src \'self\' data:; style-src \'self\' \'unsafe-inline\'; '
            . "script-src 'self' 'unsafe-inline'" . $extra('script-src') . "; connect-src 'self'" . $extra('connect-src')
            . '; frame-src ' . trim(implode(' ', $frame_sources) . $extra('frame-src'));
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
    // Fail closed. Until compliance has approved the wording and signed it off, every
    // page is an unapproved financial promotion, so nothing is indexable. Enumerating
    // a handful of legal slugs left the entire marketing surface open to search.
    if ([] !== NA_Settings::missing_compliance_controls() || ! NA_Settings::is_signed_off()) {
        $robots['noindex'] = true;
        $robots['follow'] = true;
        unset($robots['index'], $robots['max-image-preview']);
        return $robots;
    }

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
}, PHP_INT_MAX);

// Yoast writes its own robots directive, so the gate is asserted there too rather
// than relying on filter ordering between the two.
add_filter('wpseo_robots', static function ($robots) {
    if ([] !== NA_Settings::missing_compliance_controls() || ! NA_Settings::is_signed_off()) {
        return 'noindex, follow';
    }

    return $robots;
}, PHP_INT_MAX);

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
