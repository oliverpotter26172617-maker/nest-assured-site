# Nest Assured WordPress build audit

Audit date: 22 July 2026  
Environment: WordPress Studio, `http://localhost:8886/`  
Build cycles completed: 6

## Outcome

The local WordPress build is complete. It provides a calm, advice-led public site with separate existing-client and new-enquiry routes, a guided discussion-topic assessment, secure enquiry storage and integration controls, product education, legal holding pages, caching, SEO, security, backups and transactional-email configuration points.

Production launch remains intentionally controlled by external business inputs listed below. No legal identifiers, testimonials, FAQs, diary addresses, CRM destinations or compliance wording have been invented. Ollie Allen's supplied biography and consented portrait are now integrated.

## Cycle 1: existing-install audit

The supplied site was a stock WordPress 7.0.2 installation using Twenty Twenty-Five. It contained the default Hello World post, Sample Page and draft Privacy Policy. No existing Nest Assured theme, funnel, adviser biography or protection-site build was present. The brief's statement about an existing placeholder biography did not match the inspected installation.

These scores describe the inspected state. They are rubric judgements, not Lighthouse results.

| Category | Score | Evidence |
|---|---:|---|
| Design and visual craft | 2/10 | Stock theme and default content only |
| UX and conversion flow | 0/10 | No product or audience routes |
| Accessibility | 6/10 | Accessible core theme foundations, but no required journeys to verify |
| Performance | 5/10 | No production cache or project performance configuration |
| Regulatory tone | 0/10 | No protection content or advice-led framing |
| Compliance readiness | 0/10 | No FCA, complaints or financial-promotions pages |
| Content integrity | 4/10 | No fabricated content found, but required approved content was absent |
| Code quality | 4/10 | Maintained core theme, but no project implementation or tests |

Cycle 1 was blocked in every project-specific category, so the stock site was not retained as the public design.

## Cycle 2: completed build

| Category | Score | Evidence |
|---|---:|---|
| Design and visual craft | 9/10 | Custom lightweight block theme, editorial navy, gold and cream system, responsive live wordmark and restrained bird mark |
| UX and conversion flow | 10/10 | Structurally different existing-client and new-enquiry branches, adviser-name routing, product preselection and one content CTA per product page |
| Accessibility | 10/10 | Lighthouse 100, semantic browser snapshot, keyboard focus treatment, 200% text check with no horizontal overflow, reduced-motion handling and zero console warnings |
| Performance | 10/10 | Home Lighthouse 100 with LCP 1.1 s, CLS 0 and TBT 0 ms. Enquiry Lighthouse 100 with LCP 1.3 s, CLS 0 and TBT 0 ms |
| Regulatory tone | 10/10 | Advice-led throughout, no fear framing, online purchase, positive quote promise, urgency or guaranteed outcome |
| Compliance readiness | 9/10 | Required legal pages and configurable compliance fields are present, clearly held from launch until approved copy and identifiers are supplied |
| Content integrity | 10/10 | No fabricated biography, testimonial, case study, FAQ, review, legal identifier or business statistic |
| Code quality | 9/10 | Custom plugin passes WordPress Plugin Check with no errors, critical PHP parses under the Studio runtime, no browser console errors, narrow active plugin set |

All cycle 2 scores meet the minimum 8/10 threshold. The local build cycle is complete.

## Cycle 3: security, accessibility and operational improvement audit

| Category | Score | Evidence |
|---|---:|---|
| Design and visual craft | 9/10 | Desktop and mobile visual inspection found no regression or template-default treatment |
| UX and conversion flow | 10/10 | Assessment result now preserves the selected topic into the new-enquiry branch, with in-context form preselection |
| Accessibility | 10/10 | Missing assessment answers now produce a visible announced alert, focus moves correctly, field hints are programmatically associated and Lighthouse remains 100 |
| Performance | 10/10 | Home remains Lighthouse 100. Read-only form rendering stays lean while the security plugin remains active for administration and every form POST |
| Regulatory tone | 10/10 | No copy or route changed the advice-led boundary |
| Compliance readiness | 9/10 | Approved FAQ, adviser photograph and retention-policy controls can now be supplied without code changes |
| Content integrity | 10/10 | New controls remain empty until approved information is provided |
| Code quality | 10/10 | Plugin Check remains clean, controlled values are allow-listed, CRM delivery is signed and auditable, and personal records are restricted to administrators |

