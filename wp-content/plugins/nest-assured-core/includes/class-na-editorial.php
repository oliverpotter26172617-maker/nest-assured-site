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

        $words = str_word_count(wp_strip_all_tags($content));
        $minutes = max(2, (int) ceil($words / 200));
        $reviewed = get_the_modified_date('j F Y');
        $information = sprintf(
            '<aside class="na-editorial-meta" aria-label="Guide information"><div><span>Written by</span><strong>Nest Assured editorial team</strong></div><div><span>Reviewed by</span><strong><a href="/about/">Ollie Allen, Protection Adviser</a></strong></div><div><span>Last reviewed</span><strong>%1$s</strong></div><div><span>Reading time</span><strong>%2$d minutes</strong></div></aside>%3$s',
            esc_html($reviewed),
            $minutes,
            self::reference_links((string) get_post_field('post_name', get_queried_object_id()))
        );

        if (str_contains($content, '</nav>')) {
            return preg_replace('/<\/nav>/', '</nav>' . $information, $content, 1) ?? $content;
        }

        return $information . $content;
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
            'reviewedBy'       => [
                '@type' => 'Person',
                'name'  => 'Ollie Allen',
                'jobTitle' => 'Protection Adviser',
            ],
            'publisher'        => [
                '@type' => 'Organization',
                'name'  => 'Nest Assured',
                'logo'  => [
                    '@type' => 'ImageObject',
                    'url'   => get_stylesheet_directory_uri() . '/assets/images/nest-assured-bird-512.png',
                ],
            ],
        ];

        echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '</script>' . "\n";
    }
}
