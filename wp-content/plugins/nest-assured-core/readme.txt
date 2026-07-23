=== Nest Assured Core ===
Contributors: nestassured
Tags: protection, enquiry, routing, accessibility
Requires at least: 6.7
Tested up to: 7.0
Requires PHP: 8.1
Stable tag: 1.5.2
License: GPL-2.0-or-later

Secure enquiry routing and advice-led guidance for the Nest Assured website.

== Description ==

Nest Assured Core provides:

* A guided needs assessment that never generates a quote or personal recommendation.
* Separate existing-client and new-enquiry routes.
* Administrator-only WordPress enquiry storage with explicit consent records.
* Adviser queue matching by approved routing map.
* Signed JSON webhook delivery with event IDs and delivery diagnostics.
* Optional SMTP-delivered notifications and confirmations.
* Booking, Google Reviews, approved FAQ, adviser photograph and compliance-owned content controls.
* Configurable daily deletion of enquiries after an approved retention period.
* An idempotent WP-CLI installer for the required page architecture.

== Installation ==

1. Activate the plugin.
2. Run `studio wp nest-assured install` from the Studio site root.
3. Open Settings, then Nest Assured to configure approved production values.
4. Connect WP Mail SMTP to a transactional provider before enabling confirmations.
5. Test the CRM webhook, adviser routes and booking diary before launch.

== Personal data ==

Enquiries are stored as private `na_enquiry` posts. The form records contact details, client route, adviser-routing information, submission time and consent. The settings page controls delivery to the approved CRM and notification address. Retention periods must be agreed with compliance before production use.

== Shortcodes ==

* `[nest_assured_enquiry]`
* `[nest_assured_enquiry mode="existing"]`
* `[nest_assured_assessment]`
* `[nest_assured_booking]`
* `[nest_assured_regulatory]`
* `[nest_assured_complaints]`
* `[nest_assured_financial]`
* `[nest_assured_ollie]`
* `[nest_assured_faqs]`
* `[nest_assured_reviews]`
* `[nest_assured_prelaunch_note]`

== Changelog ==

= 1.5.2 =

* Matched each assessment rationale to the answer actually chosen.
* Made the phone number required only when phone is the chosen contact method, on both client and server.
* Added an announced error summary to failed enquiry submissions.
* Darkened the focus outline to meet the 3:1 non-text contrast target on light and navy surfaces.
* Strengthened the mobile menu scrim, locked page scroll while it is open and let a tall panel scroll internally.
* Stopped the installer touching unchanged pages so guide review dates only move when content genuinely changes.

= 1.5.1 =

* Repaired the mobile menu scrim by moving the header blur to a pseudo-element.
* Corrected the footer column wrap at wide widths and reinforced the footer wordmark colour.
* Added a calm three-step "what happens after you send it" timeline to the enquiry page.

= 1.5.0 =

* Fixed the collapsed cross-link cards on cover pages and the mobile layout of the wide family-protection card.
* Restored column context on stacked mobile comparison tables with per-cell labels.
* Removed the error styling shown on untouched enquiry forms and added persistent inline validation messages.
* Blanked opposite-branch fields before storing an enquiry to minimise personal data held.
* Made the retention task drain backlogs in bounded batches and kept the security plugin active on form-page views.
* Added an automatic content upgrade when the plugin version changes, current-page navigation states, an assessment restart control and a launch-gated footer status note.
* Refined public copy, aligned the brand line and completed the intent-matched search titles for all product pages.

= 1.4.0 =

* Added seven authority-led guides covering claims, insurance language, self-employed income protection, business cover, company medical schemes, trusts and adviser preparation.
* Added guide authorship, review dates, reading times, Article structured data and branded social-share metadata.
* Reduced enquiry friction with conditional phone validation and an explicit unknown-adviser route.
* Added privacy-conscious conversion events, mobile comparison cards and reduced-motion-safe reveal animation.
* Added launch gating, privacy controls, legal noindex rules, cached security-header support and an enforcing frontend content security policy.

= 1.3.0 =

* Added private medical insurance, business protection and general insurance routes.
* Added a protection guide hub with six supporting search-intent guides.
* Expanded product education, internal links, SEO metadata and enquiry topic routing.
* Added the polished cover navigation, guide components and accessible motion system.

= 1.2.1 =

* Added the approved Ollie Allen profile and responsive adviser photography.
* Improved the long-form adviser profile layout on larger screens.
* Reflowed the footer at 200% text size and removed repeated media lookups.

= 1.1.0 =

* Restricted enquiry access, strengthened webhook delivery and added retention controls.
* Added accessible assessment validation and preserved topic context into the enquiry form.
* Added approved FAQ and adviser-photograph launch controls.

= 1.0.0 =

* Initial Nest Assured build.