Cycle 3 improvements:

- Restricted enquiry-record capabilities to users with `manage_options`; ordinary editors cannot access personal enquiry data.
- Required the configured CRM signing secret before webhook delivery and changed delivery to `wp_safe_remote_post` with event ID, signature type and non-sensitive delivery diagnostics.
- Suppressed customer confirmation emails unless an adviser email or CRM delivery was accepted first.
- Added an approved retention-days control and a tested daily deletion task for expired enquiries.
- Added controls for approved FAQ copy and consented Ollie Allen photography.
- Made Google reviews optional while retaining Google as the only supported reviews source.
- Added dynamic-route security headers. All In One WP Security remains active for administration and every form POST; only read-only form rendering skips its back-office bootstrap.
- Removed duplicate document titles caused by the WordPress 7 block-template renderer running alongside Yoast.
- Blocked unauthenticated REST user enumeration and fully disabled the unused XML-RPC endpoint.
- Removed unused feed, RSD, shortlink and oEmbed discovery tags from the public document head.
- Stopped loading assessment and form assets on pages that do not use them.
- Added accessible assessment validation and carried the assessment topic into the enquiry query string.

## Cycle 4: approved adviser profile integration

| Category | Score | Evidence |
|---|---:|---|
| Design and visual craft | 10/10 | Supplied portrait is presented in the established visual system, aligned to the start of the long-form biography on larger screens and stacked cleanly on mobile |
| UX and conversion flow | 10/10 | The About page now gives visitors an identifiable adviser and a detailed explanation of the advice approach without adding a competing action |
| Accessibility | 10/10 | The image has the accessible name `Ollie Allen, protection adviser`, the heading structure is intact and browser inspection found no horizontal overflow at standard sizes |
| Performance | 10/10 | The 18,210-byte WebP is delivered through WordPress responsive image markup with 150, 300 and 500-pixel candidates |
| Regulatory tone | 10/10 | The approved biography was installed verbatim and no additional claims were generated |
| Compliance readiness | 9/10 | Biography and photography inputs are resolved; the factual and regulated claims remain subject to final human compliance approval before production |
| Content integrity | 10/10 | Exact supplied paragraph breaks and the exact `Ollie Allen` name are preserved |
| Code quality | 10/10 | Media ID is stored once, avoiding a repeated URL-to-attachment database lookup on every About page request |

## Cycle 5: final accessibility and cache audit

| Category | Score | Evidence |
|---|---:|---|
| Design and visual craft | 10/10 | Final 1440-pixel and 390-pixel screenshots show balanced profile, regulatory and footer layouts |
| UX and conversion flow | 10/10 | All 15 required routes remain available and the missing-route control returns HTTP 404 |
| Accessibility | 10/10 | Final Lighthouse accessibility score is 100; the footer now reflows without horizontal overflow at 200% text size |
| Performance | 10/10 | Corrected the trailing-slash page-cache setting; warm About and product pages now return in about 0.05 to 0.11 seconds locally |
| Regulatory tone | 10/10 | Full rendered-page scan remains clear of em dashes, the incorrect adviser name, Trustpilot references and fear-based urgency phrases |
| Compliance readiness | 9/10 | Site controls continue to expose only approved-input fields and clearly show the remaining external blockers |
| Content integrity | 10/10 | All public pages render exactly one document title and no placeholder biography remains |
| Code quality | 10/10 | Final Plugin Check reports no errors; active custom theme and plugin are version 1.2.1 |

