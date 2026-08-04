<?php
/**
 * Front-end shortcodes.
 *
 * @package NestAssuredCore
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

final class NA_Shortcodes
{
    public static function init(): void
    {
        add_shortcode('nest_assured_enquiry', [self::class, 'enquiry']);
        add_shortcode('nest_assured_assessment', [self::class, 'assessment']);
        add_shortcode('nest_assured_booking', [self::class, 'booking']);
        add_shortcode('nest_assured_regulatory', [self::class, 'regulatory']);
        add_shortcode('nest_assured_privacy', [self::class, 'privacy']);
        add_shortcode('nest_assured_complaints', [self::class, 'complaints']);
        add_shortcode('nest_assured_financial', [self::class, 'financial']);
        add_shortcode('nest_assured_ollie', [self::class, 'ollie']);
        add_shortcode('nest_assured_faqs', [self::class, 'faqs']);
        add_shortcode('nest_assured_reviews', [self::class, 'reviews']);
        add_shortcode('nest_assured_legal_links', [self::class, 'legal_links']);
        add_shortcode('nest_assured_prelaunch_note', [self::class, 'prelaunch_note']);
        add_shortcode('nest_assured_adviser', [self::class, 'adviser']);
        add_shortcode('nest_assured_assurance', [self::class, 'assurance']);
        add_shortcode('nest_assured_social_proof', [self::class, 'social_proof']);
        add_shortcode('nest_assured_footer_reviews', [self::class, 'footer_reviews']);
        add_shortcode('nest_assured_dock', [self::class, 'dock']);
        add_shortcode('nest_assured_ollie_profile', [self::class, 'ollie_profile']);
        add_shortcode('nest_assured_footer_regulatory', [self::class, 'footer_regulatory']);
        add_shortcode('nest_assured_copyright', [self::class, 'copyright']);
        add_shortcode('nest_assured_contact_details', [self::class, 'contact_details']);

        add_action('wp_footer', [self::class, 'render_dock']);
    }

    /**
     * The exact consent wording presented to the visitor. Kept in one place so the
     * stored consent record can hash precisely what was agreed to.
     */
    public static function consent_text(): string
    {
        return 'I consent to Nest Assured using these details to respond to my enquiry and route it to the appropriate adviser.';
    }

    /**
     * @param array<string, mixed> $atts Shortcode attributes.
     */
    public static function enquiry(array $atts = []): string
    {
        if (! NA_Settings::is_launch_ready()) {
            return '<div class="na-status"><h2>Online enquiries are not open yet</h2><p>The secure adviser-routing and approved privacy controls are being completed before this form is made available.</p></div>';
        }

        // The form carries a per-session nonce, so any page rendering it must never be
        // cached. Declaring this here means correctness does not depend on host-level
        // cache exclusions that are not part of this repository.
        if (! defined('DONOTCACHEPAGE')) {
            define('DONOTCACHEPAGE', true);
        }
        if (! headers_sent()) {
            nocache_headers();
        }

        $atts = shortcode_atts(['mode' => ''], $atts, 'nest_assured_enquiry');
        $mode = in_array($atts['mode'], ['existing', 'new'], true) ? (string) $atts['mode'] : '';
        $topics = [
            'life-insurance',
            'income-protection',
            'critical-illness',
            'family-protection',
            'private-medical-insurance',
            'business-protection',
            'general-insurance',
        ];
        $topic = sanitize_key((string) filter_input(INPUT_GET, 'topic', FILTER_SANITIZE_SPECIAL_CHARS));
        $topic = in_array($topic, $topics, true) ? $topic : '';
        if ('' !== $topic && '' === $mode) {
            $mode = 'new';
        }
        $status = sanitize_key((string) filter_input(INPUT_GET, 'enquiry', FILTER_SANITIZE_SPECIAL_CHARS));
        $messages = [
            'received'   => ['success', 'Thank you. Your enquiry has been received and passed to the right adviser team.'],
            'pending'    => ['success', 'Thank you. Your enquiry has been saved and we will confirm by email shortly.'],
            'validation' => ['error', 'Please check the fields marked as required, including consent, and send again.'],
            'security'   => ['error', 'The form session expired. Please refresh the page and try again.'],
            'rate'       => ['error', 'Please wait a moment before sending another enquiry.'],
            'storage'    => ['error', 'We could not store the enquiry. Please try again.'],
        ];
        $request_uri = isset($_SERVER['REQUEST_URI'])
            ? esc_url_raw(wp_unslash((string) $_SERVER['REQUEST_URI']))
            : '/enquire/';
        $current_url = home_url($request_uri);
        $current_url = remove_query_arg('enquiry', $current_url);

        ob_start();
        ?>
        <?php // novalidate is applied by JavaScript, not in the markup, so that without
              // JavaScript the browser's own validation still runs. ?>
        <form class="na-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" data-na-enquiry>
            <?php if (isset($messages[$status])) : ?>
                <div class="na-form__message na-form__message--<?php echo esc_attr($messages[$status][0]); ?>" role="<?php echo 'success' === $messages[$status][0] ? 'status' : 'alert'; ?>" tabindex="-1" data-na-form-message>
                    <?php echo esc_html($messages[$status][1]); ?>
                </div>
            <?php endif; ?>

            <input type="hidden" name="action" value="na_submit_enquiry" />
            <input type="hidden" name="redirect_to" value="<?php echo esc_url($current_url); ?>" />
            <?php wp_nonce_field('na_enquiry', 'na_nonce'); ?>
            <div class="na-honeypot" aria-hidden="true" hidden>
                <label for="na-website">Website</label>
                <input id="na-website" type="text" name="website" tabindex="-1" autocomplete="off" />
            </div>

            <fieldset>
                <legend>Are you already a Major Money Matters client?</legend>
                <div class="na-choices">
                    <label class="na-choice">
                        <input type="radio" name="client_type" value="existing" <?php checked('existing', $mode); ?> required />
                        <span><strong>Yes, I am an existing client</strong><br />Continue a protection conversation connected to your mortgage journey.</span>
                    </label>
                    <label class="na-choice">
                        <input type="radio" name="client_type" value="new" <?php checked('new', $mode); ?> required />
                        <span><strong>No, this is a new enquiry</strong><br />Start a new conversation with the protection team.</span>
                    </label>
                </div>
            </fieldset>

            <div class="na-form__grid">
                <div class="na-field">
                    <label for="na-full-name">Full name</label>
                    <input id="na-full-name" type="text" name="full_name" autocomplete="name" required />
                </div>
                <div class="na-field">
                    <label for="na-email">Email address</label>
                    <input id="na-email" type="email" name="email" autocomplete="email" required />
                </div>
                <div class="na-field">
                    <label for="na-phone">Phone number</label>
                    <input id="na-phone" type="tel" name="phone" autocomplete="tel" data-na-phone aria-describedby="na-phone-hint" />
                    <small class="na-field__hint" id="na-phone-hint">Needed if you would like a call back; leave blank if you prefer email.</small>
                </div>
                <div class="na-field">
                    <label for="na-contact-preference">Preferred contact method</label>
                    <select id="na-contact-preference" name="contact_preference" data-na-contact-preference>
                        <option value="either">Phone or email</option>
                        <option value="phone">Phone</option>
                        <option value="email">Email</option>
                    </select>
                </div>
            </div>

            <?php // Branch sections start visible and are hidden by JavaScript on load.
                  // Starting them hidden meant that without JavaScript an existing client
                  // could never reach the adviser field the server requires, leaving them
                  // in a validation loop with no way out. ?>
            <fieldset class="na-form__branch" data-branch="existing">
                <legend>Your existing mortgage journey</legend>
                <div class="na-field">
                    <label for="na-adviser-name">Your mortgage adviser’s name</label>
                    <input id="na-adviser-name" type="text" name="adviser_name" autocomplete="off" data-required-for="existing" aria-describedby="na-adviser-hint" />
                    <small class="na-field__hint" id="na-adviser-hint">Enter their name if known so the enquiry can follow the established adviser route.</small>
                    <label class="na-inline-choice"><input type="checkbox" name="adviser_unknown" value="1" data-na-adviser-unknown /> <span>I am not sure who my mortgage adviser is</span></label>
                </div>
                <div class="na-form__grid">
                    <div class="na-field">
                        <label for="na-mortgage-reference">Mortgage reference, if known</label>
                        <input id="na-mortgage-reference" type="text" name="mortgage_reference" autocomplete="off" />
                    </div>
                    <div class="na-field">
                        <label for="na-mortgage-stage">Where are you in the process?</label>
                        <select id="na-mortgage-stage" name="mortgage_stage">
                            <option value="">Choose one</option>
                            <option value="application">Mortgage application in progress</option>
                            <option value="offer">Mortgage offer received</option>
                            <option value="completion">Completion approaching</option>
                            <option value="completed">Mortgage completed</option>
                            <option value="unsure">Not sure</option>
                        </select>
                    </div>
                </div>
            </fieldset>

            <fieldset class="na-form__branch" data-branch="new">
                <legend>Your new enquiry</legend>
                <div class="na-field">
                    <label for="na-product-interest">What would you like to discuss?</label>
                    <select id="na-product-interest" name="product_interest">
                        <option value="not-sure" <?php selected('', $topic); ?>>I am not sure yet</option>
                        <option value="life-insurance" <?php selected('life-insurance', $topic); ?>>Life insurance</option>
                        <option value="income-protection" <?php selected('income-protection', $topic); ?>>Income protection</option>
                        <option value="critical-illness" <?php selected('critical-illness', $topic); ?>>Critical illness cover</option>
                        <option value="family-protection" <?php selected('family-protection', $topic); ?>>Family protection (the wider picture)</option>
                        <option value="private-medical-insurance" <?php selected('private-medical-insurance', $topic); ?>>Private medical insurance</option>
                        <option value="business-protection" <?php selected('business-protection', $topic); ?>>Business protection</option>
                        <option value="general-insurance" <?php selected('general-insurance', $topic); ?>>Home and general insurance</option>
                    </select>
                </div>
                <div class="na-field">
                    <label for="na-main-concern">What would be most useful to understand?</label>
                    <textarea id="na-main-concern" name="main_concern" rows="4" aria-describedby="na-concern-hint"></textarea>
                    <small class="na-field__hint" id="na-concern-hint">Do not include medical details. An adviser can discuss sensitive information with you privately.</small>
                </div>
            </fieldset>

            <div class="na-field">
                <label class="na-choice">
                    <input type="checkbox" name="consent" value="1" required />
                    <span><?php echo esc_html(self::consent_text()); ?> I have read the <a href="<?php echo esc_url(home_url('/legal/privacy/')); ?>">privacy information</a>.</span>
                </label>
            </div>

            <p class="na-disclaimer">Submitting this form does not create insurance cover and is not a quote or recommendation. An adviser will review your enquiry before discussing suitable next steps.</p>
            <button class="na-button" type="submit">Send my enquiry</button>
        </form>
        <?php
        return (string) ob_get_clean();
    }

    public static function assessment(): string
    {
        ob_start();
        ?>
        <section class="na-assessment" data-na-assessment aria-labelledby="na-assessment-title">
            <p class="na-eyebrow">Guided starting point</p>
            <h2 id="na-assessment-title">Which conversation might help?</h2>
            <p>Three short questions can help you choose a useful subject for an adviser conversation. No prices or policy recommendations are produced.</p>
            <?php // Steps two and three are revealed by JavaScript, so without it this
                  // panel is a single question and an inert button. Say so, and offer the
                  // route the panel exists to lead to. ?>
            <noscript>
                <p class="na-note">This guided panel needs JavaScript. You can <a href="<?php echo esc_url(home_url('/enquire/')); ?>">talk to an adviser directly</a> instead, or read the <a href="<?php echo esc_url(home_url('/guides/')); ?>">protection guides</a>.</p>
            </noscript>
            <p class="na-assessment__progress" aria-live="polite">Question 1 of 3</p>

            <fieldset class="na-assessment__step" data-step="1">
                <legend>Who currently depends on your income?</legend>
                <div class="na-choices">
                    <label class="na-choice"><input type="radio" name="dependants" value="family" /><span>A partner, children or other family members</span></label>
                    <label class="na-choice"><input type="radio" name="dependants" value="financial" /><span>Someone shares financial commitments with me</span></label>
                    <label class="na-choice"><input type="radio" name="dependants" value="none" /><span>No one at present</span></label>
                    <label class="na-choice"><input type="radio" name="dependants" value="unsure" /><span>I am not sure how to answer</span></label>
                </div>
            </fieldset>

            <fieldset class="na-assessment__step" data-step="2" hidden>
                <legend>Do you have cover through work?</legend>
                <div class="na-choices">
                    <label class="na-choice"><input type="radio" name="work_cover" value="yes" /><span>Yes, and I know what it provides</span></label>
                    <label class="na-choice"><input type="radio" name="work_cover" value="some" /><span>Possibly, but I am not sure of the details</span></label>
                    <label class="na-choice"><input type="radio" name="work_cover" value="no" /><span>No</span></label>
                    <label class="na-choice"><input type="radio" name="work_cover" value="unsure" /><span>I do not know</span></label>
                </div>
            </fieldset>

            <fieldset class="na-assessment__step" data-step="3" hidden>
                <legend>What is the main point you want to understand?</legend>
                <div class="na-choices">
                    <label class="na-choice"><input type="radio" name="concern" value="family" /><span>How household commitments could be managed if I died</span></label>
                    <label class="na-choice"><input type="radio" name="concern" value="income" /><span>How I could manage if illness or injury stopped me working</span></label>
                    <label class="na-choice"><input type="radio" name="concern" value="illness" /><span>How a serious illness could affect my finances</span></label>
                    <label class="na-choice"><input type="radio" name="concern" value="overall" /><span>How the different types of cover fit together</span></label>
                </div>
            </fieldset>

            <div class="na-assessment__controls">
                <button class="na-button na-button--outline" type="button" data-assessment-back hidden>Back</button>
                <button class="na-button" type="button" data-assessment-next>Next question</button>
            </div>
            <p class="na-assessment__error" role="alert" data-assessment-error hidden>Please choose an answer before continuing.</p>

            <div class="na-assessment__result" data-assessment-result hidden tabindex="-1" aria-live="polite"></div>
            <p class="na-disclaimer">This is a starting point for a conversation with a regulated adviser, not a quote or a recommendation.</p>
        </section>
        <?php
        return (string) ob_get_clean();
    }

    public static function booking(): string
    {
        $url = NA_Settings::get('booking_url');
        if ('' === $url) {
            return current_user_can('manage_options')
                ? '<div class="na-admin-only"><strong>Launch control:</strong> Add the approved adviser diary URL to replace this notice.</div>'
                : '<div class="na-note"><h3>Start with an enquiry</h3><p>Use the secure enquiry route and an adviser can arrange the appropriate next step.</p><a class="na-button" href="/enquire/">Talk to an adviser</a></div>';
        }

        return sprintf(
            '<iframe class="na-booking-frame" src="%s" title="Book a conversation with a Nest Assured adviser" loading="lazy" referrerpolicy="strict-origin-when-cross-origin"></iframe>',
            esc_url($url)
        );
    }

    public static function regulatory(): string
    {
        $copy = NA_Settings::get('regulatory_copy');
        $reference = NA_Settings::get('fca_reference');
        if ('' === $copy || '' === $reference) {
            return self::pending('Compliance-approved regulatory status and FCA reference');
        }

        return '<div class="na-note">' . wp_kses_post(wpautop($copy)) . '<p><strong>FCA reference:</strong> ' . esc_html($reference) . '</p></div>';
    }

    public static function privacy(): string
    {
        $copy = NA_Settings::get('privacy_copy');
        return '' === $copy ? self::pending('Compliance-approved privacy notice') : '<div class="na-prose">' . wp_kses_post(wpautop($copy)) . '</div>';
    }

    public static function complaints(): string
    {
        $copy = NA_Settings::get('complaints_copy');
        return '' === $copy ? self::pending('Compliance-approved complaints procedure') : '<div class="na-prose">' . wp_kses_post(wpautop($copy)) . '</div>';
    }

    public static function financial(): string
    {
        $copy = NA_Settings::get('financial_copy');
        return '' === $copy ? self::pending('Compliance-approved financial promotions wording') : '<div class="na-prose">' . wp_kses_post(wpautop($copy)) . '</div>';
    }

    public static function ollie(): string
    {
        $bio = NA_Settings::get('ollie_bio');
        $photo = NA_Settings::get('ollie_photo_url');
        if ('' === $bio || '' === $photo) {
            return '<div class="na-card na-team-card"><div class="na-team-card__portrait" aria-hidden="true">OA</div><div><p class="na-eyebrow">Protection adviser</p><h2>Ollie Allen</h2>' . self::pending('Ollie Allen approved biography and photography') . '</div></div>';
        }

        $photo_id = (int) NA_Settings::get('ollie_photo_id');
        $photo_html = $photo_id > 0
            ? wp_get_attachment_image($photo_id, 'medium', false, [
                'class'   => 'na-team-card__photo',
                'alt'     => 'Ollie Allen, protection adviser',
                'loading' => 'lazy',
            ])
            : '<img class="na-team-card__photo" src="' . esc_url($photo) . '" alt="Ollie Allen, protection adviser" loading="lazy" />';

        return '<div class="na-card na-team-card">' . $photo_html . '<div><p class="na-eyebrow">Protection adviser</p><h2>Ollie Allen</h2>' . wp_kses_post(wpautop($bio)) . '</div></div>';
    }

    public static function faqs(): string
    {
        $copy = NA_Settings::get('faqs_copy');
        if ('' === $copy) {
            return '<div class="na-status"><h2>Approved adviser questions are being prepared</h2><p>This page is intentionally not filled with generic insurance questions. Ollie Allen will supply questions drawn from real adviser conversations before launch.</p></div>';
        }

        return '<div class="na-prose na-faqs">' . wp_kses_post(wpautop($copy)) . '</div>';
    }

    public static function reviews(): string
    {
        $url = NA_Settings::get('google_reviews_url');
        if ('' === $url) {
            return '';
        }

        return '<div class="na-card"><h2>Read our Google reviews</h2><p>Reviews are shown only through the verified Google Business Profile.</p><a class="na-button na-button--outline" href="' . esc_url($url) . '" rel="noopener noreferrer">View Google reviews</a></div>';
    }

    /**
     * Returns anchors only. The surrounding <nav> lives in the template, because the
     * core shortcode block runs wpautop before do_shortcode and would otherwise wrap
     * block-level output in a paragraph, producing invalid markup.
     */
    public static function legal_links(): string
    {
        $links = ['<a href="' . esc_url(home_url('/legal/privacy/')) . '">Privacy</a>'];
        if ('' !== trim(NA_Settings::get('complaints_copy'))) {
            $links[] = '<a href="' . esc_url(home_url('/legal/complaints-procedure/')) . '">Complaints procedure</a>';
        }
        if ('' !== trim(NA_Settings::get('financial_copy'))) {
            $links[] = '<a href="' . esc_url(home_url('/legal/financial-promotions/')) . '">Financial promotions</a>';
        }

        return implode('', $links);
    }

    public static function prelaunch_note(): string
    {
        if (NA_Settings::is_launch_ready()) {
            return '';
        }

        return 'Regulatory wording is subject to compliance approval before launch.';
    }

    /**
     * The site-wide regulatory status line. Regulatory status disclosure belongs on
     * every page, not one page, but the wording is a compliance matter: this renders
     * only the approved text and never substitutes wording of its own.
     */
    public static function footer_regulatory(): string
    {
        $copy = trim(NA_Settings::get('regulatory_copy'));
        $reference = trim(NA_Settings::get('fca_reference'));

        if ('' === $copy || '' === $reference) {
            return '';
        }

        return '<p>' . wp_kses_post($copy) . ' FCA reference ' . esc_html($reference) . '.</p>';
    }

    /**
     * The whole copyright line, not just the year. The core shortcode block wraps
     * its output in a paragraph, so a shortcode sitting inside a template paragraph
     * produced nested <p> elements and the line broke apart.
     */
    public static function copyright(): string
    {
        return '&copy; ' . esc_html(gmdate('Y')) . ' Nest Assured. All rights reserved. &middot; 133 Shepherds Hill, Harold Wood, RM3 0NR';
    }

    /**
     * Telephone and email routes. Returns anchors only, so the caller supplies the
     * block-level wrapper (the core shortcode block runs wpautop before
     * do_shortcode). Renders nothing until real details are entered.
     */
    public static function contact_details(): string
    {
        $phone = trim(NA_Settings::get('contact_phone'));
        $email = trim(NA_Settings::get('contact_email'));
        $parts = [];

        if ('' !== $phone) {
            $tel = preg_replace('/[^0-9+]/', '', $phone);
            $parts[] = '<a href="tel:' . esc_attr((string) $tel) . '">' . esc_html($phone) . '</a>';
        }

        if (is_email($email)) {
            $parts[] = '<a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a>';
        }

        return implode('<span aria-hidden="true"> &middot; </span>', $parts);
    }

    /**
     * Adviser imagery for the home page. Renders the approved photograph when one has
     * been supplied in Settings, and a neutral monogram plate until then, so that no
     * unapproved likeness is ever published.
     *
     * @param array<string, mixed> $atts Shortcode attributes.
     */
    public static function adviser(array $atts = []): string
    {
        $atts = shortcode_atts(['variant' => 'portrait'], is_array($atts) ? $atts : [], 'nest_assured_adviser');
        $variant = 'pill' === $atts['variant'] ? 'pill' : 'portrait';

        if ('pill' === $variant) {
            return '<p class="na-v2-pill">'
                . self::adviser_image('na-v2-pill__photo', 'na-v2-pill__plate')
                . '<span>Every enquiry is read by Ollie Allen, not a call centre. <a href="/enquire/">Book a callback time</a>.</span>'
                . '</p>';
        }

        return '<figure class="na-v2-portrait">'
            . self::adviser_image('na-v2-portrait__photo', 'na-v2-portrait__plate')
            . '<figcaption>Ollie Allen &mdash; dedicated protection adviser. Every enquiry is read by him, not a call centre.</figcaption>'
            . '</figure>';
    }

    private static function adviser_image(string $photo_class, string $plate_class): string
    {
        $photo = NA_Settings::get('ollie_photo_url');
        if ('' === $photo) {
            return '<span class="' . esc_attr($plate_class) . '" role="img" aria-label="Photograph of Ollie Allen to follow">OA</span>';
        }

        $photo_id = (int) NA_Settings::get('ollie_photo_id');
        if ($photo_id > 0) {
            return (string) wp_get_attachment_image($photo_id, 'medium_large', false, [
                'class'   => $photo_class,
                'alt'     => 'Ollie Allen, protection adviser',
                'loading' => 'lazy',
            ]);
        }

        return '<img class="' . esc_attr($photo_class) . '" src="' . esc_url($photo) . '" alt="Ollie Allen, protection adviser" loading="lazy" />';
    }

    /**
     * The assurance strip beneath the hero. Regulated claims appear only once the
     * matching approved value has been entered in Settings.
     */
    public static function assurance(): string
    {
        $reference = trim(NA_Settings::get('fca_reference'));
        $reviews = trim(NA_Settings::get('google_reviews_url'));

        $cells = [];

        $cells[] = '<div class="na-v2-assurance__cell"><strong>Advice before decisions</strong><span>No instant quotes on this site</span></div>';

        // Regulated claims are omitted entirely until the approved value exists. A cell
        // reading "published at launch" is itself an unapproved statement of regulatory
        // status, so there is no placeholder branch here.
        if ('' !== $reference) {
            $cells[] = '<div class="na-v2-assurance__cell"><strong>Regulated advice route</strong><span>FCA reference ' . esc_html($reference) . '</span></div>';
        }

        if ('' !== $reviews) {
            $cells[] = '<div class="na-v2-assurance__cell"><strong><a href="' . esc_url($reviews) . '" rel="noopener noreferrer">Google reviews</a></strong><span>Read the verified Google Business Profile</span></div>';
        }

        return '<section class="na-v2-assurance" aria-label="Why Nest Assured"><div class="na-v2-shell na-v2-assurance__grid">'
            . implode('', $cells)
            . '</div></section>';
    }

    /**
     * The review slot on the home page. Nothing is shown in place of a real review:
     * either the verified Google profile is linked, or the slot states that reviews
     * are still to come.
     */
    public static function social_proof(): string
    {
        $reviews = trim(NA_Settings::get('google_reviews_url'));

        // Render nothing at all until there is a verified profile to link. An empty
        // "what clients say" block tells a prospect only that there are no reviews.
        if ('' === $reviews) {
            return '';
        }

        return '<div class="na-v2-proof">'
            . '<p class="na-v2-eyebrow na-v2-eyebrow--light">What clients say</p>'
            . '<p>Reviews are published only through the verified Google Business Profile, so you can read every one of them in full.</p>'
            . '<a class="na-v2-link na-v2-link--light" href="' . esc_url($reviews) . '" rel="noopener noreferrer">Read the Google reviews &rarr;</a>'
            . '</div>';
    }

    /**
     * Footer review line. The star rating is published only once a verified Google
     * Business Profile URL exists, so no rating is implied before there are reviews.
     */
    public static function footer_reviews(): string
    {
        $reviews = trim(NA_Settings::get('google_reviews_url'));

        if ('' === $reviews) {
            return '';
        }

        return '<a href="' . esc_url($reviews) . '" rel="noopener noreferrer">Read our verified Google reviews</a>';
    }

    /**
     * Persistent adviser dock.
     *
     * Rendered on wp_footer rather than inside the footer template part: it is a
     * site-wide call to action, not footer content, so it does not belong inside the
     * contentinfo landmark. Rendering here also keeps it clear of the core shortcode
     * block, which runs wpautop before do_shortcode and mangled this markup.
     */
    public static function render_dock(): void
    {
        echo '<div class="na-v2-dock" data-na-dock>'
            . self::adviser_image('na-v2-dock__photo', 'na-v2-dock__plate')
            . '<div class="na-v2-dock__text"><strong>Talk to Ollie</strong><span>Book a callback time</span></div>'
            . '<a class="na-v2-dock__cta" href="' . esc_url(home_url('/enquire/')) . '">Book</a>'
            . '<button type="button" class="na-v2-dock__dismiss" data-na-dock-dismiss aria-label="Hide the adviser shortcut">&times;</button>'
            . '</div>';
    }

    /**
     * Retained so any page still carrying the shortcode keeps working.
     */
    public static function dock(): string
    {
        ob_start();
        self::render_dock();
        return (string) ob_get_clean();
    }

    /**
     * The adviser profile on the About page: a credentials list beside the biography.
     *
     * Advice-status claims (market scope, independence, remuneration) are regulated
     * statements, so they are shown only once compliance has supplied an FCA reference
     * through the launch controls. The biography itself comes from the approved
     * `ollie_bio` setting and is never substituted with placeholder prose.
     */
    public static function ollie_profile(): string
    {
        $reference = trim(NA_Settings::get('fca_reference'));
        $bio = trim(NA_Settings::get('ollie_bio'));

        $since = trim(NA_Settings::get('adviser_since'));
        $permissions = trim(NA_Settings::get('adviser_permissions'));

        // Experience, permissions and advice status are all regulated credential
        // claims. Each is published only from its own approved setting, and omitted
        // entirely otherwise: a "confirmed at launch" placeholder is itself a claim.
        $facts_list = '';

        if ('' !== $since) {
            $facts_list .= '<div class="na-v2-fact"><strong>' . esc_html($since) . '</strong><span>Running protection advice at Major Money Matters</span></div>';
        }

        if ('' !== $permissions) {
            $facts_list .= '<div class="na-v2-fact"><strong>Personal and business protection</strong><span>' . esc_html($permissions) . '</span></div>';
        }

        if ('' !== $reference) {
            $facts_list .= '<div class="na-v2-fact"><strong>Advice status</strong><span>Published under FCA reference ' . esc_html($reference) . '</span></div>';
        }

        $facts = '' !== $facts_list ? '<div class="na-v2-facts">' . $facts_list . '</div>' : '';

        $body = '' !== $bio
            ? '<div class="na-v2-prose">' . wp_kses_post(wpautop($bio)) . '</div>'
            : '<div class="na-v2-prose">' . self::pending('Ollie Allen approved biography') . '</div>';

        return '<div class="na-v2-profile"><div>'
            . '<h2>Protection as a specialism, not an afterthought.</h2>'
            . $facts
            . '</div>' . $body . '</div>';
    }

    private static function pending(string $item): string
    {
        if (current_user_can('manage_options')) {
            return '<div class="na-admin-only"><strong>Launch control:</strong> ' . esc_html($item) . ' has not been supplied. Add only approved wording in Settings, then Nest Assured.</div>';
        }

        return '<div class="na-note"><p>This information will be published before the service opens for production enquiries.</p></div>';
    }
}
