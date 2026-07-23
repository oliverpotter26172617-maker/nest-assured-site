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
    }

    /**
     * @param array<string, mixed> $atts Shortcode attributes.
     */
    public static function enquiry(array $atts = []): string
    {
        if ('production' === wp_get_environment_type() && ! NA_Settings::is_launch_ready()) {
            return '<div class="na-status"><h2>Online enquiries are not open yet</h2><p>The secure adviser-routing and approved privacy controls are being completed before this form is made available.</p></div>';
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
        <form class="na-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" data-na-enquiry novalidate>
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

            <fieldset class="na-form__branch" data-branch="existing" hidden>
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

            <fieldset class="na-form__branch" data-branch="new" hidden>
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
                    <span>I consent to Nest Assured using these details to respond to my enquiry and route it to the appropriate adviser. I have read the <a href="/legal/privacy/">privacy information</a>.</span>
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
            return '<div class="na-card na-team-card"><div class="na-team-card__portrait" aria-hidden="true">OA</div><div><p class="na-eyebrow">Protection adviser</p><h2>Ollie Allen</h2><p>Ollie Allen leads protection advice at Nest Assured and reviews every guide for accuracy.</p><div class="na-status"><p>Approved biography and photography are required before launch. No placeholder biography has been generated.</p></div></div></div>';
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

    public static function legal_links(): string
    {
        $links = ['<a href="/legal/privacy/">Privacy</a>'];
        if ('' !== trim(NA_Settings::get('complaints_copy'))) {
            $links[] = '<a href="/legal/complaints-procedure/">Complaints procedure</a>';
        }
        if ('' !== trim(NA_Settings::get('financial_copy'))) {
            $links[] = '<a href="/legal/financial-promotions/">Financial promotions</a>';
        }

        return '<nav class="site-footer__links" aria-label="Legal information">' . implode('', $links) . '</nav>';
    }

    public static function prelaunch_note(): string
    {
        if (NA_Settings::is_launch_ready()) {
            return '';
        }

        return '<p>Regulatory wording is subject to compliance approval before launch.</p>';
    }

    private static function pending(string $item): string
    {
        if (current_user_can('manage_options')) {
            return '<div class="na-admin-only"><strong>Launch control:</strong> ' . esc_html($item) . ' has not been supplied. Add only approved wording in Settings, then Nest Assured.</div>';
        }

        return '<div class="na-note"><p>This information will be published before the service opens for production enquiries.</p></div>';
    }
}