The original five-cycle build was complete. Cycle 6 was authorised as a separate competitive, product and organic-search expansion.

## Cycle 6: competitive expansion, new products and SEO library

| Category | Score | Evidence |
|---|---:|---|
| Design and visual craft | 10/10 | New hero composition, soft depth, six-card product system, accessible cover menu, guide components and restrained hover motion render cleanly at 1440 and 390 pixels |
| UX and conversion flow | 10/10 | Seven product routes feed one allow-listed enquiry system; selected PMI, business and general-insurance topics arrive preselected in the new-enquiry branch |
| Accessibility | 10/10 | Final Lighthouse accessibility is 100, the mobile page has no horizontal overflow, reduced-motion rules remain active and the browser console is clear |
| Performance | 10/10 | Warm-cache home Lighthouse performance is 100 with LCP 1.3 s, CLS 0 and TBT 0 ms; the business guide also scores 100 with LCP 1.1 s |
| Regulatory tone | 10/10 | New content remains educational and advice-led, with no quote promise, urgency, invented provider relationship, tax guarantee or online purchase route |
| Compliance readiness | 9/10 | Business and PMI qualifications are explicit, legal and tax advice boundaries are stated, and the original approved-input launch controls remain unchanged |
| Content integrity | 10/10 | Competitor claims were researched but not copied; no customer totals, reviews, awards, insurer panel or claims statistics were invented |
| Code quality | 10/10 | Version 1.3.0 passes WordPress Plugin Check with no errors; 21 core routes return HTTP 200 and a deliberate missing route returns 404 |

Cycle 6 improvements:

- Benchmarked Reassured, LifeSearch and Pure Protection across product breadth, content architecture, trust patterns and conversion model. The detailed review is recorded in `COMPETITOR-BENCHMARK.md`.
- Added private medical insurance, business protection and home/general insurance product pages.
- Added a protection guide hub and six supporting guides targeting distinct, non-competing search intents.
- Expanded the original life, income, critical illness and family pages with comparisons, policy-shaping details and connected guides.
- Added descriptive SEO titles, meta descriptions, canonical URLs and deliberate noindex treatment for transactional form routes and the empty FAQ holding page.
- Added a compact desktop cover menu and expanded mobile and footer navigation.
- Added friendly visual polish with gradients, orbit details, numbered cards, comparison tables, timelines, preparation checklists and responsive guide layouts.
- Added restrained button, card, menu and hero hover motion while preserving reduced-motion handling and zero layout shift.
- Extended enquiry validation and storage allow-lists for PMI, business protection and general insurance.

## Implemented architecture

- Custom Full Site Editing theme at `wp-content/themes/nest-assured/`.
- Custom functionality plugin at `wp-content/plugins/nest-assured-core/`.
- Performance router at `wp-content/mu-plugins/na-performance-router.php` to keep dynamic form routes lean while retaining site-wide operational plugins.
- Static `robots.txt` and Yoast XML sitemap support.
- Published home, seven product routes, a six-guide SEO library, existing-client, process, FAQ, about, contact, enquiry and legal pages.
- Exactly one content action at the end of each product page.
- Three-question assessment that suggests only a discussion topic and shows the required disclaimer.
- Existing-client enquiry branch captures the mortgage adviser and journey context, then resolves configured adviser queues before using the unmatched-adviser route.
- New-enquiry branch routes separately to the protection-team configuration.
- Enquiries are private WordPress records with consent time, routing status and protected admin detail views.
- Optional CRM webhook uses an HMAC SHA-256 signature.
- Booking embed, adviser mapping, CRM, Google review and compliance controls are managed in the Nest Assured settings screen.
- Approved FAQ, adviser photograph and enquiry-retention controls are managed in the same launch-control screen.
- Ollie Allen's supplied biography and approved portrait are rendered on the About page with responsive media-library image output.
- Transactional email remains disabled until a real WP Mail SMTP provider is connected.

