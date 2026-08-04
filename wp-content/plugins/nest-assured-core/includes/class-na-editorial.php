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
        add_filter('the_content', [self::class, 'add_guide_footer'], 13);
        add_filter('body_class', [self::class, 'body_class']);
        add_action('wp_head', [self::class, 'render_article_metadata'], 4);
        add_filter('wpseo_opengraph_image', [self::class, 'social_image']);
        add_filter('wpseo_twitter_image', [self::class, 'social_image']);
        add_filter('wpseo_add_opengraph_images', [self::class, 'add_open_graph_image']);
        add_filter('wpseo_opengraph_image_width', static fn (): int => 1200);
        add_filter('wpseo_opengraph_image_height', static fn (): int => 630);
        add_filter('wpseo_opengraph_type', [self::class, 'open_graph_type']);
        add_filter('wpseo_schema_organization', [self::class, 'organization_schema']);
        add_filter('wpseo_breadcrumb_links', [self::class, 'breadcrumb_links']);
    }

    /**
     * Describe the firm properly: a generic Organization with only a name, URL and
     * logo forfeits the local pack for a location-based advisory firm, and carried
     * no address, telephone or business type at all.
     *
     * @param array<string, mixed> $data Organization schema node.
     * @return array<string, mixed>
     */
    public static function organization_schema(array $data): array
    {
        $data['@type'] = ['Organization', 'FinancialService'];

        $data['address'] = [
            '@type'           => 'PostalAddress',
            'streetAddress'   => '133 Shepherds Hill',
            'addressLocality' => 'Harold Wood',
            'addressRegion'   => 'Essex',
            'postalCode'      => 'RM3 0NR',
            'addressCountry'  => 'GB',
        ];

        $data['areaServed'] = [
            '@type' => 'Country',
            'name'  => 'United Kingdom',
        ];

        $phone = trim(NA_Settings::get('contact_phone'));
        if ('' !== $phone) {
            $data['telephone'] = $phone;
        }

        $email = trim(NA_Settings::get('contact_email'));
        if (is_email($email)) {
            $data['email'] = $email;
        }

        $reviews = trim(NA_Settings::get('google_reviews_url'));
        if ('' !== $reviews) {
            $data['sameAs'] = array_values(array_unique(array_merge(
                isset($data['sameAs']) && is_array($data['sameAs']) ? $data['sameAs'] : [],
                [$reviews]
            )));
        }

        return $data;
    }

    /**
     * The visible breadcrumb on a guide reads Home / Guides / Article, but the
     * machine-readable trail skipped the Guides level entirely.
     *
     * @param array<int, array<string, mixed>> $crumbs Breadcrumb links.
     * @return array<int, array<string, mixed>>
     */
    public static function breadcrumb_links(array $crumbs): array
    {
        if (! self::is_guide() || count($crumbs) < 2) {
            return $crumbs;
        }

        $guides_url = home_url('/guides/');

        foreach ($crumbs as $crumb) {
            $url = (string) ($crumb['url'] ?? '');
            if ('' !== $url && untrailingslashit($url) === untrailingslashit($guides_url)) {
                return $crumbs;
            }
        }

        $tail = array_pop($crumbs);

        // Google expects the markup to correspond to the breadcrumb the reader can
        // see. The visible trail uses a short crumb; the schema was using the full
        // page title, so thirty of the thirty-three guides disagreed with
        // themselves.
        $visible = self::visible_crumb_label();
        if ('' !== $visible) {
            $tail['text'] = $visible;
        }

        $crumbs[] = ['url' => $guides_url, 'text' => 'Guides'];
        $crumbs[] = $tail;

        return array_values($crumbs);
    }

    /**
     * The last crumb as rendered in the article's own breadcrumb nav.
     */
    private static function visible_crumb_label(): string
    {
        $post = get_post(get_queried_object_id());
        if (! $post instanceof WP_Post) {
            return '';
        }

        if (preg_match('#<nav class="na-breadcrumbs".*?<span>([^<]{1,80})</span>\s*</nav>#s', $post->post_content, $m)) {
            return trim(wp_strip_all_tags($m[1]));
        }

        return '';
    }

    /**
     * Every guide slug, including the second series.
     *
     * @return array<int, string>
     */
    private static function all_guide_slugs(): array
    {
        static $slugs = null;

        if (null === $slugs) {
            $slugs = self::GUIDE_SLUGS;
            if (class_exists('NA_Guides_Library')) {
                $slugs = array_merge($slugs, array_keys(NA_Guides_Library::meta()));
            }
        }

        return $slugs;
    }

    public static function is_guide(?int $post_id = null): bool
    {
        $post_id = $post_id ?? get_queried_object_id();
        if ($post_id <= 0) {
            return false;
        }

        return in_array((string) get_post_field('post_name', $post_id), self::all_guide_slugs(), true);
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
            // substr_replace, not preg_replace: a guide title containing a dollar sign
            // would be read as a backreference in a replacement string and silently
            // mangled, so "Is $1m of cover enough?" would lose the $1.
            $anchor = strpos($content, '</nav>');
            if (false !== $anchor) {
                return substr_replace($content, '</nav>' . $heading . $information, $anchor, 6);
            }
        }

        return $heading . $information . $content;
    }

    /**
     * Guides that carry a planning tool, because the reader has arrived asking a
     * question the tool actually answers.
     *
     * @return array<string, string>
     */
    private static function guide_calculators(): array
    {
        return [
            'how-much-life-insurance-do-i-need'    => 'nest_assured_cover_calculator',
            'how-much-income-protection-can-i-get' => 'nest_assured_income_calculator',
            'income-protection-and-sick-pay'       => 'nest_assured_income_calculator',
            'life-insurance-and-mortgages'         => 'nest_assured_cover_calculator',
        ];
    }

    public static function guide_has_calculator(int $post_id): bool
    {
        $slug = (string) get_post_field('post_name', $post_id);

        return isset(self::guide_calculators()[$slug]);
    }

    /**
     * Everything after the article: the relevant planning tool, guides on the same
     * subject, and a route to an adviser.
     *
     * The library had almost no cross-linking, which wastes both the reader's
     * attention and the internal linking that makes a library worth having.
     */
    public static function add_guide_footer(string $content): string
    {
        if (is_admin() || ! is_singular('page') || ! in_the_loop() || ! is_main_query() || ! self::is_guide()) {
            return $content;
        }

        $post_id = get_queried_object_id();
        $slug = (string) get_post_field('post_name', $post_id);
        $out = '';

        $calculators = self::guide_calculators();
        if (isset($calculators[$slug])) {
            // Shortcodes are already expanded by this point, so this one is run
            // explicitly rather than left as literal text in the output.
            $out .= do_shortcode('[' . $calculators[$slug] . ']');
        }

        $out .= self::related_guides($slug);

        $out .= '<aside class="na-guide-cta">'
            . '<div><h2>Still the wrong question for your situation?</h2>'
            . '<p>A guide can frame what to ask. Weighing your health, your budget and the cover you already hold is what the conversation is for.</p></div>'
            . '<a class="na-v2-btn na-v2-btn--gold" href="' . esc_url(home_url('/enquire/')) . '">Talk to an adviser</a>'
            . '</aside>';

        return $content . $out;
    }

    /**
     * Up to three guides from the same part of the library, then a link to the rest.
     */
    private static function related_guides(string $slug): string
    {
        if (! class_exists('NA_Guides_Library')) {
            return '';
        }

        $catalogue = NA_Guides_Library::catalogue();
        if (! isset($catalogue[$slug])) {
            return '';
        }

        $group = $catalogue[$slug]['group'];
        $cards = '';
        $shown = 0;

        foreach ($catalogue as $other => $guide) {
            if ($other === $slug || $guide['group'] !== $group || $shown >= 3) {
                continue;
            }

            $cards .= '<a class="na-v2-guide" href="' . esc_url(home_url('/guides/' . $other . '/')) . '">'
                . '<p class="na-v2-eyebrow">' . esc_html($guide['eyebrow']) . '</p>'
                . '<h3>' . esc_html($guide['title']) . '</h3></a>';
            $shown++;
        }

        if ('' === $cards) {
            return '';
        }

        $groups = NA_Guides_Library::groups();
        $label = $groups[$group] ?? 'More guides';

        return '<section class="na-guide-related" aria-labelledby="na-related-title">'
            . '<div class="na-guide-related__head">'
            . '<h2 id="na-related-title">More on ' . esc_html(strtolower($label)) . '</h2>'
            . '<a class="na-v2-link" href="' . esc_url(home_url('/guides/')) . '">All guides <span aria-hidden="true">&rarr;</span></a>'
            . '</div><div class="na-v2-guides">' . $cards . '</div></section>';
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
            '<a href="https://www.fca.org.uk/consumers/your-rights-financial-services" target="_blank" rel="noopener noreferrer">FCA consumer rights</a>',
        ];

        if (str_contains($slug, 'income-protection')) {
            $links[] = '<a href="https://www.moneyhelper.org.uk/en/everyday-money/insurance/what-is-income-protection-insurance" target="_blank" rel="noopener noreferrer">MoneyHelper income protection guidance</a>';
        } elseif ('life-insurance-and-trusts' === $slug) {
            $links[] = '<a href="https://www.gov.uk/trusts-taxes" target="_blank" rel="noopener noreferrer">GOV.UK trusts guidance</a>';
        } elseif ('making-a-protection-insurance-claim' === $slug || 'buildings-and-contents-insurance' === $slug) {
            $links[] = '<a href="https://www.abi.org.uk/products-and-issues/choosing-the-right-insurance/" target="_blank" rel="noopener noreferrer">ABI consumer insurance guidance</a>';
        } else {
            $links[] = '<a href="https://www.moneyhelper.org.uk/en/everyday-money/insurance" target="_blank" rel="noopener noreferrer">MoneyHelper insurance guidance</a>';
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
            'headline'         => wp_strip_all_tags((string) get_the_title($post_id)),
            'description'      => wp_strip_all_tags($description),
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
                'name'  => wp_strip_all_tags($reviewer),
            ];
        }

        echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) . '</script>' . "\n";
    }
}
