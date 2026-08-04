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

    // Without editor-styles support the editor iframe loaded neither stylesheet, so
    // v2 pages were edited against v1 chrome or none at all.
    add_theme_support('editor-styles');
    add_editor_style('style.css');
    add_editor_style('assets/css/v2.css');
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

    $v2 = get_stylesheet_directory() . '/assets/css/v2.css';
    wp_enqueue_style(
        'nest-assured-v2',
        get_stylesheet_directory_uri() . '/assets/css/v2.css',
        ['nest-assured'],
        is_file($v2) ? (string) filemtime($v2) : '1.0.0'
    );
});

add_filter('should_load_separate_core_block_assets', '__return_true');

/**
 * v2 pages and guide articles carry their own <h1> inside the content, so the
 * template's post-title block would produce a second one. Suppress it for those
 * views only; pages still on the original layout keep the template title.
 *
 * This is deliberately gated on the queried object. Query loops call
 * setup_postdata() for each looped post, so an ungated filter blanked the linked
 * titles in listings and search results as well.
 */
add_filter('render_block_core/post-title', static function (string $block_content, array $block): string {
    if (! is_singular()) {
        return $block_content;
    }

    $queried_id = get_queried_object_id();
    $block_post_id = isset($block['attrs']['postId']) ? (int) $block['attrs']['postId'] : $queried_id;

    if ($block_post_id !== $queried_id || $queried_id <= 0) {
        return $block_content;
    }

    if (class_exists('NA_Editorial') && NA_Editorial::is_guide($queried_id)) {
        return '';
    }

    $post = get_post($queried_id);
    if ($post instanceof WP_Post && str_contains($post->post_content, 'na-v2')) {
        return '';
    }

    return $block_content;
}, 10, 2);

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

/**
 * Preload the two typefaces.
 *
 * WordPress prints its @font-face block at wp_head priority 50, after the
 * stylesheet links, so the browser could not begin fetching either file until it
 * had parsed almost the whole head. The heading face is on the critical path for
 * the largest element on most pages.
 */
add_action('wp_head', static function (): void {
    foreach (['newsreader-variable.woff2', 'publicsans-variable.woff2'] as $font) {
        if (! is_file(get_stylesheet_directory() . '/assets/fonts/' . $font)) {
            continue;
        }

        printf(
            '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin />' . "
",
            esc_url(get_stylesheet_directory_uri() . '/assets/fonts/' . $font)
        );
    }
}, 1);
