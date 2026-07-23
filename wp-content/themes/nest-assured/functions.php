<?php
/**
 * Nest Assured theme functions.
 *
 * @package NestAssured
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

add_action('after_setup_theme', static function (): void {
    add_theme_support('title-tag');
    add_theme_support('responsive-embeds');
    add_theme_support('wp-block-styles');
    add_editor_style('style.css');

});

add_action('wp_enqueue_scripts', static function (): void {
    $stylesheet = get_stylesheet_directory() . '/style.css';
    $script = get_stylesheet_directory() . '/assets/js/site.js';
    wp_enqueue_style(
        'nest-assured',
        get_stylesheet_uri(),
        [],
        is_file($stylesheet) ? (string) filemtime($stylesheet) : '1.0.0'
    );
    wp_enqueue_script(
        'nest-assured-site',
        get_stylesheet_directory_uri() . '/assets/js/site.js',
        [],
        is_file($script) ? (string) filemtime($script) : '1.0.0',
        true
    );
});

add_filter('should_load_separate_core_block_assets', '__return_true');

add_action('init', static function (): void {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'feed_links_extra', 3);
    remove_action('wp_head', 'wp_shortlink_wp_head', 10);
    remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
    remove_action('wp_head', 'wp_oembed_add_host_js', 10);
});

add_action('wp_head', static function (): void {
    if (defined('WPSEO_VERSION')) {
        remove_action('wp_head', '_wp_render_title_tag', 1);
        remove_action('wp_head', '_block_template_render_title_tag', 1);
    }
}, 0);