## Operational configuration

- Active theme: Nest Assured.
- Active custom theme and plugin version: 1.4.0.
- Active operational plugins: All In One WP Security, Nest Assured Core, UpdraftPlus, WP Mail SMTP, WP Super Cache and Yoast SEO.
- WP Super Cache is active. Dynamic enquiry routes are excluded to protect nonces and branch state.
- Warm cached responses measured at approximately 0.05 to 0.11 s locally after correcting the trailing-slash cache configuration.
- UpdraftPlus schedule: database daily with seven retained copies, files weekly with two retained copies.
- Language: `en_GB`.
- Permalinks: post-name structure.
- Static front page: page ID 6.
- Site icon: media ID 36.

## Verification evidence

- All 15 required public routes returned HTTP 200.
- A deliberately missing route returned HTTP 404.
- Product query routing preselected both the new-enquiry branch and requested product.
- Existing-client form branch was submitted successfully in-browser, stored with its dedicated routing status, and the synthetic test record was then removed.
- Public rendered-page scan found no em dash, Trustpilot reference, incorrect adviser name, fear phrase, buy-now wording or guaranteed-outcome wording.
- The phrase `instant quote` appears only in explicit negative disclosures that state the site does not provide one.
- Home isolated Lighthouse: Performance 100, Accessibility 100, Best Practices 100, SEO 100, FCP 1.1 s, LCP 1.1 s, CLS 0, TBT 0 ms, root response 90 ms.
- Enquiry isolated Lighthouse: Performance 100, Accessibility 100, Best Practices 100, SEO 100, FCP 1.3 s, LCP 1.3 s, CLS 0, TBT 0 ms, root response 1.15 s.
- WordPress Plugin Check: `Success: Checks complete. No errors found.`
- Mobile browser accessibility snapshot names both wordmark links correctly and reports zero console errors or warnings.
- Cycle 3 final home Lighthouse: Performance 100, Accessibility 100, Best Practices 100, SEO 100, FCP 1.1 s, LCP 1.1 s, CLS 0, TBT 0 ms, root response 110 ms.
- Cycle 3 final enquiry Lighthouse: Performance 99, Accessibility 100, Best Practices 100, SEO 100, FCP 1.3 s, LCP 1.5 s, CLS 0, TBT 0 ms, root response 1.76 s.
- Assessment validation was verified in the browser accessibility tree as an alert, and its final CTA preselected the correct enquiry topic.
- Synthetic delivery testing stored `protection-team`, `stored-pending-integration`, `no-configured-delivery-channel` and the selected product correctly. The synthetic record was permanently removed afterwards.
- Retention testing permanently removed only the deliberately backdated synthetic record and restored the original empty settings state.
- Yoast pages and custom fallback-meta pages each render exactly one document title.
- Unauthenticated `/wp-json/wp/v2/users` requests return HTTP 404 and XML-RPC requests return HTTP 403.
- The supplied 500 by 500-pixel portrait was exported faithfully as an 18,210-byte WebP without retouching and returns HTTP 200 with `image/webp` content type.
- The About profile emits WordPress `srcset` candidates at 150, 300 and 500 pixels, has no placeholder biography and retains exactly one document title.
- Final About Lighthouse: Performance 100, Accessibility 100, Best Practices 100, SEO 100, FCP 0.9 s, LCP 0.9 s, CLS 0, TBT 0 ms, root response 80 ms.
- A 200% text-size browser test at a 1280-pixel viewport found no horizontal overflow after the footer grid correction.
- Final full-site scan: all 15 required routes returned HTTP 200, a deliberately missing route returned HTTP 404 and every public route rendered exactly one document title.
- Cycle 6 route scan: all 21 expanded core routes returned HTTP 200 and a deliberately missing route returned HTTP 404.
- Cycle 6 home Lighthouse: Performance 100, Accessibility 100, Best Practices 100, SEO 100, FCP 1.2 s, LCP 1.3 s, CLS 0 and TBT 0 ms.
- Cycle 6 business-guide Lighthouse: Performance 100, Accessibility 100, Best Practices 100, SEO 100, FCP 1.0 s, LCP 1.1 s, CLS 0 and TBT 0 ms.
- The new business-protection query route preselects both the new-enquiry branch and Business protection option in the browser accessibility tree.
- Empty FAQ, new-enquiry and existing-client routes render `noindex, follow`; substantive product and guide pages remain indexable with unique titles, descriptions and canonicals.
- WordPress Plugin Check after the 1.3.0 expansion reports `Success: Checks complete. No errors found.`
- Cycle 7 authority and conversion expansion: seven new long-form guides, an editorial policy, visible authorship and review information, official references, Article structured data and a 1200 by 630-pixel branded share image.
- Cycle 7 route scan: all 26 indexable sitemap URLs returned HTTP 200. Unapproved legal pages are noindexed and excluded from the sitemap.
- Cycle 7 enquiry testing: email-preference submission succeeded without a phone number, the unknown-adviser control disabled the adviser-name field correctly and the single synthetic record was permanently removed. The enquiry store is empty.
- Cycle 7 mobile rendering: general-insurance and business-comparison tables stack into readable cards at 390 pixels with no browser console errors or warnings.
- Cycle 7 home Lighthouse: Performance 92, Accessibility 100, Best Practices 100, SEO 100, FCP 1.1 s, LCP 1.4 s, CLS 0 and TBT 0 ms. The lower performance score is isolated to a delayed headless Speed Index filmstrip; user-facing paint, layout and blocking metrics remain healthy.
- Cycle 7 guide Lighthouse: Performance 100, Accessibility 100, Best Practices 100, SEO 100, FCP 1.0 s, LCP 1.2 s, CLS 0 and TBT 0 ms.
- Cycle 7 WordPress core checksums pass and Plugin Check reports `Success: Checks complete. No errors found.`
- Cached public responses now retain the content security policy, nosniff, frame, referrer and permissions headers. PHP version disclosure is removed from the verified response.

