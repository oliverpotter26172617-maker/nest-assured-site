=== Nest Assured Core ===
Contributors: nestassured
Tags: protection, enquiry, routing, accessibility
Requires at least: 6.7
Tested up to: 6.8
Requires PHP: 8.1
Stable tag: 1.6.5
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
* `[nest_assured_adviser]`
* `[nest_assured_assurance]`
* `[nest_assured_social_proof]`
* `[nest_assured_privacy]`
* `[nest_assured_legal_links]`
* `[nest_assured_footer_reviews]`
* `[nest_assured_footer_regulatory]`
* `[nest_assured_contact_details]`
* `[nest_assured_copyright]`
* `[nest_assured_dock]`
* `[nest_assured_ollie_profile]`

Gated shortcodes render nothing at all until the matching approved value exists in
the launch controls. They never substitute placeholder wording, because a line such
as "FCA reference published at launch" is itself an unapproved statement of
regulatory status.

== Changelog ==

= 1.6.5 =

* Compliance: gated shortcodes now omit content entirely rather than publishing "published at launch" placeholders, which were themselves unapproved statements of regulatory status. Adviser experience and permissions moved behind approved settings.
* Compliance: removed the homepage premium-by-age chart (its figures were not sourced) and the "Reviewed by Ollie Allen" credit stamped on thirteen guides and in `Article` structured data, where no review had been recorded. Both now read per-guide meta.
* Compliance: indexing fails closed. Nothing is indexable until approved copy exists and a named person has recorded a sign-off, which is withdrawn automatically whenever approved wording changes.
* Compliance: warns in wp-admin when stored copy contains regulated status terms such as "independent" or "whole of market".
* Enquiries: added a delivery fallback chain, an honest "pending" status, failure logging and an admin notice. A stored enquiry that reaches nobody is no longer reported to the visitor as received.
* Enquiries: the form is gated in every environment, is never cached, records what wording was consented to, and works without JavaScript.
* Data protection: registered the personal-data exporter and eraser, added removal cleanup, widened the retention job to trashed records, and stopped configuring unencrypted local backups containing client data.
* Accessibility: guide articles publish a real `h1`; reading time is computed with a multibyte-safe count.
* Reliability: the installer takes an atomic lock, stamps its version last, and detects pages edited in wp-admin instead of trusting only its own hash. Settings are merged on save so a partial write cannot wipe approved copy.
* Structured data: the organisation is described as a `FinancialService` with a postal address, and guide breadcrumbs include the Guides level.

= 1.5.5 =

* Rebuilt the site header and footer to the v2 direction, including the persistent adviser dock (hidden on small screens, where the sticky header carries the same call to action).
* Rebuilt the About, Life insurance, Protection guides and Enquire pages to the v2 direction.
* Added `[nest_assured_ollie_profile]`, so advice-status claims appear only once compliance has supplied an FCA reference, and the biography comes only from the approved setting.
* Added `[nest_assured_footer_reviews]` and `[nest_assured_dock]`.
* Suppressed the template post-title on v2 pages, which now carry their own single h1.
* Kept the live enquiry form and restyled it in place; no change to enquiry storage, routing, consent records or webhook delivery.
* Brought the thirteen guide articles into the v2 look through styling and a reading-progress bar only, leaving their published prose unchanged.

= 1.5.4 =

* Rebuilt the home page to the Home v2 editorial direction.
* Kept the existing Major Money Matters client route on the home page as a dedicated band, so adviser-route enquiries stay separate from the new-enquiry queue.
* Added `[nest_assured_adviser]` so adviser photography is published only from the approved Settings value, with a monogram plate until then.
* Added `[nest_assured_assurance]` so the FCA reference and Google reviews link appear only once approved values exist.
* Added `[nest_assured_social_proof]` so the review slot never carries an unverified quotation.
* Darkened the gold used for numerals and link underlines on light surfaces to #8a6a33, so home-page text and link affordances clear the 4.5:1 and 3:1 contrast targets.

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

* Added the adviser profile mechanism and responsive photography handling. The profile renders only approved content supplied through the launch controls; no approved biography ships with the plugin.
* Improved the long-form adviser profile layout on larger screens.
* Reflowed the footer at 200% text size and removed repeated media lookups.

= 1.1.0 =

* Restricted enquiry access, strengthened webhook delivery and added retention controls.
* Added accessible assessment validation and preserved topic context into the enquiry form.
* Added approved FAQ and adviser-photograph launch controls.

= 1.0.0 =

* Initial Nest Assured build.
