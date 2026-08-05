<?php
/**
 * Idempotent site content setup.
 *
 * @package NestAssuredCore
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

final class NA_Site_Setup
{
    public static function register_command(): void
    {
        if (defined('WP_CLI') && WP_CLI) {
            WP_CLI::add_command('nest-assured install', [self::class, 'cli_install']);
            WP_CLI::add_command('nest-assured cache-clear', [self::class, 'cli_clear_cache']);
        }
    }

    /**
     * Run the content installer from WP-CLI.
     */
    public static function cli_install(): void
    {
        self::install();
        WP_CLI::success('Nest Assured pages and site settings are installed.');
    }

    /**
     * Clear the page cache after a controlled build update.
     */
    public static function cli_clear_cache(): void
    {
        if (function_exists('wp_cache_clear_cache')) {
            wp_cache_clear_cache();
            WP_CLI::success('WP Super Cache files cleared.');
            return;
        }

        wp_cache_flush();
        WP_CLI::warning('WP Super Cache was unavailable, so only the object cache was cleared.');
    }

    public static function install(): void
    {
        $legal_id = self::upsert_page('Legal information', 'legal', self::legal_index());

        $pages = [
            ['Home', 'home', self::home()],
            ['Life insurance', 'life-insurance', self::life_insurance()],
            ['Income protection', 'income-protection', self::income_protection()],
            ['Critical illness cover', 'critical-illness-cover', self::critical_illness()],
            ['Family protection', 'family-protection', self::family_protection()],
            ['Private medical insurance', 'private-medical-insurance', self::private_medical_insurance()],
            ['Business protection', 'business-protection', self::business_protection()],
            ['General insurance', 'general-insurance', self::general_insurance()],
            ['Protection guides', 'guides', self::guides()],
            ['Protection calculators', 'calculators', self::calculators()],
            ['Find your starting point', 'find-your-starting-point', self::starting_point()],
            ['Editorial policy', 'editorial-policy', NA_Content_Expansion::editorial_policy()],
            ['Already a client', 'already-a-client', self::already_client()],
            ['How it works', 'how-it-works', self::how_it_works()],
            ['FAQs', 'faqs', self::faqs()],
            ['About Nest Assured', 'about', self::about()],
            ['Contact', 'contact', self::contact()],
            ['Enquire', 'enquire', self::enquire()],
        ];

        $ids = [];
        foreach ($pages as [$title, $slug, $content]) {
            $ids[$slug] = self::upsert_page($title, $slug, $content);
        }

        $guides_id = (int) ($ids['guides'] ?? 0);
        $guide_pages = [
        ];

        $guide_pages = array_merge($guide_pages, NA_Guides_Expanded::guides(), NA_Guides_Library::guides());

        foreach ($guide_pages as [$title, $slug, $content]) {
            $ids[$slug] = self::upsert_page($title, $slug, $content, $guides_id);
        }

        $ids['legal'] = $legal_id;
        $ids['privacy'] = self::upsert_privacy_page($legal_id);
        $ids['complaints-procedure'] = self::upsert_page('Complaints procedure', 'complaints-procedure', self::complaints(), $legal_id);
        $ids['financial-promotions'] = self::upsert_page('Financial promotions', 'financial-promotions', self::financial_promotions(), $legal_id);

        if (! empty($ids['home'])) {
            update_option('show_on_front', 'page');
            update_option('page_on_front', (int) $ids['home']);
            update_option('page_for_posts', 0);
        }

        self::configure_page_seo($ids);

        update_option('wp_page_for_privacy_policy', (int) $ids['privacy']);
        update_option('blogname', 'Nest Assured');
        update_option('blogdescription', 'Protection insurance, explained clearly');
        update_option('permalink_structure', '/%postname%/');
        update_option('default_comment_status', 'closed');
        update_option('default_ping_status', 'closed');
        update_option('timezone_string', 'Europe/London');
        update_option('WPLANG', 'en_GB');

        self::configure_yoast();
        self::configure_backups();

        self::remove_starter_content();
        flush_rewrite_rules();

        // Stamped last. Written mid-run, any fatal before this point left the build
        // marked as complete, or re-ran the whole installer on every request forever.
        update_option('na_site_build_version', NA_CORE_VERSION);
    }

    private static function upsert_page(string $title, string $slug, string $content, int $parent = 0): int
    {
        $path = $parent > 0 ? get_post_field('post_name', $parent) . '/' . $slug : $slug;
        $existing = get_page_by_path($path, OBJECT, 'page');

        // Skip untouched pages so the post modified date, surfaced publicly as
        // "Last reviewed", only moves when the content genuinely changes.
        //
        // The live content is compared as well as the generated content. Comparing
        // only the generated hash meant a page edited in wp-admin was never detected,
        // so an edit that introduced unapproved wording survived every later deploy.
        $hash = md5($title . '|' . $slug . '|' . $parent . '|' . $content);
        if ($existing instanceof WP_Post
            && $hash === (string) get_post_meta($existing->ID, '_na_content_hash', true)
            && $content === $existing->post_content
        ) {
            return (int) $existing->ID;
        }

        // Record any divergence so overwritten edits are visible rather than silent.
        if ($existing instanceof WP_Post && $content !== $existing->post_content
            && $hash === (string) get_post_meta($existing->ID, '_na_content_hash', true)
        ) {
            $overwritten = get_option('na_overwritten_pages', []);
            $overwritten = is_array($overwritten) ? $overwritten : [];
            $overwritten[$slug] = gmdate('c');
            update_option('na_overwritten_pages', $overwritten, false);
        }

        $post = [
            'post_type'      => 'page',
            'post_status'    => 'publish',
            'post_title'     => $title,
            'post_name'      => $slug,
            'post_content'   => $content,
            'post_parent'    => $parent,
            'comment_status' => 'closed',
            'ping_status'    => 'closed',
        ];

        if ($existing instanceof WP_Post) {
            $post['ID'] = $existing->ID;
            $result = wp_update_post($post, true);
        } else {
            $result = wp_insert_post($post, true);
        }

        if (is_wp_error($result)) {
            return 0;
        }

        update_post_meta((int) $result, '_na_content_hash', $hash);
        return (int) $result;
    }

    private static function upsert_privacy_page(int $legal_id): int
    {
        $existing_id = (int) get_option('wp_page_for_privacy_policy', 0);
        $existing = $existing_id > 0 ? get_post($existing_id) : null;

        if (! $existing instanceof WP_Post || 'page' !== $existing->post_type) {
            return self::upsert_page('Privacy', 'privacy', self::privacy(), $legal_id);
        }

        $result = wp_update_post([
            'ID'             => $existing->ID,
            'post_title'     => 'Privacy',
            'post_name'      => 'privacy',
            'post_status'    => 'publish',
            'post_parent'    => $legal_id,
            'post_content'   => self::privacy(),
            'comment_status' => 'closed',
        ], true);

        return is_wp_error($result) ? 0 : (int) $result;
    }

    private static function remove_starter_content(): void
    {
        foreach (['hello-world', 'sample-page'] as $slug) {
            $post = get_page_by_path($slug, OBJECT, ['post', 'page']);
            if ($post instanceof WP_Post && 'trash' !== $post->post_status) {
                wp_trash_post($post->ID);
            }
        }
    }

    private static function configure_yoast(): void
    {
        $titles = get_option('wpseo_titles', []);
        if (is_array($titles) && [] !== $titles) {
            $logo_id = (int) get_option('site_icon', 0);
            $logo_url = $logo_id > 0 ? (string) wp_get_attachment_image_url($logo_id, 'full') : '';
            $titles['separator'] = 'sc-pipe';
            $titles['website_name'] = 'Nest Assured';
            $titles['company_name'] = 'Nest Assured';
            $titles['company_or_person'] = 'company';
            $titles['title-home-wpseo'] = '%%sitename%% %%sep%% %%sitedesc%%';
            $titles['metadesc-home-wpseo'] = 'Friendly, adviser-led guidance on life insurance, income protection, critical illness, private medical, business and home insurance.';
            $titles['open_graph_frontpage_title'] = 'Nest Assured | Insurance advice, made reassuringly clear';
            $titles['open_graph_frontpage_desc'] = $titles['metadesc-home-wpseo'];
            $titles['company_logo'] = $logo_url;
            $titles['company_logo_id'] = $logo_id;
            $titles['open_graph_frontpage_image'] = $logo_url;
            $titles['open_graph_frontpage_image_id'] = $logo_id;
            $titles['disable-author'] = true;
            $titles['disable-date'] = true;
            $titles['noindex-author-wpseo'] = true;
            update_option('wpseo_titles', $titles);
        }

        $general = get_option('wpseo', []);
        if (is_array($general) && [] !== $general) {
            $general['tracking'] = false;
            $general['semrush_integration_active'] = false;
            $general['wincher_integration_active'] = false;
            $general['enable_ai_generator'] = false;
            $general['remove_emoji_scripts'] = true;
            $general['remove_generator'] = true;
            $general['remove_pingback_header'] = true;
            update_option('wpseo', $general);
        }
    }

    /**
     * @param array<string, int> $ids Page IDs keyed by slug.
     */
    private static function configure_page_seo(array $ids): void
    {
        $descriptions = [
            'home'                   => 'Friendly, adviser-led guidance on life insurance, income protection, critical illness, private medical, business and home insurance.',
            'life-insurance'         => 'Understand what life insurance is designed to do, what it is not and what to prepare for an adviser conversation.',
            'income-protection'      => 'Understand how income protection works, the role of waiting periods and what an adviser needs to discuss.',
            'critical-illness-cover' => 'A plain-English guide to critical illness cover, policy definitions and the adviser conversation.',
            'family-protection'      => 'Family protection explained: how life insurance, income protection, critical illness cover and existing benefits can fit together for your household.',
            'private-medical-insurance' => 'Understand private medical insurance, hospital lists, excesses, underwriting and the questions worth discussing with an adviser.',
            'business-protection'    => 'A clear guide to key person cover, shareholder protection, business loan protection, relevant life and executive income protection.',
            'general-insurance'      => 'Understand buildings, contents and wider general insurance, including what to check when protecting your home and belongings.',
            'guides'                 => 'Plain-English insurance guides covering personal protection, private medical insurance, business protection and home insurance.',
            'editorial-policy'       => 'How Nest Assured writes, reviews, sources and corrects its plain-English insurance guides.',
            'life-insurance-vs-critical-illness-cover' => 'Compare life insurance and critical illness cover, including when each can pay, how benefits differ and why the two are not interchangeable.',
            'income-protection-and-sick-pay' => 'Learn how employer sick pay, savings and income protection can work together if illness or injury prevents you from working.',
            'choosing-private-medical-insurance' => 'A practical checklist for comparing private medical insurance, from underwriting and hospital access to excesses and outpatient cover.',
            'types-of-business-protection' => 'Understand the main types of business protection and the different roles of key person, shareholder, loan and relevant life cover.',
            'buildings-and-contents-insurance' => 'A plain-English explanation of buildings and contents insurance, common limits and the details to check before choosing cover.',
            'when-to-review-protection-insurance' => 'The life, work and mortgage changes that can make a protection insurance review worthwhile.',
            'making-a-protection-insurance-claim' => 'A practical first-step guide to making a life, critical illness, income protection or private medical insurance claim.',
            'insurance-jargon-buster' => 'Plain-English definitions for common protection, private medical and general insurance terms.',
            'income-protection-for-self-employed' => 'How self-employed people can assess income gaps, waiting periods and the evidence used for income protection.',
            'relevant-life-vs-key-person-cover' => 'Compare relevant life and key person cover, including who each arrangement is intended to protect and why.',
            'leaving-company-private-medical-insurance' => 'What to check when employer-funded private medical insurance ends, including continuation terms and underwriting.',
            'life-insurance-and-trusts' => 'A plain-English introduction to life insurance trusts, trustees, beneficiaries and the decisions that need care.',
            'preparing-for-protection-appointment' => 'A practical checklist of policies, workplace benefits, commitments and questions to prepare for a protection appointment.',
            'already-a-client'       => 'A dedicated protection enquiry route for existing Major Money Matters mortgage clients.',
            'how-it-works'           => 'How the Nest Assured advice-led protection process works from enquiry to adviser conversation.',
            'faqs'                   => 'Approved protection questions drawn from real Nest Assured adviser conversations.',
            'about'                  => 'Nest Assured is the protection advice service connected to mortgage broker Major Money Matters, built around a regulated adviser conversation.',
            'contact'                => 'Choose the correct Nest Assured contact route for an existing-client or new protection enquiry.',
            'enquire'                => 'Start an advice-led protection conversation through the correct existing-client or new-enquiry route.',
            'calculators'            => 'Two planning tools that work only with figures you already know: a cover gap estimator and an income timeline. No quotes, nothing stored.',
            'find-your-starting-point' => 'Three short questions to help work out which protection conversation is worth having. No prices, no recommendation, nothing stored.',
            'legal'                  => 'Legal, privacy and regulatory information for the Nest Assured protection advice service.',
            'privacy'                => 'How Nest Assured collects, uses and retains the information you send through an enquiry.',
            'complaints-procedure'   => 'How to raise a concern about Nest Assured and how a complaint will be handled.',
            'financial-promotions'   => 'The scope and status of the information published on the Nest Assured website.',
        ];

        // Both guide series carry their own descriptions.
        foreach (NA_Guides_Expanded::meta() as $guide_slug => $guide_meta) {
            $descriptions[$guide_slug] = $guide_meta['description'];
        }

        foreach (NA_Guides_Library::meta() as $guide_slug => $guide_meta) {
            $descriptions[$guide_slug] = $guide_meta['description'];
        }

        foreach ($descriptions as $slug => $description) {
            $page_id = (int) ($ids[$slug] ?? 0);
            if ($page_id <= 0) {
                continue;
            }

            update_post_meta($page_id, '_yoast_wpseo_metadesc', $description);
        }

        if (! empty($ids['home'])) {
            update_post_meta((int) $ids['home'], '_yoast_wpseo_title', 'Nest Assured | Insurance advice, made reassuringly clear');
        }

        $titles = [
            'life-insurance' => 'Life Insurance Advice | Nest Assured',
            'income-protection' => 'Income Protection Advice | Nest Assured',
            'critical-illness-cover' => 'Critical Illness Cover Advice | Nest Assured',
            'family-protection' => 'Family Protection Advice | Nest Assured',
            'private-medical-insurance' => 'Private Medical Insurance Advice | Nest Assured',
            'business-protection' => 'Business Protection Insurance Advice | Nest Assured',
            'general-insurance' => 'Home and General Insurance Advice | Nest Assured',
            'guides' => 'Protection and Insurance Guides | Nest Assured',
            'editorial-policy' => 'Editorial Policy | Nest Assured',
            'life-insurance-vs-critical-illness-cover' => 'Life Insurance vs Critical Illness Cover | Nest Assured',
            'income-protection-and-sick-pay' => 'Income Protection and Sick Pay Explained | Nest Assured',
            'choosing-private-medical-insurance' => 'How to Choose Private Medical Insurance | Nest Assured',
            'types-of-business-protection' => 'Types of Business Protection Explained | Nest Assured',
            'buildings-and-contents-insurance' => 'Buildings vs Contents Insurance Explained | Nest Assured',
            'when-to-review-protection-insurance' => 'When to Review Protection Insurance | Nest Assured',
            'making-a-protection-insurance-claim' => 'How to Make a Protection Insurance Claim | Nest Assured',
            'insurance-jargon-buster' => 'Insurance Jargon Buster | Nest Assured',
            'income-protection-for-self-employed' => 'Income Protection for Self-Employed People | Nest Assured',
            'relevant-life-vs-key-person-cover' => 'Relevant Life vs Key Person Cover | Nest Assured',
            'leaving-company-private-medical-insurance' => 'Leaving Company Private Medical Insurance | Nest Assured',
            'life-insurance-and-trusts' => 'Life Insurance and Trusts Explained | Nest Assured',
            'preparing-for-protection-appointment' => 'Protection Appointment Checklist | Nest Assured',
        ];

        foreach ($titles as $slug => $title) {
            if (! empty($ids[$slug])) {
                update_post_meta((int) $ids[$slug], '_yoast_wpseo_title', $title);
            }
        }

        foreach (['already-a-client', 'enquire'] as $slug) {
            if (! empty($ids[$slug])) {
                update_post_meta((int) $ids[$slug], '_yoast_wpseo_meta-robots-noindex', '1');
            }
        }

        if (! empty($ids['faqs'])) {
            if ('' === trim(NA_Settings::get('faqs_copy'))) {
                update_post_meta((int) $ids['faqs'], '_yoast_wpseo_meta-robots-noindex', '1');
            } else {
                delete_post_meta((int) $ids['faqs'], '_yoast_wpseo_meta-robots-noindex');
            }
        }

        $unapproved_legal = [
            'legal' => '' === trim(NA_Settings::get('complaints_copy')) || '' === trim(NA_Settings::get('financial_copy')),
            'privacy' => '' === trim(NA_Settings::get('privacy_copy')),
            'complaints-procedure' => '' === trim(NA_Settings::get('complaints_copy')),
            'financial-promotions' => '' === trim(NA_Settings::get('financial_copy')),
        ];

        foreach ($unapproved_legal as $slug => $should_noindex) {
            if (empty($ids[$slug])) {
                continue;
            }
            if ($should_noindex) {
                update_post_meta((int) $ids[$slug], '_yoast_wpseo_meta-robots-noindex', '1');
            } else {
                delete_post_meta((int) $ids[$slug], '_yoast_wpseo_meta-robots-noindex');
            }
        }
    }

    /**
     * Backup scheduling is deliberately NOT configured from code.
     *
     * Scheduling a daily database backup with no remote destination and no
     * encryption phrase wrote unencrypted SQL dumps containing every stored
     * enquiry, names, email addresses, phone numbers and mortgage references,
     * into wp-content/updraft/ inside the webroot. The directory ships .htaccess
     * and web.config, which protect nothing on Nginx, and both WordPress.com and
     * Pressable run Nginx.
     *
     * A backup schedule now has to be set up deliberately, with a destination and
     * an encryption phrase, rather than being switched on silently by a deploy.
     */
    private static function configure_backups(): void
    {
    }

    /**
     * Warn when a local, unencrypted backup schedule is active, since those archives
     * contain client personal data and sit inside the webroot.
     */
    public static function backup_notice(): void
    {
        if (! current_user_can('manage_options')) {
            return;
        }

        if (! defined('UPDRAFTPLUS_DIR') && ! is_dir(WP_PLUGIN_DIR . '/updraftplus')) {
            return;
        }

        $scheduled = 'manual' !== (string) get_option('updraft_interval_database', 'manual')
            && '' !== (string) get_option('updraft_interval_database', '');
        $encrypted = '' !== trim((string) get_option('updraft_encryptionphrase', ''));
        $remote = (array) get_option('updraft_service', []);
        $remote = array_filter($remote, static fn ($service): bool => '' !== (string) $service && 'none' !== (string) $service);

        if (! $scheduled || ($encrypted && [] !== $remote)) {
            return;
        }

        echo '<div class="notice notice-warning"><p><strong>Nest Assured:</strong> scheduled database backups are running without '
            . esc_html([] === $remote ? 'a remote destination' : 'an encryption phrase')
            . '. Backup archives contain client enquiry data and are written inside the website directory. '
            . 'Set an encryption phrase and a remote destination in UpdraftPlus, or turn the schedule off.</p></div>';
    }

    private static function html(string $content): string
    {
        return "<!-- wp:html -->\n" . trim($content) . "\n<!-- /wp:html -->";
    }

    private static function shortcode(string $shortcode): string
    {
        return "<!-- wp:shortcode -->\n" . $shortcode . "\n<!-- /wp:shortcode -->";
    }

    private static function home(): string
    {
        return self::html('
<div class="na-v2">
<section class="na-v2-hero">
  <div class="na-v2-shell na-v2-hero__grid">
    <div class="na-v2-hero__body">
      <p class="na-v2-eyebrow">Protection advice &middot; Harold Wood, Essex &middot; UK-wide</p>
      <h1>Know what your family needs. And what you don&rsquo;t.</h1>
      <p class="na-v2-lede">Life insurance, income protection and critical illness, explained by a named adviser who starts with the cover you already have. No online quotes. Nothing sold on this site.</p>
      <div class="na-v2-actions">
        <a class="na-v2-btn" href="/enquire/">Arrange a conversation</a>
        <a class="na-v2-link" href="/find-your-starting-point/">Find my starting point <span aria-hidden="true">&darr;</span></a>
      </div>')
            . self::shortcode('[nest_assured_adviser variant="pill"]')
            . self::html('</div>')
            . self::shortcode('[nest_assured_adviser variant="portrait"]')
            . self::html('</div>
</section>
</div>')
            . self::shortcode('[nest_assured_assurance]')
            . self::html('
<div class="na-v2">
<section class="na-v2-route" aria-label="Existing client route">
  <div class="na-v2-shell na-v2-route__inner">
    <p><strong>Already a Major Money Matters client?</strong> Continue on the adviser route you have already started. Your enquiry is kept separate from the new-enquiry queue.</p>
    <a class="na-v2-link" href="/already-a-client/">Continue with your adviser <span aria-hidden="true">&rarr;</span></a>
  </div>
</section>

<section class="na-v2-section na-v2-section--tight" id="starting-point">
  <div class="na-v2-shell na-v2-split">
    <div class="na-v2-panel na-v2-panel--paper">')
            . self::shortcode('[nest_assured_assessment]')
            . self::html('</div>
    <div class="na-v2-panel na-v2-panel--navy na-v2-timing">
      <p class="na-v2-eyebrow na-v2-eyebrow--light">Why timing matters</p>
      <h2>The same cover costs more the longer you wait.</h2>
      <p>Premiums are usually based on your age and health when you apply, so applying later generally costs more. Some policies have reviewable premiums that can change during the term. An adviser can explain how this applies to you.</p>
      <a class="na-v2-link na-v2-link--light" href="/enquire/">Ask about your timing <span aria-hidden="true">&rarr;</span></a>
    </div>
  </div>
</section>

<section class="na-v2-section" id="cover-options">
  <div class="na-v2-shell">
    <div class="na-v2-headrow">
      <h2>The cover, in plain English.</h2>
      <p class="na-v2-headrow__note">Seven conversations</p>
    </div>
    <div class="na-v2-index">
      <a class="na-v2-index__row" href="/life-insurance/"><span class="na-v2-index__num" aria-hidden="true">I.</span><span class="na-v2-index__text"><span class="na-v2-index__title">Life insurance</span><span class="na-v2-index__desc">A lump sum for the people who would need it</span></span><span class="na-v2-index__go" aria-hidden="true">&rarr;</span></a>
      <a class="na-v2-index__row" href="/income-protection/"><span class="na-v2-index__num" aria-hidden="true">II.</span><span class="na-v2-index__text"><span class="na-v2-index__title">Income protection</span><span class="na-v2-index__desc">A monthly income if illness stops you working</span></span><span class="na-v2-index__go" aria-hidden="true">&rarr;</span></a>
      <a class="na-v2-index__row" href="/critical-illness-cover/"><span class="na-v2-index__num" aria-hidden="true">III.</span><span class="na-v2-index__text"><span class="na-v2-index__title">Critical illness cover</span><span class="na-v2-index__desc">A lump sum after a covered diagnosis</span></span><span class="na-v2-index__go" aria-hidden="true">&rarr;</span></a>
      <a class="na-v2-index__row" href="/private-medical-insurance/"><span class="na-v2-index__num" aria-hidden="true">IV.</span><span class="na-v2-index__text"><span class="na-v2-index__title">Private medical insurance</span><span class="na-v2-index__desc">Eligible private diagnosis and treatment</span></span><span class="na-v2-index__go" aria-hidden="true">&rarr;</span></a>
      <a class="na-v2-index__row" href="/business-protection/"><span class="na-v2-index__num" aria-hidden="true">V.</span><span class="na-v2-index__text"><span class="na-v2-index__title">Business protection</span><span class="na-v2-index__desc">Key person, shareholder and loan cover</span></span><span class="na-v2-index__go" aria-hidden="true">&rarr;</span></a>
      <a class="na-v2-index__row" href="/general-insurance/"><span class="na-v2-index__num" aria-hidden="true">VI.</span><span class="na-v2-index__text"><span class="na-v2-index__title">Home and general insurance</span><span class="na-v2-index__desc">Buildings, contents and valuables</span></span><span class="na-v2-index__go" aria-hidden="true">&rarr;</span></a>
      <a class="na-v2-index__row" href="/family-protection/"><span class="na-v2-index__num" aria-hidden="true">VII.</span><span class="na-v2-index__text"><span class="na-v2-index__title">Family protection</span><span class="na-v2-index__desc">The joined-up household conversation</span></span><span class="na-v2-index__go" aria-hidden="true">&rarr;</span></a>
    </div>
  </div>
</section>

<section class="na-v2-section na-v2-section--paper" id="how-it-works">
  <div class="na-v2-shell na-v2-steps">
    <div class="na-v2-steps__intro">
      <p class="na-v2-eyebrow">How it works</p>
      <h2>A conversation before any recommendation.</h2>
      <p>An adviser first learns what you already have, what matters to you and what you can reasonably maintain. Only then are products or policy terms discussed.</p>
    </div>
    <div class="na-v2-steps__list">
      <div class="na-v2-step"><span class="na-v2-step__num" aria-hidden="true">01</span><div><strong>Tell us where you are</strong><span>Existing <a href="/already-a-client/">Major Money Matters clients</a> follow their adviser route; new enquiries go straight to Ollie.</span></div></div>
      <div class="na-v2-step"><span class="na-v2-step__num" aria-hidden="true">02</span><div><strong>Talk it through</strong><span>Thirty minutes on your circumstances, existing cover and questions, in person, by phone or by video.</span></div></div>
      <div class="na-v2-step"><span class="na-v2-step__num" aria-hidden="true">03</span><div><strong>Decide in your own time</strong><span>Any recommendation arrives in writing, in plain English, before you commit to anything.</span></div></div>
    </div>
  </div>
</section>

<section class="na-v2-section">
  <div class="na-v2-shell">
    <div class="na-v2-headrow">
      <h2>Guides worth reading before any call.</h2>
      <a class="na-v2-link" href="/guides/">All guides <span aria-hidden="true">&rarr;</span></a>
    </div>
    <div class="na-v2-guides">
      <a class="na-v2-guide" href="/guides/life-insurance-vs-critical-illness-cover/">
        <p class="na-v2-eyebrow">Compare cover</p>
        <h3>Life insurance or critical illness cover?</h3>
        <p class="na-v2-guide__desc">What triggers each policy, and why they are not interchangeable.</p>
              </a>
      <a class="na-v2-guide" href="/guides/income-protection-and-sick-pay/">
        <p class="na-v2-eyebrow">Protecting income</p>
        <h3>How sick pay fits with income protection</h3>
        <p class="na-v2-guide__desc">Map employer benefits and savings before comparing cover.</p>
              </a>
      <a class="na-v2-guide" href="/guides/when-to-review-protection-insurance/">
        <p class="na-v2-eyebrow">Keeping cover relevant</p>
        <h3>When is a protection review worthwhile?</h3>
        <p class="na-v2-guide__desc">The life, work and mortgage changes that justify another look.</p>
              </a>
    </div>
  </div>
</section>

<section class="na-v2-close">
  <div class="na-v2-shell na-v2-close__grid">')
            . self::shortcode('[nest_assured_social_proof]')
            . self::html('<div class="na-v2-close__cta">
      <h2>Harold Wood, Romford and Essex, or anywhere by phone.</h2>
      <p>Thirty minutes. Your existing cover, your commitments, your budget. Only then, advice.</p>
      <a class="na-v2-btn na-v2-btn--gold" href="/enquire/">Arrange a conversation</a>
    </div>
  </div>
</section>
</div>');
    }

    private static function life_insurance(): string
    {
        return self::html('
<div class="na-v2">
<section class="na-v2-masthead">
  <div class="na-v2-shell na-v2-masthead__grid">
    <div>
      <p class="na-v2-eyebrow">Cover I &middot; Plain-English guide</p>
      <h1>Life insurance</h1>
      <p class="na-v2-lede na-v2-lede--wide">Designed to pay a lump sum if the insured person dies during the policy term, provided the policy terms and claim conditions are met. Here is what it is, what it is not, and what an adviser needs to understand.</p>
    </div>
    <div class="na-v2-masthead__aside">
      <a class="na-v2-btn" href="/enquire/?topic=life-insurance">Discuss life insurance</a>
      <span class="na-v2-note">No quotes on this site &middot; advice first</span>
    </div>
  </div>
  <figure class="na-v2-masthead__figure"><img src="/wp-content/themes/nest-assured/assets/images/editorial/hallway.webp" srcset="/wp-content/themes/nest-assured/assets/images/editorial/hallway-800.webp 800w, /wp-content/themes/nest-assured/assets/images/editorial/hallway.webp 1600w" sizes="100vw" width="1600" height="900" alt="" loading="lazy" decoding="async" /></figure>
</section>

<section class="na-v2-section na-v2-section--short">
  <div class="na-v2-shell">
    <h2>Common ways cover can be shaped</h2>
    <p class="na-v2-subhead">The purpose of the money is agreed by the people arranging the cover, alongside a mortgage, shared commitments or support for dependants.</p>
    <div class="na-v2-shapes">
      <div class="na-v2-shape"><span class="na-v2-shape__key" aria-hidden="true">a.</span><h3>Level or increasing</h3><p>The benefit stays fixed or can rise over time, depending on the option and policy terms.</p></div>
      <div class="na-v2-shape"><span class="na-v2-shape__key" aria-hidden="true">b.</span><h3>Decreasing</h3><p>The benefit reduces over the term, often considered alongside a repayment mortgage.</p></div>
      <div class="na-v2-shape"><span class="na-v2-shape__key" aria-hidden="true">c.</span><h3>Single or joint life</h3><p>One person or two under one arrangement, with different consequences after a claim.</p></div>
      <div class="na-v2-shape"><span class="na-v2-shape__key" aria-hidden="true">d.</span><h3>Term or whole of life</h3><p>Term cover lasts an agreed period; whole-of-life continues while required premiums are maintained.</p></div>
    </div>
  </div>
</section>

<section class="na-v2-section na-v2-section--paper na-v2-section--short">
  <div class="na-v2-shell na-v2-twocol">
    <div>
      <h2 class="na-v2-h2--small">What it is not</h2>
      <ul class="na-v2-list na-v2-list--no">
        <li>It is not a savings account.</li>
        <li>It does not normally pay simply because the policy reaches the end of its term.</li>
        <li>It is not the same as critical illness cover, which responds to specified diagnoses rather than death.</li>
        <li>A website description cannot establish whether a policy is suitable for you.</li>
      </ul>
    </div>
    <div>
      <h2 class="na-v2-h2--small">What an adviser needs to understand</h2>
      <ul class="na-v2-list na-v2-list--yes">
        <li>Who relies on your income or shares financial commitments with you</li>
        <li>Any cover you already hold personally or through work</li>
        <li>The amount and length of any mortgage or other commitments</li>
        <li>Your budget and how it may change over time</li>
      </ul>
    </div>
  </div>
</section>

<section class="na-v2-section na-v2-section--short">
  <div class="na-v2-shell na-v2-mis">
    <div>
      <p class="na-v2-eyebrow">A common misunderstanding</p>
      <p class="na-v2-quote">&ldquo;Life cover through work means I do not need to discuss anything else.&rdquo;</p>
      <p class="na-v2-subhead">Workplace benefits can be valuable, but the amount, conditions and connection to your employment need to be understood before drawing that conclusion.</p>
    </div>
    <a class="na-v2-guide" href="/guides/life-insurance-vs-critical-illness-cover/">
      <p class="na-v2-eyebrow">Compare the options</p>
      <h3>Life insurance or critical illness cover?</h3>
      <p class="na-v2-guide__desc">See how the claim triggers, benefits and purposes differ.</p>
      <span class="na-v2-guide__more">Read the comparison <span aria-hidden="true">&rarr;</span></span>
    </a>
  </div>
</section>

<section class="na-v2-section na-v2-section--short">
  <div class="na-v2-shell">
    <p class="na-v2-note na-v2-note--block">This guide is general information. It is not a personal recommendation and does not describe every policy condition or exclusion.</p>
    <div class="na-v2-callout">
      <div>
        <h2>Discuss life insurance with an adviser</h2>
        <p>Start with your existing arrangements and the people or commitments you want the conversation to consider.</p>
      </div>
      <a class="na-v2-btn na-v2-btn--gold" href="/enquire/?topic=life-insurance">Talk to an adviser</a>
    </div>
  </div>
</section>')
            . self::faq_block(NA_Faq::for_slug('life-insurance'))
            . self::html('</div>');
    }

    /**
     * Shared renderer for the cover pages.
     *
     * Every cover page used to carry its own hand-written markup, which is how six
     * of the seven ended up on the old layout while the seventh was redesigned, so
     * a visitor moving between two products crossed a visible change of design.
     * The approved prose is unchanged; only the structure around it is shared.
     *
     * @param array<string, mixed> $spec Page content.
     */
    /**
     * Masthead photography. Decorative: the alt text is empty because the image
     * repeats nothing the heading does not already say, and a description would
     * only add noise for anyone using a screen reader.
     */
    private static function masthead_image(string $name, string $alt = ''): string
    {
        $base = get_theme_file_uri('assets/images/editorial/');

        return '<figure class="na-v2-masthead__figure">'
            . '<img src="' . esc_url($base . $name . '.webp') . '" '
            . 'srcset="' . esc_url($base . $name . '-800.webp') . ' 800w, ' . esc_url($base . $name . '.webp') . ' 1600w" '
            . 'sizes="100vw" width="1600" height="900" alt="' . esc_attr($alt) . '" loading="lazy" decoding="async" />'
            . '</figure>';
    }

    /**
     * Guides relevant to a cover page. Each cover page linked to exactly one guide
     * while thirty-three existed, so the library was doing almost nothing for the
     * pages that most need to send readers into it.
     *
     * @param array<int, string> $slugs Guide slugs, in the order to show them.
     */
    /**
     * A question block, rendered visibly and marked up as FAQPage.
     *
     * Every serious competitor in this market carries FAQ markup and this site
     * carried none. The questions are the ones people actually type, and the
     * answers stay inside the same rules as the guides: no figures, no
     * recommendation, and a route to an adviser rather than an assertion.
     *
     * @param array<int, array{0:string,1:string}> $pairs Question and answer.
     */
    private static function faq_block(array $pairs): string
    {
        if ([] === $pairs) {
            return '';
        }

        $items = '';
        $schema = [];

        foreach ($pairs as [$question, $answer]) {
            $items .= '<div class="na-v2-faq__item"><h3>' . esc_html($question) . '</h3>'
                . '<p>' . esc_html($answer) . '</p></div>';

            $schema[] = [
                '@type' => 'Question',
                'name'  => $question,
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $answer],
            ];
        }

        unset($schema);

        // The markup is emitted from wp_head, not from here. A script tag stored in
        // post content is stripped by kses on save and its JSON then renders as
        // visible text on the page, which is worse than having no markup at all.
        return '<section class="na-v2-section na-v2-section--short"><div class="na-v2-shell">'
            . '<div class="na-v2-headrow"><h2>Common questions</h2></div>'
            . '<div class="na-v2-faq">' . $items . '</div>'
            . '</div></section>';
    }

    private static function guide_rail(string $heading, array $slugs): string
    {
        if (! class_exists('NA_Guides_Library')) {
            return '';
        }

        $catalogue = NA_Guides_Library::catalogue();
        $cards = '';

        foreach ($slugs as $slug) {
            if (! isset($catalogue[$slug])) {
                continue;
            }

            $cards .= '<a class="na-v2-guide" href="' . esc_url(home_url('/guides/' . $slug . '/')) . '">'
                . '<p class="na-v2-eyebrow">' . esc_html($catalogue[$slug]['eyebrow']) . '</p>'
                . '<h3>' . esc_html($catalogue[$slug]['title']) . '</h3></a>';
        }

        if ('' === $cards) {
            return '';
        }

        return '<section class="na-v2-section na-v2-section--short"><div class="na-v2-shell">'
            . '<div class="na-v2-headrow"><h2>' . esc_html($heading) . '</h2>'
            . '<a class="na-v2-link" href="' . esc_url(home_url('/guides/')) . '">All guides <span aria-hidden="true">&rarr;</span></a></div>'
            . '<div class="na-v2-guides">' . $cards . '</div></div></section>';
    }

    private static function cover_page(array $spec): string
    {
        $topic = (string) $spec['topic'];
        $out = '<div class="na-v2">';

        $out .= '<section class="na-v2-masthead"><div class="na-v2-shell na-v2-masthead__grid"><div>'
            . '<p class="na-v2-eyebrow">' . $spec['eyebrow'] . '</p>'
            . '<h1>' . $spec['title'] . '</h1>'
            . '<p class="na-v2-lede na-v2-lede--wide">' . $spec['lede'] . '</p>'
            . '</div><div class="na-v2-masthead__aside">'
            . '<a class="na-v2-btn" href="/enquire/?topic=' . $topic . '">' . $spec['cta_short'] . '</a>'
            . '<span class="na-v2-note">No quotes on this site &middot; advice first</span>'
            . '</div></div>'
            . (isset($spec['image']) ? self::masthead_image($spec['image'], $spec['image_alt']) : '')
            . '</section>';

        if (isset($spec['intro'])) {
            $out .= '<section class="na-v2-section na-v2-section--short"><div class="na-v2-shell">'
                . '<h2>' . $spec['intro']['h'] . '</h2>'
                . '<p class="na-v2-subhead">' . $spec['intro']['p'] . '</p>'
                . '</div></section>';
        }

        if (isset($spec['shapes'])) {
            $keys = ['a.', 'b.', 'c.', 'd.', 'e.'];
            $items = '';
            foreach (array_values($spec['shapes']) as $i => $shape) {
                $items .= '<div class="na-v2-shape"><span class="na-v2-shape__key" aria-hidden="true">'
                    . ($keys[$i] ?? '') . '</span><h3>' . $shape['h'] . '</h3><p>' . $shape['p'] . '</p></div>';
            }

            $out .= '<section class="na-v2-section na-v2-section--short"><div class="na-v2-shell">'
                . '<h2>' . $spec['shapes_heading'] . '</h2>'
                . (isset($spec['shapes_note']) ? '<p class="na-v2-subhead">' . $spec['shapes_note'] . '</p>' : '')
                . '<div class="na-v2-shapes">' . $items . '</div></div></section>';
        }

        if (isset($spec['twocol'])) {
            $columns = '';
            foreach ($spec['twocol'] as $column) {
                $list = '';
                foreach ($column['items'] as $item) {
                    $list .= '<li>' . $item . '</li>';
                }
                $columns .= '<div><h2 class="na-v2-h2--small">' . $column['h'] . '</h2>'
                    . '<ul class="na-v2-list na-v2-list--' . $column['tone'] . '">' . $list . '</ul></div>';
            }

            $out .= '<section class="na-v2-section na-v2-section--paper na-v2-section--short">'
                . '<div class="na-v2-shell na-v2-twocol">' . $columns . '</div></section>';
        }

        if (isset($spec['checklist'])) {
            $list = '';
            foreach ($spec['checklist']['items'] as $item) {
                $list .= '<li>' . $item . '</li>';
            }

            $out .= '<section class="na-v2-section na-v2-section--short"><div class="na-v2-shell">'
                . '<h2 class="na-v2-h2--small">' . $spec['checklist']['h'] . '</h2>'
                . '<ul class="na-v2-list na-v2-list--yes">' . $list . '</ul>'
                . (isset($spec['checklist']['note']) ? '<p class="na-v2-subhead">' . $spec['checklist']['note'] . '</p>' : '')
                . '</div></section>';
        }

        if (isset($spec['mis'])) {
            $guide = '';
            if (isset($spec['guide'])) {
                $guide = '<a class="na-v2-guide" href="' . $spec['guide']['href'] . '">'
                    . '<p class="na-v2-eyebrow">' . $spec['guide']['eyebrow'] . '</p>'
                    . '<h3>' . $spec['guide']['h'] . '</h3>'
                    . '<p class="na-v2-guide__desc">' . $spec['guide']['p'] . '</p>'
                    . '<span class="na-v2-guide__more">' . $spec['guide']['more'] . ' <span aria-hidden="true">&rarr;</span></span></a>';
            }

            $out .= '<section class="na-v2-section na-v2-section--short"><div class="na-v2-shell na-v2-mis"><div>'
                . '<p class="na-v2-eyebrow">A common misunderstanding</p>'
                . '<p class="na-v2-quote">&ldquo;' . $spec['mis']['quote'] . '&rdquo;</p>'
                . '<p class="na-v2-subhead">' . $spec['mis']['p'] . '</p>'
                . '</div>' . $guide . '</div></section>';
        }

        if (isset($spec['guides'])) {
            $out .= self::guide_rail($spec['guides_heading'] ?? 'Guides on this subject', $spec['guides']);
        }

        if (isset($spec['faqs'])) {
            $out .= self::faq_block($spec['faqs']);
        }

        $out .= '<section class="na-v2-section na-v2-section--short"><div class="na-v2-shell">'
            . '<p class="na-v2-note na-v2-note--block">' . $spec['disclaimer'] . '</p>'
            . '<div class="na-v2-callout"><div><h2>' . $spec['cta']['h'] . '</h2><p>' . $spec['cta']['p'] . '</p></div>'
            . '<a class="na-v2-btn na-v2-btn--gold" href="/enquire/?topic=' . $topic . '">Talk to an adviser</a>'
            . '</div></div></section>';

        return self::html($out . '</div>');
    }

    private static function income_protection(): string
    {
        return self::cover_page([
            'topic'     => 'income-protection',
            'faqs'      => [
                ['How long does income protection pay out for?', 'It depends on the policy. Some plans limit each claim to a set period, while others can continue until the end of the policy term if you remain unable to work under its definition. The maximum payment period is one of the main things that separates otherwise similar plans.'],
                ['Is income protection the same as payment protection insurance?', 'No. Income protection is designed to replace part of your earnings when illness or injury stops you working. Payment protection was sold to cover a specific debt or credit agreement, and works differently.'],
                ['Can I get income protection if I am self-employed?', 'Self-employed people can generally be considered, though insurers assess earnings differently and usually want to see accounts or tax calculations. There is no employer sick pay behind you, which is often why the conversation matters more.'],
                ['What is a deferred period?', 'The waiting time between becoming unable to work and the policy starting to pay. A longer deferred period usually costs less, so it is normally set to line up with whatever sick pay and savings would carry you until then.'],
                ['Will income protection affect my state benefits?', 'It can. Some benefits are means tested, so an insurance payment may affect entitlement. The interaction depends on your circumstances and on current rules, so check your own position rather than assuming.'],
                ['What happens if I change job while covered?', 'A personal policy belongs to you rather than your employer, so it normally continues. Changing occupation can matter, because some definitions and premiums are tied to the work you do, so tell the insurer.'],
            ],
            'guides_heading' => 'Guides on protecting income',
            'guides'    => ['how-much-income-protection-can-i-get', 'own-occupation-vs-any-occupation', 'income-protection-and-sick-pay', 'protection-when-self-employed', 'income-protection-for-self-employed', 'waiver-of-premium-explained'],
            'image'     => 'empty-desk',
            'image_alt' => '',
            'eyebrow'   => 'Cover II &middot; Plain-English guide',
            'title'     => 'Income protection',
            'lede'      => 'Income protection is designed to pay a regular income if illness or injury prevents the insured person working, subject to the policy definition and terms.',
            'cta_short' => 'Discuss income protection',
            'intro'     => [
                'h' => 'What it is',
                'p' => 'The policy normally replaces part of earnings after an agreed waiting period. The amount, waiting period, definition of incapacity and maximum payment period are important parts of the cover.',
            ],
            'shapes_heading' => 'Four details that make a material difference',
            'shapes'    => [
                ['h' => 'Incapacity definition', 'p' => 'The policy wording determines how inability to work is assessed.'],
                ['h' => 'Waiting period', 'p' => 'The period before an eligible claim can begin paying should be considered alongside sick pay and savings.'],
                ['h' => 'Payment period', 'p' => 'Some plans limit how long each claim can pay, while others can continue until the end of the policy term, subject to its conditions.'],
                ['h' => 'Premium basis', 'p' => 'Premiums may be guaranteed, reviewable or age-related depending on the product.'],
            ],
            'twocol'    => [
                ['h' => 'What it is not', 'tone' => 'no', 'items' => [
                    'It is not unemployment cover.',
                    'It does not replace every pound of earnings.',
                    'It is not the same as critical illness cover, which usually pays a lump sum for specified conditions.',
                    'It does not remove the need to understand sick pay and workplace benefits.',
                ]],
                ['h' => 'What an adviser needs to understand', 'tone' => 'yes', 'items' => [
                    'Your employment status, occupation and income',
                    'Employer sick pay and any existing protection',
                    'How long savings could support essential outgoings',
                    'Which policy definition and waiting period may be relevant',
                ]],
            ],
            'mis'       => [
                'quote' => 'I can only claim if I am permanently unable to work.',
                'p'     => 'Policies differ. Some are designed around a temporary inability to work, but the exact definition, waiting period and claim requirements must be checked.',
            ],
            'guide'     => [
                'href'    => '/guides/income-protection-and-sick-pay/',
                'eyebrow' => 'Build the timeline',
                'h'       => 'How does income protection fit with sick pay?',
                'p'       => 'Map employer support, savings and a potential policy waiting period.',
                'more'    => 'Read the guide',
            ],
            'disclaimer' => 'This guide is general information. It is not a personal recommendation and does not describe every policy condition or exclusion.',
            'cta'       => [
                'h' => 'Discuss income protection with an adviser',
                'p' => 'Bring any information you have about sick pay, savings and existing workplace benefits.',
            ],
        ]);
    }

    private static function critical_illness(): string
    {
        return self::cover_page([
            'topic'     => 'critical-illness',
            'faqs'      => [
                ['What conditions does critical illness cover include?', 'Each policy lists the conditions it covers and the definition a diagnosis has to meet. The number of listed conditions on its own tells you very little, because the definitions decide whether a claim is paid.'],
                ['Does critical illness cover pay out for any cancer?', 'No. Policies define which cancers are covered and at what severity, and some earlier-stage or less advanced cancers pay a smaller amount or nothing. The wording governs, not the diagnosis alone.'],
                ['What is a survival period?', 'A short period after diagnosis that you have to survive before a claim can be paid. It is set by the policy and is one of the terms worth checking before comparing plans on price.'],
                ['Can I add children\'s cover?', 'Many policies include or offer cover for children, but the conditions, limits and age ranges vary widely, and it is not available on every plan. Check what is included rather than assuming.'],
                ['Is critical illness cover the same as life insurance?', 'No. Life cover pays on death. Critical illness cover pays on diagnosis of a condition the policy covers, while you are alive. Some people hold both, sometimes on the same policy.'],
                ['Do I still need it if I have cover through work?', 'Workplace benefits can be valuable, but the amount, the conditions and the fact they usually end when the job does all need understanding before you decide the question is settled.'],
            ],
            'guides_heading' => 'Guides on critical illness',
            'guides'    => ['life-insurance-vs-critical-illness-cover', 'critical-illness-cover-for-children', 'life-insurance-pre-existing-conditions', 'life-insurance-medical-underwriting', 'waiver-of-premium-explained', 'when-to-review-protection-insurance'],
            'image'     => 'quiet-room',
            'image_alt' => '',
            'eyebrow'   => 'Cover III &middot; Plain-English guide',
            'title'     => 'Critical illness cover',
            'lede'      => 'Critical illness cover is designed to pay a lump sum if the insured person is diagnosed with a condition covered by the policy and meets its definition.',
            'cta_short' => 'Discuss critical illness cover',
            'intro'     => [
                'h' => 'What it is',
                'p' => 'The policy lists the conditions it covers and the definition that a diagnosis must meet. The payment may be considered for financial commitments, changes at home or time away from work, but how it is used is the policyholder&rsquo;s decision.',
            ],
            'shapes_heading' => 'What deserves attention in a comparison',
            'shapes'    => [
                ['h' => 'Definitions, not just counts', 'p' => 'The number of listed conditions does not show how each definition works or how relevant it is to you.'],
                ['h' => 'Full and additional payments', 'p' => 'Some policies include lower payments for specified less-severe conditions without ending all cover.'],
                ['h' => 'Children&rsquo;s cover', 'p' => 'Availability, limits and definitions vary and may be included or optional.'],
                ['h' => 'Extra support', 'p' => 'Some policies include services such as remote GP access, counselling or rehabilitation support.'],
            ],
            'twocol'    => [
                ['h' => 'What it is not', 'tone' => 'no', 'items' => [
                    'It does not cover every illness.',
                    'A diagnosis does not automatically qualify unless the policy definition is met.',
                    'It is not private medical insurance and does not pay for treatment directly.',
                    'It is not a substitute for income protection.',
                ]],
                ['h' => 'What an adviser needs to understand', 'tone' => 'yes', 'items' => [
                    'Your existing cover and workplace benefits',
                    'The financial commitments a lump sum might need to address',
                    'Whether children&rsquo;s cover or other policy features are relevant',
                    'Your budget and the policy definitions being compared',
                ]],
            ],
            'mis'       => [
                'quote' => 'All providers cover exactly the same illnesses.',
                'p'     => 'Policy lists and definitions vary. The number of listed conditions alone does not explain the quality or relevance of cover.',
            ],
            'guide'     => [
                'href'    => '/guides/life-insurance-vs-critical-illness-cover/',
                'eyebrow' => 'Related guide',
                'h'       => 'Life insurance or critical illness cover?',
                'p'       => 'Understand why two lump-sum policies can still serve different purposes.',
                'more'    => 'Read the comparison',
            ],
            'disclaimer' => 'This guide is general information. It is not a personal recommendation and does not describe every policy condition or exclusion.',
            'cta'       => [
                'h' => 'Discuss critical illness cover with an adviser',
                'p' => 'An adviser can explain policy definitions and how a lump-sum benefit differs from other protection.',
            ],
        ]);
    }

    private static function family_protection(): string
    {
        return self::cover_page([
            'topic'     => 'family-protection',
            'faqs'      => [
                ['How much life insurance does a family need?', 'There is no universal figure. The useful approach is to work out what would need paying off, what income would need replacing and for how long, then subtract the cover you already hold. The calculators on this site do that arithmetic.'],
                ['Should cover be joint or two single policies?', 'They behave differently. A joint policy pays once and ends, leaving the survivor without cover. Two single policies each pay separately. Which suits a household depends on circumstances, and it is a conversation rather than a rule.'],
                ['What happens to cover if we separate?', 'A joint policy cannot simply be split in two. Some plans include a separation option allowing two single policies without further medical evidence, but it is not universal and it has to be checked in the wording.'],
                ['Do stay-at-home parents need cover?', 'The question is what it would cost to replace what they do. Childcare, care for a relative and running a household all have a real cost if they have to be bought in, so a parent with no salary can still leave a substantial gap.'],
                ['Does life insurance pay out for any cause of death?', 'Most policies cover death from any cause once in force, subject to their terms. There are usually exclusions, commonly around suicide in the early period, and answering the application questions accurately matters at claim.'],
                ['What is family income benefit?', 'Cover that pays a regular income for the rest of the policy term rather than a single lump sum. Some households find a monthly figure easier to plan around than a large one-off amount.'],
            ],
            'guides_heading' => 'Guides for families',
            'guides'    => ['how-much-life-insurance-do-i-need', 'family-income-benefit-explained', 'joint-vs-single-life-insurance', 'critical-illness-cover-for-children', 'when-to-review-protection-insurance', 'writing-life-insurance-in-trust'],
            'image'     => 'kitchen-table',
            'image_alt' => '',
            'eyebrow'   => 'Cover IV &middot; A broader conversation',
            'title'     => 'Family protection',
            'lede'      => 'Family protection is not one policy type. It is a way to consider how life insurance, income protection, critical illness cover and existing benefits may fit together.',
            'cta_short' => 'Start the conversation',
            'intro'     => [
                'h' => 'What the conversation covers',
                'p' => 'An adviser can help map the people and commitments that depend on household income, then review which risks are already covered and which questions remain unanswered.',
            ],
            'shapes_heading' => 'Four things worth mapping first',
            'shapes'    => [
                ['h' => 'People', 'p' => 'Who relies on income, care or other contributions from the household?'],
                ['h' => 'Commitments', 'p' => 'Which mortgages, debts and regular costs would still need to be met?'],
                ['h' => 'Existing support', 'p' => 'What policies, savings and workplace benefits are already available?'],
                ['h' => 'Priorities', 'p' => 'Which risks matter most and what premium could remain comfortable?'],
            ],
            'twocol'    => [
                ['h' => 'What it is not', 'tone' => 'no', 'items' => [
                    'It is not a pre-packaged bundle.',
                    'It does not mean every household needs every type of policy.',
                    'It is not an online comparison or instant quote.',
                    'It does not replace a review of trusts, nominations or other arrangements where relevant.',
                ]],
                ['h' => 'Useful information to gather', 'tone' => 'yes', 'items' => [
                    'Mortgage and other shared financial commitments',
                    'Monthly essential household costs',
                    'Existing personal policies and workplace benefits',
                    'Savings and the length of time they could support the household',
                ]],
            ],
            'mis'       => [
                'quote' => 'One policy should cover every situation.',
                'p'     => 'Different policies are designed to respond to different events. The advice process is used to decide which subjects are relevant and which are not.',
            ],
            'guide'     => [
                'href'    => '/guides/when-to-review-protection-insurance/',
                'eyebrow' => 'Keep cover relevant',
                'h'       => 'When should you review protection insurance?',
                'p'       => 'Use a practical checklist of life, work and mortgage changes.',
                'more'    => 'Read the guide',
            ],
            'disclaimer' => 'This guide is general information. It is not a personal recommendation and does not describe every policy condition or exclusion.',
            'cta'       => [
                'h' => 'Start a family protection conversation',
                'p' => 'Begin with the commitments, people and existing cover you want the adviser to understand.',
            ],
        ]);
    }

    private static function private_medical_insurance(): string
    {
        return self::cover_page([
            'topic'     => 'private-medical-insurance',
            'faqs'      => [
                ['Does private medical insurance replace the NHS?', 'No. Having a policy does not remove your right to NHS care. It is designed to provide another route for eligible treatment, and what it covers depends on the plan.'],
                ['Are pre-existing conditions covered?', 'Usually not, at least at outset. How they are handled depends on whether the policy is fully underwritten or written on a moratorium basis, and the wording sets out how and whether a condition can later be reconsidered.'],
                ['What is the difference between acute and chronic?', 'Policies are generally built around acute conditions that respond to treatment. Chronic conditions, which are ongoing and managed rather than cured, are usually excluded or limited. Each policy defines both terms.'],
                ['Can I choose my own hospital or consultant?', 'It depends on the plan. Some use a fixed hospital list, others are wider, and some route you through a guided or open referral process. This is one of the main levers on cost.'],
                ['What is a six-week option?', 'A plan feature where private treatment is used only if the NHS wait would be longer than six weeks. It usually reduces the premium and generally applies to admitted treatment rather than every benefit.'],
                ['What happens to cover if I leave my employer\'s scheme?', 'Some schemes offer continuation terms allowing a personal policy without full underwriting, usually within a short window. It varies by scheme and insurer, so ask the administrator before your last day.'],
            ],
            'guides_heading' => 'Guides on health cover',
            'guides'    => ['choosing-private-medical-insurance', 'leaving-company-private-medical-insurance', 'life-insurance-medical-underwriting', 'life-insurance-pre-existing-conditions'],
            'image'     => 'quiet-room',
            'image_alt' => '',
            'eyebrow'   => 'Cover V &middot; Health cover, explained clearly',
            'title'     => 'Private medical insurance',
            'lede'      => 'Private medical insurance can help meet the cost of eligible private diagnosis and treatment. The useful question is not simply who is cheapest, but which cover fits how you want to access care.',
            'cta_short' => 'Discuss private medical cover',
            'intro'     => [
                'h' => 'What private medical insurance is designed to do',
                'p' => 'Policies are generally designed around eligible acute conditions that begin after cover starts. Depending on the plan, benefits may include consultations, diagnostic tests, hospital treatment, therapies, mental health support and cancer care. Exact benefits, limits and routes to treatment vary.',
            ],
            'shapes_heading' => 'Two things to hold in mind',
            'shapes'    => [
                ['h' => 'It works alongside the NHS', 'p' => 'Having private cover does not remove your right to NHS care. A plan can provide another route for eligible treatment.'],
                ['h' => 'It is not designed for everything', 'p' => 'Pre-existing conditions, chronic conditions, routine care and some treatments may be excluded or limited.'],
            ],
            'checklist' => [
                'h'     => 'The choices that can shape cover and cost',
                'items' => [
                    'Individual, couple, family or company-funded cover',
                    'The hospital list and consultant access available to you',
                    'Outpatient limits, therapies, mental health and cancer options',
                    'The excess you agree to pay towards eligible claims',
                    'Full medical underwriting or a moratorium approach, where recent conditions are initially excluded and may later be reconsidered',
                    'Any guided care or open referral pathways, and six-week options that use private treatment only when the NHS wait would be longer than six weeks',
                ],
                'note'  => 'Your priorities may be speed, consultant choice, a particular hospital network, wider outpatient benefits or keeping premiums manageable. An adviser also needs to explain how underwriting works and what will not be covered.',
            ],
            'mis'       => [
                'quote' => 'Private cover means I no longer need the NHS.',
                'p'     => 'A policy is designed to provide another route for eligible treatment, not to replace NHS care. What is covered depends on the plan, its exclusions and how underwriting was arranged.',
            ],
            'guide'     => [
                'href'    => '/guides/choosing-private-medical-insurance/',
                'eyebrow' => 'Keep exploring',
                'h'       => 'How to compare private medical insurance',
                'p'       => 'A practical checklist for benefits, access and cost controls.',
                'more'    => 'Read the guide',
            ],
            'disclaimer' => 'This guide is general information. Cover, underwriting, exclusions, excesses and provider networks depend on the policy and your circumstances.',
            'cta'       => [
                'h' => 'Discuss private medical insurance',
                'p' => 'Start with who needs cover, how you want to access care and the budget you want to keep comfortable.',
            ],
        ]);
    }

    private static function business_protection(): string
    {
        return self::cover_page([
            'topic'     => 'business-protection',
            'faqs'      => [
                ['Who owns a key person policy and who receives the payment?', 'Key person cover is usually owned by and paid to the business, because it is designed to protect the company against the loss of someone it depends on. Ownership needs to match the purpose of the cover.'],
                ['What is shareholder protection?', 'Cover arranged so the remaining owners have funds to buy an affected owner\'s share, normally alongside an appropriately drafted agreement. The cover and the agreement have to work together.'],
                ['Can a relevant life policy include critical illness?', 'No. A relevant life policy can only provide a lump sum on death, with terminal illness generally accepted as an acceleration of that benefit. Critical illness cover has to be arranged separately.'],
                ['Is business protection tax deductible?', 'It depends on the arrangement, who owns the policy, who benefits and the purpose of the cover. Tax treatment depends on individual circumstances and current rules, both of which can change, so take advice from the company accountant.'],
                ['Does a sole trader need business protection?', 'The question is dependency rather than size. If revenue, borrowing or an ownership position would be materially affected by losing one person, the conversation is relevant however small the business.'],
                ['What is executive income protection?', 'Cover designed to help a business keep paying an employee who cannot work through illness or injury, subject to the policy terms. What it can include, such as employer costs, varies by provider.'],
            ],
            'guides_heading' => 'Guides for business owners',
            'guides'    => ['types-of-business-protection', 'relevant-life-vs-key-person-cover', 'protection-when-self-employed', 'death-in-service-and-life-insurance'],
            'image'     => 'workshop',
            'image_alt' => '',
            'eyebrow'   => 'Cover VI &middot; Protecting the people behind the business',
            'title'     => 'Business protection',
            'lede'      => 'Business protection is a group of policies designed to help a company manage the financial impact of death or serious illness affecting an owner, director or key employee.',
            'cta_short' => 'Map your cover',
            'shapes_heading' => 'Five conversations that serve different purposes',
            'shapes'    => [
                ['h' => 'Key person cover', 'p' => 'Designed to pay the business if a person whose skills, relationships or leadership are important dies or meets a covered critical illness definition.'],
                ['h' => 'Shareholder or partnership protection', 'p' => 'Can help provide funds for the remaining owners to buy an affected owner&rsquo;s interest, alongside an appropriately drafted agreement.'],
                ['h' => 'Business loan protection', 'p' => 'Designed around borrowing that could become difficult to repay if a person connected to the debt dies or becomes critically ill.'],
                ['h' => 'Relevant life cover', 'p' => 'An employer-funded life policy for an eligible employee or director, arranged for the benefit of their loved ones rather than the business.'],
                ['h' => 'Executive income protection', 'p' => 'Designed to help a business continue paying an eligible employee when illness or injury prevents them from working, subject to policy terms.'],
            ],
            'checklist' => [
                'h'     => 'Start with the risk, not the product',
                'items' => [
                    'Which people materially affect revenue, operations or lender confidence?',
                    'How would ownership transfer if a shareholder or partner died?',
                    'Which debts have personal guarantees or depend on a key individual?',
                    'What benefits already exist for directors and employees?',
                    'Who should own the policy and receive any benefit?',
                ],
                'note'  => 'Policy ownership, valuation, agreements and potential tax treatment need to align with the purpose of the cover. Tax treatment depends on circumstances and may change, so legal and tax advice can be needed alongside insurance advice.',
            ],
            'mis'       => [
                'quote' => 'The business is small, so none of this applies.',
                'p'     => 'The question is not size but dependency: whether revenue, borrowing or ownership would be materially affected by the loss of one person. That can matter more in a small company, not less.',
            ],
            'guide'     => [
                'href'    => '/guides/types-of-business-protection/',
                'eyebrow' => 'Go deeper',
                'h'       => 'Which type of business protection does what?',
                'p'       => 'Compare who pays, who receives the benefit and what each arrangement is intended to protect.',
                'more'    => 'Read the guide',
            ],
            'disclaimer' => 'This guide is general information and is not legal or tax advice. Eligibility, taxation and policy treatment depend on the arrangement and current rules.',
            'cta'       => [
                'h' => 'Map your business protection needs',
                'p' => 'Bring your ownership structure, borrowing and a simple view of the people the business relies on.',
            ],
        ]);
    }

    private static function general_insurance(): string
    {
        return self::cover_page([
            'topic'     => 'general-insurance',
            'faqs'      => [
                ['What is the difference between buildings and contents insurance?', 'Buildings cover is designed around the structure and permanent fixtures. Contents cover is designed around the belongings you would normally take with you if you moved.'],
                ['How much buildings cover do I need?', 'It is based on the cost of rebuilding the property, which is a different figure from what the property is worth on the market. Insuring against the wrong one is a common way for a policy to fall short.'],
                ['What is underinsurance?', 'Insuring for less than the true value. Where an average condition applies, a settlement can be reduced in proportion to the shortfall, so a claim may pay less than expected even if it is well below the sum insured.'],
                ['Do I have to have buildings insurance?', 'A mortgage lender will normally require suitable buildings cover as a condition of the loan. Contents cover is usually optional. Leasehold arrangements may already include buildings cover, so check the documents.'],
                ['Are my valuables covered automatically?', 'Not necessarily. Policies usually apply a single item limit and a total limit for valuables, and higher value items often need to be specified. Belongings away from home may need separate cover.'],
                ['When do I need cover from when buying a home?', 'Confirm the exact date with your conveyancer and your lender. Contracts vary on when risk passes, and many lenders want cover in force from exchange rather than from moving day.'],
            ],
            'guides_heading' => 'Guides on home cover',
            'guides'    => ['buildings-and-contents-insurance', 'life-insurance-and-mortgages', 'when-to-review-protection-insurance', 'making-a-protection-insurance-claim'],
            'image'     => 'rooflines',
            'image_alt' => '',
            'eyebrow'   => 'Cover VII &middot; Home and general insurance',
            'title'     => 'Home and general insurance',
            'lede'      => 'The right home insurance conversation goes beyond a renewal price. It checks that the building, belongings and valuable items are described accurately and covered on terms you understand.',
            'cta_short' => 'Review your cover',
            'shapes_heading' => 'The two halves of home cover',
            'shapes'    => [
                ['h' => 'Buildings insurance', 'p' => 'Designed around the structure of the home and permanent fixtures. Worth discussing: rebuild cost, extensions, garages, subsidence and escape of water.'],
                ['h' => 'Contents insurance', 'p' => 'Designed around belongings you would normally take if you moved. Worth discussing: replacement basis, valuables, bicycles and accidental damage.'],
            ],
            'checklist' => [
                'h'     => 'Details worth checking before you choose',
                'items' => [
                    'The rebuild value of the property, which is different from its market value',
                    'Single-item and total limits for jewellery, technology and other valuables',
                    'Whether belongings away from home need personal possessions cover',
                    'Accidental damage, home emergency and legal expenses options',
                    'Security requirements, unoccupancy limits and relevant exclusions',
                    'The excess that applies to different types of claim',
                ],
                'note'  => 'A mortgage lender will usually require suitable buildings insurance by exchange or completion. Contents insurance is normally optional, but the cost of replacing a household&rsquo;s belongings can still be substantial. Leasehold arrangements may already include buildings cover, so the documents need checking.',
            ],
            'mis'       => [
                'quote' => 'The rebuild value is the same as what the house is worth.',
                'p'     => 'Rebuild cost and market value are different figures, and insuring against the wrong one is a common way for a policy to fall short at the point of a claim.',
            ],
            'guide'     => [
                'href'    => '/guides/buildings-and-contents-insurance/',
                'eyebrow' => 'Practical guide',
                'h'       => 'Buildings and contents insurance explained',
                'p'       => 'Work through examples, common gaps and the information that helps a useful comparison.',
                'more'    => 'Read the guide',
            ],
            'disclaimer' => 'This page provides general information. Policy limits, exclusions, optional benefits and eligibility vary between insurers and properties.',
            'cta'       => [
                'h' => 'Review home and general insurance',
                'p' => 'Start with the property, who lives there and the belongings or risks you do not want overlooked.',
            ],
        ]);
    }

    /**
     * The guided triage, on a page of its own.
     *
     * It only ever existed on the home page, so it had no URL, could not be
     * linked to and earned nothing in search, despite being the one interactive
     * thing on the site that maps a visitor to a subject.
     */
    private static function starting_point(): string
    {
        return self::html('
<div class="na-v2">
<section class="na-v2-masthead">
  <div class="na-v2-shell na-v2-masthead__grid">
    <div>
      <p class="na-v2-eyebrow">Guided starting point</p>
      <h1>Not sure which conversation you need?</h1>
      <p class="na-v2-lede na-v2-lede--wide">Three short questions to help you work out which subject is worth an adviser conversation. No prices, no recommendation, and nothing is sent anywhere.</p>
    </div>
    <div class="na-v2-masthead__aside">
      <a class="na-v2-btn" href="/guides/">Read the guides instead</a>
      <span class="na-v2-note">No quotes on this site &middot; advice first</span>
    </div>
  </div>
</section>

<section class="na-v2-section na-v2-section--short">
  <div class="na-v2-shell">')
            . self::shortcode('[nest_assured_assessment]')
            . self::html('</div>
</section>

<section class="na-v2-section na-v2-section--short">
  <div class="na-v2-shell">
    <div class="na-v2-callout">
      <div>
        <h2>A subject is not the same as an answer.</h2>
        <p>Which policy would actually pay out in your circumstances depends on your health, your budget and the cover you already hold. That is the conversation this leads to.</p>
      </div>
      <a class="na-v2-btn na-v2-btn--gold" href="/enquire/">Talk to an adviser</a>
    </div>
  </div>
</section>
</div>');
    }

    private static function calculators(): string
    {
        return self::html('
<div class="na-v2">
<section class="na-v2-masthead">
  <div class="na-v2-shell na-v2-masthead__grid">
    <div>
      <p class="na-v2-eyebrow">Planning tools</p>
      <h1>Put the numbers on the table.</h1>
      <p class="na-v2-lede na-v2-lede--wide">Two tools that work only with figures you already know. They do not quote, compare insurers or recommend anything. They exist so the conversation starts from arithmetic rather than guesswork.</p>
    </div>
    <div class="na-v2-masthead__aside">
      <a class="na-v2-btn" href="/enquire/">Talk to an adviser</a>
      <span class="na-v2-note">Nothing you type is sent or stored</span>
    </div>
  </div>
</section>

<section class="na-v2-section na-v2-section--short">
  <div class="na-v2-shell">')
            . self::shortcode('[nest_assured_cover_calculator]')
            . self::html('</div>
</section>

<section class="na-v2-section na-v2-section--short">
  <div class="na-v2-shell">')
            . self::shortcode('[nest_assured_income_calculator]')
            . self::html('</div>
</section>

<section class="na-v2-section na-v2-section--short">
  <div class="na-v2-shell">
    <div class="na-v2-callout">
      <div>
        <h2>A figure is a starting point, not an answer.</h2>
        <p>What the number cannot tell you is which policy would pay out in your circumstances, what your health means for the application, or what is affordable long term. That is the conversation.</p>
      </div>
      <a class="na-v2-btn na-v2-btn--gold" href="/enquire/">Discuss your figures</a>
    </div>
  </div>
</section>
</div>');
    }

    /**
     * The guide hub, rendered from the shared catalogue rather than hand-written
     * cards, so the count and every title stay correct as guides are added.
     */
    private static function guides(): string
    {
        $catalogue = NA_Guides_Library::catalogue();
        $count = count($catalogue);

        $out = '<div class="na-v2">'
            . '<section class="na-v2-section na-v2-section--short"><div class="na-v2-shell">'
            . '<p class="na-v2-eyebrow">The Nest Assured library &middot; ' . (int) $count . ' guides</p>'
            . '<h1>Good questions lead to better conversations.</h1>'
            . '<p class="na-v2-lede na-v2-lede--wide">Plain-English explainers on how UK protection insurance actually works. '
            . 'General information, never a personal recommendation, and never a quote.</p>'
            . '<nav class="na-v2-pills" aria-label="Guide topics">';

        foreach (NA_Guides_Library::groups() as $key => $label) {
            $out .= '<a href="#' . esc_attr($key) . '">' . esc_html($label) . '</a>';
        }

        $out .= '</nav></div></section>';

        $out .= '<section class="na-v2-section na-v2-section--short"><div class="na-v2-shell">'
            . '<a class="na-v2-feature" href="/calculators/"><div class="na-v2-feature__body">'
            . '<p class="na-v2-eyebrow na-v2-eyebrow--light">Start with the numbers</p>'
            . '<h2>Work out the size of the gap first</h2>'
            . '<p>Two planning tools that use only figures you already know, so the conversation starts from arithmetic rather than guesswork.</p>'
            . '<span class="na-v2-feature__more">Open the calculators <span aria-hidden="true">&rarr;</span></span>'
            . '</div><div class="na-v2-feature__mark" aria-hidden="true">'
            . '<img src="' . esc_url(get_theme_file_uri('assets/images/editorial/notebook-800.webp')) . '" width="400" height="225" alt="" loading="lazy" decoding="async" />'
            . '</div></a></div></section>';

        foreach (NA_Guides_Library::groups() as $key => $label) {
            $cards = '';
            foreach ($catalogue as $slug => $guide) {
                if ($guide['group'] !== $key) {
                    continue;
                }
                $cards .= '<a class="na-v2-guide" href="/guides/' . esc_attr($slug) . '/">'
                    . '<p class="na-v2-eyebrow">' . esc_html($guide['eyebrow']) . '</p>'
                    . '<h3>' . esc_html($guide['title']) . '</h3>'
                    . '</a>';
            }

            if ('' === $cards) {
                continue;
            }

            $out .= '<section class="na-v2-section na-v2-section--short" id="' . esc_attr($key) . '">'
                . '<div class="na-v2-shell"><div class="na-v2-headrow"><h2>' . esc_html($label) . '</h2></div>'
                . '<div class="na-v2-guides">' . $cards . '</div></div></section>';
        }

        $out .= '<section class="na-v2-section na-v2-section--short"><div class="na-v2-shell">'
            . '<div class="na-v2-callout"><div><h2>A guide can frame the question.</h2>'
            . '<p>What it cannot do is weigh your health, your budget and the cover you already hold. That happens in a conversation.</p></div>'
            . '<a class="na-v2-btn na-v2-btn--gold" href="/enquire/">Talk to an adviser</a></div>'
            . '</div></section></div>';

        return self::html($out);
    }

    private static function already_client(): string
    {
        return self::html('
<div class="na-v2">
<section class="na-v2-masthead">
  <div class="na-v2-shell na-v2-masthead__grid">
    <div>
      <p class="na-v2-eyebrow">Existing Major Money Matters clients</p>
      <h1 class="na-v2-h1--small">Keep it connected to your mortgage journey.</h1>
      <p class="na-v2-lede na-v2-lede--wide">Your mortgage adviser may already have introduced this conversation. When enquiries open, this route keeps your protection enquiry connected to that journey.</p>
    </div>
    <div class="na-v2-masthead__aside">
      <span class="na-v2-note">A distinct client route &middot; not the standard new-enquiry queue</span>
    </div>
  </div>
</section>

<section class="na-v2-section na-v2-section--paper na-v2-section--short">
  <div class="na-v2-shell na-v2-twocol">
    <div>
      <h2 class="na-v2-h2--small">This is a distinct client route</h2>
      <p>Your mortgage adviser&rsquo;s name and mortgage stage are captured so the enquiry can be matched to the correct adviser queue. It is not placed into the standard new-enquiry route.</p>
    </div>
    <div>
      <h2 class="na-v2-h2--small">Before you start</h2>
      <ul class="na-v2-list na-v2-list--yes">
        <li>Your mortgage adviser&rsquo;s name, if known</li>
        <li>Your mortgage reference, if available</li>
        <li>A general idea of where you are in the mortgage process</li>
      </ul>
    </div>
  </div>
</section>

<section class="na-v2-section na-v2-section--short">
  <div class="na-v2-shell na-v2-form-panel">')
            . self::shortcode('[nest_assured_enquiry mode="existing"]')
            . self::html('</div>
</section>
</div>');
    }

    private static function how_it_works(): string
    {
        return self::html('
<div class="na-v2">
<section class="na-v2-masthead">
  <div class="na-v2-shell na-v2-masthead__grid">
    <div>
      <p class="na-v2-eyebrow">Advice-led means</p>
      <h1>Understanding first, recommendation later.</h1>
      <p class="na-v2-lede na-v2-lede--wide">The website helps you prepare for a conversation. It does not sell policies, calculate premiums or decide which cover is suitable.</p>
    </div>
    <div class="na-v2-masthead__aside">
      <a class="na-v2-btn" href="/enquire/">Start an enquiry</a>
      <span class="na-v2-note">No quotes on this site &middot; advice first</span>
    </div>
  </div>
</section>

<section class="na-v2-section na-v2-section--short">
  <div class="na-v2-shell">
    <h2>Three steps, in order</h2>
    <div class="na-v2-steps__list">
      <div class="na-v2-step"><span class="na-v2-step__num" aria-hidden="true">01</span><div><strong>Choose the right route</strong><span>Existing Major Money Matters clients identify their mortgage adviser. New enquiries go to the protection team.</span></div></div>
      <div class="na-v2-step"><span class="na-v2-step__num" aria-hidden="true">02</span><div><strong>Describe the starting point</strong><span>Share contact details and the subject you want to understand. Sensitive medical information is not requested online.</span></div></div>
      <div class="na-v2-step"><span class="na-v2-step__num" aria-hidden="true">03</span><div><strong>Have the adviser conversation</strong><span>The adviser reviews needs, existing arrangements, affordability, policy terms and any relevant exclusions.</span></div></div>
    </div>
  </div>
</section>

<section class="na-v2-section na-v2-section--paper na-v2-section--short">
  <div class="na-v2-shell na-v2-twocol">
    <div>
      <h2 class="na-v2-h2--small">What happens after an enquiry?</h2>
      <p>The team on your assigned route reviews the information you submitted. A conversation is then used to establish whether advice is appropriate and what further information is needed. No cover begins because a form was submitted.</p>
    </div>
    <div>
      <h2 class="na-v2-h2--small">What should you bring?</h2>
      <ul class="na-v2-list na-v2-list--yes">
        <li>Existing policy schedules or workplace benefit information</li>
        <li>Mortgage and household commitment information</li>
        <li>Questions you want answered in plain English</li>
      </ul>
    </div>
  </div>
</section>

<section class="na-v2-section na-v2-section--short">
  <div class="na-v2-shell">')
            . self::shortcode('[nest_assured_booking]')
            . self::html('<p class="na-v2-note na-v2-note--block">Policy eligibility, pricing, definitions and exclusions depend on individual circumstances and the insurer&rsquo;s terms. These are discussed during the advice process.</p>
    <div class="na-v2-callout">
      <div>
        <h2>Start with a conversation</h2>
        <p>Send an enquiry and an adviser will arrange a time that suits you.</p>
      </div>
      <a class="na-v2-btn na-v2-btn--gold" href="/enquire/">Talk to an adviser</a>
    </div>
  </div>
</section>
</div>');
    }

    private static function faqs(): string
    {
        return self::html('
<div class="na-v2">
<section class="na-v2-masthead">
  <div class="na-v2-shell na-v2-masthead__grid">
    <div>
      <p class="na-v2-eyebrow">Questions</p>
      <h1>Questions from real conversations.</h1>
      <p class="na-v2-lede na-v2-lede--wide">This page is reserved for questions drawn from real Nest Assured adviser conversations.</p>
    </div>
    <div class="na-v2-masthead__aside">
      <a class="na-v2-btn" href="/enquire/">Ask an adviser</a>
      <span class="na-v2-note">No quotes on this site &middot; advice first</span>
    </div>
  </div>
</section>

<section class="na-v2-section na-v2-section--short">
  <div class="na-v2-shell">')
            . self::shortcode('[nest_assured_faqs]')
            . self::html('</div>
</section>
</div>');
    }

    private static function about(): string
    {
        return self::html('
<div class="na-v2">
<section class="na-v2-section na-v2-section--short">
  <div class="na-v2-shell na-v2-hero__grid">
    <div>
      <p class="na-v2-eyebrow">About Nest Assured &middot; Protection adviser</p>
      <h1>Meet Ollie Allen.</h1>
      <p class="na-v2-lede">Nest Assured is the protection advice service connected to Major Money Matters. Every enquiry runs through one named adviser, so the person who reads your form is the person you speak to.</p>
      <div class="na-v2-actions">
        <a class="na-v2-btn" href="/enquire/">Book a call with Ollie</a>
        <span class="na-v2-note">Harold Wood, Essex &middot; UK-wide by phone or video</span>
      </div>
    </div>')
            . self::shortcode('[nest_assured_adviser variant="portrait"]')
            . self::html('</div>
</section>

<section class="na-v2-section na-v2-section--paper">
  <div class="na-v2-shell">')
            . self::shortcode('[nest_assured_ollie_profile]')
            . self::html('</div>
</section>

<section class="na-v2-section na-v2-section--short">
  <div class="na-v2-shell na-v2-split">
    <div class="na-v2-panel na-v2-panel--paper">
      <p class="na-v2-eyebrow">Regulatory status</p>')
            . self::shortcode('[nest_assured_regulatory]')
            . self::html('</div>
    <div class="na-v2-panel na-v2-panel--paper">
      <p class="na-v2-eyebrow">Editorial standards</p>
      <h2>Guides that say who stands behind them</h2>
      <p>Guides show who wrote them, when they were last updated, and name a reviewer once a review has been recorded. They carry general information only. A recommendation happens in a regulated conversation, never on a web page.</p>
      <a class="na-v2-link" href="/editorial-policy/">Read the editorial policy <span aria-hidden="true">&rarr;</span></a>
    </div>
  </div>
</section>

<section class="na-v2-section na-v2-section--short">
  <div class="na-v2-shell">
    <div class="na-v2-callout">
      <div>
        <h2>Thirty minutes, in plain English.</h2>
        <p>Your existing cover, your commitments, your budget. Only then, advice.</p>
      </div>
      <a class="na-v2-btn na-v2-btn--gold" href="/enquire/">Arrange a conversation</a>
    </div>
  </div>
</section>
</div>');
    }

    private static function contact(): string
    {
        return self::html('
<div class="na-v2">
<section class="na-v2-masthead">
  <div class="na-v2-shell na-v2-masthead__grid">
    <div>
      <p class="na-v2-eyebrow">Choose the right contact route</p>
      <h1>Keep the conversation connected.</h1>
      <p class="na-v2-lede na-v2-lede--wide">We ask existing clients to identify their mortgage adviser so their enquiry can follow the established journey.</p>
    </div>
    <div class="na-v2-masthead__aside">')
            . self::shortcode('[nest_assured_contact_details]')
            . self::html('<span class="na-v2-note">Harold Wood, Essex &middot; UK-wide by phone or video</span>
    </div>
  </div>
</section>

<section class="na-v2-section na-v2-section--short">
  <div class="na-v2-shell na-v2-mis">
    <a class="na-v2-guide" href="/already-a-client/">
      <p class="na-v2-eyebrow">Existing client</p>
      <h3>I am an existing Major Money Matters client</h3>
      <p class="na-v2-guide__desc">Continue with your adviser-connected protection route.</p>
      <span class="na-v2-guide__more">Use the existing-client route <span aria-hidden="true">&rarr;</span></span>
    </a>
    <a class="na-v2-guide" href="/enquire/">
      <p class="na-v2-eyebrow">New enquiry</p>
      <h3>I am making a new enquiry</h3>
      <p class="na-v2-guide__desc">Contact the protection team about a new advice conversation.</p>
      <span class="na-v2-guide__more">Make a new enquiry <span aria-hidden="true">&rarr;</span></span>
    </a>
  </div>
</section>
</div>');
    }

    private static function enquire(): string
    {
        return self::html('
<div class="na-v2">
<section class="na-v2-section na-v2-section--short">
  <div class="na-v2-shell na-v2-enquire">
    <div class="na-v2-enquire__intro">
      <p class="na-v2-eyebrow">Talk to an adviser</p>
      <h1 class="na-v2-h1--small">Start the conversation.</h1>
      <p class="na-v2-lede">Tell us whether you are an existing Major Money Matters client, so your enquiry reaches the right place when enquiries open. Nothing is sold or decided at this stage.</p>
      <div class="na-v2-steps__list">
        <div class="na-v2-step"><span class="na-v2-step__num" aria-hidden="true">01</span><div><strong>Your enquiry is read</strong><span>The right adviser reviews what you send. Nothing is sold or decided at this stage.</span></div></div>
        <div class="na-v2-step"><span class="na-v2-step__num" aria-hidden="true">02</span><div><strong>A short conversation</strong><span>An adviser arranges a time to understand your situation, existing cover and questions.</span></div></div>
        <div class="na-v2-step"><span class="na-v2-step__num" aria-hidden="true">03</span><div><strong>Advice to consider</strong><span>Any recommendation arrives in writing, in plain English, for you to consider in your own time.</span></div></div>
      </div>')
            . self::shortcode('[nest_assured_adviser variant="pill"]')
            . self::html('</div>
    <div class="na-v2-form-panel">')
            . self::shortcode('[nest_assured_enquiry]')
            . self::html('</div>
  </div>
</section>
</div>');
    }

    private static function legal_index(): string
    {
        return self::html('
<div class="na-v2">
<section class="na-v2-masthead">
  <div class="na-v2-shell na-v2-masthead__grid">
    <div>
      <p class="na-v2-eyebrow">Legal</p>
      <h1 class="na-v2-h1--small">Legal and regulatory information.</h1>
      <p class="na-v2-lede na-v2-lede--wide">Legal, privacy and regulatory information for the Nest Assured website.</p>
    </div>
  </div>
</section>

<section class="na-v2-section na-v2-section--short">
  <div class="na-v2-shell na-v2-guides">
    <a class="na-v2-guide" href="/legal/privacy/"><h3>Privacy</h3><p class="na-v2-guide__desc">How enquiry information is collected, used and retained.</p></a>
    <a class="na-v2-guide" href="/legal/complaints-procedure/"><h3>Complaints</h3><p class="na-v2-guide__desc">The approved complaints process and escalation information.</p></a>
    <a class="na-v2-guide" href="/legal/financial-promotions/"><h3>Financial promotions</h3><p class="na-v2-guide__desc">The scope and status of information published on this site.</p></a>
  </div>
</section>
</div>');
    }

    private static function privacy(): string
    {
        return self::html('
<div class="na-v2">
<section class="na-v2-masthead">
  <div class="na-v2-shell na-v2-masthead__grid">
    <div>
      <p class="na-v2-eyebrow">Legal</p>
      <h1 class="na-v2-h1--small">Privacy notice.</h1>
      <p class="na-v2-lede na-v2-lede--wide">How enquiry information is collected, used and retained.</p>
    </div>
  </div>
</section>

<section class="na-v2-section na-v2-section--short">
  <div class="na-v2-shell na-v2-prose">')
            . self::shortcode('[nest_assured_privacy]')
            . self::html('</div>
</section>
</div>');
    }

    private static function complaints(): string
    {
        return self::html('
<div class="na-v2">
<section class="na-v2-masthead">
  <div class="na-v2-shell na-v2-masthead__grid">
    <div>
      <p class="na-v2-eyebrow">Legal</p>
      <h1 class="na-v2-h1--small">Complaints procedure.</h1>
      <p class="na-v2-lede na-v2-lede--wide">How to raise a concern and how it will be handled.</p>
    </div>
  </div>
</section>

<section class="na-v2-section na-v2-section--short">
  <div class="na-v2-shell na-v2-prose">')
            . self::shortcode('[nest_assured_complaints]')
            . self::html('</div>
</section>
</div>');
    }

    private static function financial_promotions(): string
    {
        return self::html('
<div class="na-v2">
<section class="na-v2-masthead">
  <div class="na-v2-shell na-v2-masthead__grid">
    <div>
      <p class="na-v2-eyebrow">Legal</p>
      <h1 class="na-v2-h1--small">Financial promotions.</h1>
      <p class="na-v2-lede na-v2-lede--wide">This website provides general education and a route into an adviser conversation.</p>
    </div>
  </div>
</section>

<section class="na-v2-section na-v2-section--short">
  <div class="na-v2-shell na-v2-prose">
    <h2 class="na-v2-h2--small">What the site does not do</h2>
    <ul class="na-v2-list na-v2-list--no">
      <li>It does not generate an insurance quote.</li>
      <li>It does not provide a personal recommendation.</li>
      <li>It does not compare premiums or policy terms for purchase.</li>
      <li>It does not allow a visitor to buy or begin cover online.</li>
    </ul>
    <p class="na-v2-note na-v2-note--block">Eligibility, policy definitions, exclusions, premiums and suitability depend on individual circumstances and the insurer&rsquo;s terms.</p>')
            . self::shortcode('[nest_assured_financial]')
            . self::html('</div>
</section>
</div>');
    }
}