## Production launch controls

These are external inputs, not unfinished site code:

1. Complete human compliance approval of the supplied biography claims and confirm the recorded photograph consent.
2. Supply the approved FCA reference, regulatory status, privacy notice, complaints procedure and financial-promotions wording.
3. Supply real FAQs sourced from adviser conversations.
4. Confirm the CRM webhook URL and a strong signing secret.
5. Confirm the adviser-name to queue or mailbox map, including the unmatched-adviser owner.
6. Supply the live protection-adviser diary URL.
7. Connect WP Mail SMTP to an approved transactional email provider and send a deliverability test.
8. Supply the verified Google Business Profile reviews URL if reviews are to be shown.
9. Connect UpdraftPlus to approved off-site storage and complete a restore test.
10. Supply the approved enquiry-record retention period.
11. Configure the production CDN, host cache headers, final domain and TLS, then rerun the Lighthouse audits on the public origin.

The site should remain in controlled pre-launch status until items 1 to 7, 9 and 10 are complete. Item 8 is optional. Item 11 is completed during deployment.

## Rollout recommendation

- Weeks 1 to 4: compliance-approved soft launch through the existing M3M client route only.
- Weeks 5 to 8: compare attach-rate outcomes with the confirmed internal baseline and adjust adviser routing from real results.
- Weeks 9 to 12: open direct and organic discovery only after the existing-client route is proven operational.

No internal adviser portal or attach-rate tracker was added to this public WordPress site.

## Generated brand asset

Active web asset: `wp-content/themes/nest-assured/assets/images/nest-assured-bird-256.webp`.

Generation mode: background extraction from the supplied brand-board reference. The prompt requested a faithful redraw of only the simple top-right navy outline bird, centred on a flat chroma-key background, with no text, circle, gold, shadow or decoration. The generated background was removed locally and the transparent artwork was exported as WebP for the live site.
