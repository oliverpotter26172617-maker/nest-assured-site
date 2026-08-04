<?php
/**
 * Editorial trust signals, share metadata and structured data.
 *
 * @package NestAssuredCore
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

final class NA_Editorial
{
    /** @var array<int, string> */
    private const GUIDE_SLUGS = [
        'life-insurance-vs-critical-illness-cover',
        'income-protection-and-sick-pay',
        'choosing-private-medical-insurance',
        'types-of-business-protection',
        'buildings-and-contents-insurance',
        'when-to-review-protection-insurance',
        'making-a-protection-insurance-claim',
        'insurance-jargon-buster',
        'income-protection-for-self-employed',
        'relevant-life-vs-key-person-cover',
        'leaving-company-private-medical-insurance',
        'life-insurance-and-trusts',
        'preparing-for-protection-appointment',
    ];

    public static function init(): void
    {
        add_filter('the_content', [self::class, 'add_guide_information'], 12);
        add_filter('body_class', [self::class, 'body_class']);
        add_action('wp_head', [self::class, 'render_article_metadata'], 4);
        add_filter('wpseo_opengraph_image', [self::class, 'social_image']);
        add_filter('wpseo_twitter_image', [self::class, 'social_image']);
        add_filter('wpseo_add_opengraph_images', [self::class, 'add_open_graph_image']);
        add_filter('wpseo_opengraph_image_width', static fn (): int => 1200);
        add_filter('wpseo_opengraph_image_height', static fn (): int => 630);
        add_filter('wpseo_opengraph_type', [self::class, 'open_graph_type']);
    }

    public static function is_guide(?int $post_id = null): bool
    {
        $post_id = $post_id ?? get_queried_object_id();
        if ($post_id <= 0) {
            return false;
        }

        return in_array((string) get_post_field('post_name', $post_id), self::GUIDE_SLUGS, true);
    }

    public static function social_image(string $current = ''): string
    {
        $image = get_stylesheet_directory_uri() . '/assets/images/nest-assured-share.png';
        return is_file(get_stylesheet_directory() . '/assets/images/nest-assured-share.png') ? $image : $current;
    }

    public static function open_graph_type(string $type): string
    {
        return self::is_guide() ? 'article' : $type;
    }

    /**
     * @param object $container Yoast Open Graph image container.
     * @return object
     */
    public static function add_open_graph_image(object $container): object
    {
        if (method_exists($container, 'add_image_by_url')) {
            $container->add_image_by_url(self::social_image());
        }
        return $container;
    }

    /**
     * @param array<int, string> $classes Body classes.
     * @return array<int, string>
     */
    public static function body_class(array $classes): array
    {
        if (self::is_guide()) {
            $classes[] = 'na-editorial-guide';
        }
        return $classes;
    }

    public static function add_guide_information(string $content): string
    {
        if (is_admin() || ! is_singular('page') || ! in_the_loop() || ! is_main_query() || ! self::is_guide()) {
            return $content;
        }

        $post_id = get_queried_object_id();
        $minutes = self::reading_minutes($content);

        // The review credit is published only where an actual review has been
        // recorded against this guide. It was previously stamped on every article
        // regardless, which asserted a review that had not taken place.
        $reviewer = trim((string) get_post_meta($post_id, '_na_reviewed_by', true));
        $reviewed_on = trim((string) get_post_meta($post_id, '_na_reviewed_on', true));

        $review_row = '';
        if ('' !== $reviewer) {
            $review_row = '<div><span>Reviewed by</span><strong><a href="' . esc_url(home_url('/about/')) . '">' . esc_html($reviewer) . '</a></strong></div>';
            if ('' !== $reviewed_on) {
                $review_row .= '<div><span>Last reviewed</span><strong>' . esc_html(mysql2date('j F Y', $reviewed_on)) . '</strong></div>';
            }
        }

        $information = sprintf(
            '<aside class="na-editorial-meta" aria-label="Guide information"><div><span>Written by</span><strong>Nest Assured editorial team</strong></div>%1$s<div><span>Last updated</span><strong>%2$s</strong></div><div><span>Reading time</span><strong>%3$s</strong></div></aside>%4$s',
            $review_row,
            esc_html(get_the_modified_date('j F Y')),
            esc_html(sprintf(_n('%d minute', '%d minutes', $minutes, 'nest-assured-core'), $minutes)),
            self::reference_links((string) get_post_field('post_name', $post_id))
        );

        // Every guide needs a real, visible level-one heading. The template's title
        // block is suppressed for guides (see the theme's functions.php), so the
        // heading is published here, immediately after the breadcrumb trail, using the
        // same string as the page title and the Article headline.
        $heading = '<h1 class="na-guide-title">' . esc_html(get_the_title($post_id)) . '</h1>';

        if (str_contains($content, '</nav>')) {
            return preg_replace('/<\/nav>/', '</nav>' . $heading . $information, $content, 1) ?? $content;
        }

        return $heading . $information . $content;
    }

    /**
     * Reading time in whole minutes.
     *
     * str_word_count() is not multibyte-safe and undercounts the typographic
     * apostrophes and dashes used throughout these guides, which pinned every
     * article to the two-minute floor.
     */
    public static function reading_minutes(string $content): int
    {
        $text = wp_strip_all_tags($content);
        $words = preg_match_all('/[\p{L}\p{N}]+/u', $text);

        return max(1, (int) ceil(((int) $words) / 200));
    }

    private static function reference_links(string $slug): string
    {
        $links = [
            '<a href="/editorial-policy/">How we review guides</a>',
            '<a href="https://www.fca.org.uk/consumers/your-rights-financial-services" rel="noopener noreferrer">FCA consumer rights</a>',
        ];

        if (str_contains($slug, 'income-protection')) {
            $links[] = '<a href="https://www.moneyhelper.org.uk/en/everyday-money/insurance/what-is-income-protection-insurance" rel="noopener noreferrer">MoneyHelper income protection guidance</a>';
        } elseif ('life-insurance-and-trusts' === $slug) {
            $links[] = '<a href="https://www.gov.uk/trusts-taxes" rel="noopener noreferrer">GOV.UK trusts guidance</a>';
        } elseif ('making-a-protection-insurance-claim' === $slug || 'buildings-and-contents-insurance' === $slug) {
            $links[] = '<a href="https://www.abi.org.uk/products-and-issues/choosing-the-right-insurance/" rel="noopener noreferrer">ABI consumer insurance guidance</a>';
        } else {
            $links[] = '<a href="https://www.moneyhelper.org.uk/en/everyday-money/insurance" rel="noopener noreferrer">MoneyHelper insurance guidance</a>';
        }

        return '<nav class="na-guide-references" aria-label="Guide references"><span>References</span>' . implode('<span aria-hidden="true">&middot;</span>', $links) . '</nav>';
    }

    public static function render_article_metadata(): void
    {
        if (! is_singular('page') || ! self::is_guide()) {
            return;
        }

        $post_id = get_queried_object_id();
        $description = (string) get_post_meta($post_id, '_yoast_wpseo_metadesc', true);
        $image = self::social_image();
        $published = get_the_date(DATE_W3C, $post_id);
        $modified = get_the_modified_date(DATE_W3C, $post_id);

        echo '<meta property="article:published_time" content="' . esc_attr($published) . '" />' . "\n";
        echo '<meta property="article:modified_time" content="' . esc_attr($modified) . '" />' . "\n";

        $schema = [
            '@context'         => 'https://schema.org',
            '@type'            => 'Article',
            'headline'         => get_the_title($post_id),
            'description'      => $description,
            'datePublished'    => $published,
            'dateModified'     => $modified,
            'mainEntityOfPage' => get_permalink($post_id),
            'image'            => [$image],
            'author'           => [
                '@type' => 'Organization',
                'name'  => 'Nest Assured editorial team',
            ],
            // Point at Yoast's organisation node rather than declaring a second
            // Organization with a different logo URL, which left two competing
            // publisher nodes in the graph.
            'publisher'        => [
                '@id' => home_url('/#organization'),
            ],
        ];

        // Machine-readable review claims follow the same rule as the visible byline:
        // published only where a review has actually been recorded.
        $reviewer = trim((string) get_post_meta($post_id, '_na_reviewed_by', true));
        if ('' !== $reviewer) {
            $schema['reviewedBy'] = [
                '@type' => 'Person',
                'name'  => $reviewer,
            ];
        }

        echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
    }
}
