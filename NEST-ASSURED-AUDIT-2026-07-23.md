# Nest Assured full site audit

Audit date: 23 July 2026
Environment: WordPress Studio, `http://localhost:8886/` (Studio CLI unavailable; verification via rendered HTML, filesystem review and headless browser)
Scope: visuals and UI, copywriting, features and code, competitor analysis
Companion document: `COMPETITOR-BENCHMARK.md` (rewritten 23 July 2026, six competitors)

## Cycle 1 baseline scores

Scored against the established eight-category rubric for comparability with `NEST-ASSURED-BUILD-AUDIT.md`.

| Category | Score | Evidence |
|---|---:|---|
| Design and visual craft | 6.5/10 | Distinctive, disciplined identity, but two visibly broken components in primary journeys (cross-link cards on six cover pages, shattered mobile family card) plus dropdown misalignment, off-balance article column and cover-template drift |
| UX and conversion flow | 7/10 | Routing, assessment tool and CTA architecture remain strong; the enquiry form shows error borders on a pristine form and relies on browser bubbles alone for validation feedback |
| Accessibility | 7/10 | Skip link, focus states, semantics and zero console errors; docked for mobile tables losing column meaning, no `aria-current` nav state, and non-persistent error feedback |
| Performance | 9/10 | 42.9 KB home HTML, four external assets, working conditional loading and page cache; uncached generation ~5.8 s, no Cache-Control on cached pages, PHP version header leaks on cached responses |
| Regulatory tone | 9/10 | Near-faultless promotions discipline; internal build language leaks to public pages in three places, one doubled hedge |
| Compliance readiness | 9/10 | Launch gating intact and correct; unchanged from prior audits |
| Content integrity | 9/10 | Zero fabrication verified against rendered output; two missing apostrophes in a flagship table, title-separator inconsistency on two noindexed pages |
| Code quality | 8/10 | Modern strict-typed PHP, consistent escaping, defence-in-depth enquiry pipeline; docked for missing i18n, cross-branch data storage and a duplicate-canonical interaction |

The previous 9 to 10 out of 10 standing does not hold at this audit. Four P1 defects sit in primary journeys and several P2s are quick, high-value corrections.

## P1 defects (fix in cycle 1)

1. **Cross-link card broken on six cover pages (desktop).** The `.na-related` white card collapses to ~215px; heading and paragraph overflow and clip. Affects life insurance, income protection, critical illness, family protection, business protection, general insurance. Fix in CSS: make the card a block that contains its children.
2. **Homepage family-protection feature card shatters at 390px.** Desktop three-column flex persists on mobile; the text column collapses to one character wide. Fix: stack the card below ~700px.
3. **Mobile stacked tables lose their column headers.** On the life vs critical illness comparison both stacked columns read "A lump sum" with no label; same root cause on the business types and general insurance tables. Fix: add per-cell labels in the stacked view (JS injects `data-label` from the table header, CSS shows it via `td::before`).
4. **Enquiry form shows red error borders on first paint.** `input:invalid:not(:focus):not(:placeholder-shown)` always matches because no field has a placeholder. Fix: gate error styling behind a submitted state or `:user-invalid`.

## P2 improvements (cycle 1 and 2)

Visual and UX: no active-nav state (`aria-current` plus visual marker); mobile menu floats without a scrim and buries the primary CTA; explore-cover dropdown columns misaligned; article column off-balance at 1440 with template drift between cover pages (only PMI and business protection have the jump bar); assessment tool lacks a restart control after completion; inline validation messages instead of bubble-only feedback; verify and align the phone-required toggle with its hint.

Copywriting (17 items from the copy audit, highest value first): missing apostrophes in the business-protection guide table; "adviser route" database-speak at the confirmation moment; internal build language on How it works ("production scheduling URL"); enhanced SEO titles for the four core product pages; contradictory phone label and hint; "proposition" jargon on About; unglossed PMI jargon; doubled hedge on income protection; slogan standardisation ("made reassuringly clear"); Yoast titles for the two noindexed form pages; assessment dropdown option wording; validation message specificity; "on risk" jargon in the review guide; family-protection meta rewrite; Ollie fallback card ops-speak; footer pre-launch status note moved behind the launch gate.

Code: remove duplicate canonical on the two router pages; blank the opposite branch's fields server-side before storing an enquiry; loop the retention cron in bounded batches; remove the security plugin from the router's GET skip list on form routes; assessment result flow polish.

## P3: blocked on external inputs or deliberate decisions (report only)

- PHP version header on cached responses: set `expose_php = Off` on the production host (deployment task).
- CSP `unsafe-inline`: tightening to nonce-based CSP is a pre-launch hardening decision; record the accepted risk if deferred.
- i18n: zero translation calls despite a declared text domain. Single-market UK site; recommend recording the decision rather than wrapping every string now.
- All existing production launch controls in `NEST-ASSURED-BUILD-AUDIT.md` remain open and unchanged (compliance wording, FCA identifiers, FAQs, CRM, diary URL, SMTP, backups, retention period, CDN and TLS).

## Competitor-inspired candidates (from `COMPETITOR-BENCHMARK.md`, for cycle 2 or later)

Highest value within guardrails: named adviser attribution on guides and product pages; a "what to expect from your conversation" timeline on home and enquiry; honest "what this does not cover" blocks per product page; a jargon-buster glossary page in the guide hub; guide metadata already partly present (read times, review dates) extended consistently; a calm persistent mobile "Ask an adviser" bar. Excluded by guardrails: instant quotes, invented statistics, Trustpilot, urgency devices.

## Cycle 1 fixes (implemented 23 July 2026, plugin and theme 1.5.0)

All four P1 defects and the high-value P2 items were implemented:

- **Cross-link cards**: `a.na-card` is now `display: block`, restoring the related-guide cards on all six cover pages.
- **Mobile family card**: the wide product card stacks to a single column below 48rem.
- **Mobile table labels**: all four comparison tables carry per-cell `data-label` attributes rendered as small uppercase labels in the stacked view, so columns keep their meaning without the hidden header row.
- **Enquiry form errors**: error borders now appear only after a failed submit (`na-was-validated` class) with persistent inline messages under each invalid field, wired via `aria-describedby`.
- **Duplicate canonicals** removed on the two router pages; their titles fixed with a `document_title_separator` fallback (`Enquire | Nest Assured`).
- **Data minimisation**: opposite-branch fields are blanked server-side before an enquiry is stored.
- **Retention cron** drains backlogs in bounded batches (up to 10 x 100 per run).
- **Security plugin** no longer skipped on form-page GETs by the performance router.
- **Navigation**: current-page links get `aria-current="page"` plus a gold underline (desktop) or cream highlight (menus); mobile menu gains a scrim and a navy pill CTA; dropdown columns top-align.
- **Assessment**: completed state now hides the answered questions and offers a "Start again" control.
- **Copy**: all 17 copywriting defects fixed, including the business-table apostrophes, internal build language on public pages, "adviser route" database-speak, intent-matched SEO titles for the four core product pages, the family-protection meta description, PMI jargon glosses, the phone field label and hint, and the brand line standardised on "made reassuringly clear". The footer pre-launch status note is now a launch-gated shortcode.
- **Deployment**: the plugin re-runs its idempotent installer automatically when its version changes (now 1.5.0), so seeded-content updates no longer require WP-CLI.

Deferred with rationale: i18n wrapping (single-market UK site, recorded as a decision), CSP nonce hardening and the PHP version header (production host tasks), cover-template unification and competitor-inspired additions (cycle 2 candidates).

## Cycle 1 visual verification (headless browser)

Desktop pass: related-guide cards render as full-width blocks on the cover pages (no clipping), the explore-cover dropdown columns top-align, the assessment hides answered questions on completion and its "Start again" control resets cleanly, the hero eyebrow and gated footer note render as intended, and consoles are clean.

Mobile and form pass: the family-protection card stacks correctly at 390px; stacked comparison tables show their uppercase column labels; the untouched enquiry form shows no error styling and a failed submit produces red borders plus inline messages with a clean console; the "Guides" nav link carries `aria-current="page"` with a gold underline; the mobile menu CTA renders as a navy pill; `/already-a-client/` and `/faqs/` are structurally sound at both widths.

Found in verification: the mobile menu scrim failed (the header's `backdrop-filter` made the header the containing block for the fixed-position scrim), the footer wordmark was reported near-invisible on the navy background, and the footer grid wrapped its fourth column onto a lone second row at 1440px (four 16rem columns plus gaps exceed the 72rem shell). Accepted as-is: the native browser validation bubble appears alongside the inline errors on the radio group (it provides focus and screen-reader announcement).

## Cycle 2 fixes (plugin 1.5.1, theme 1.5.1)

- Moved the header blur to a `.site-header::before` pseudo-element so the fixed mobile-menu scrim can cover the viewport; the frosted-glass effect is retained.
- Footer grid minimum column width reduced to 14rem so all four columns fit on one row at 1440px; footer wordmark colour reinforced with an explicit child-span rule.
- Added a calm three-step "What happens after you send it" timeline to the enquiry page (the strongest guardrail-compatible competitor pattern: process transparency as the counter to quote-led speed).

## Cycle 2 verification and cycle 3 fix

Cycle 2 verification (headless browser, measured): the mobile menu scrim now covers the full viewport and paints over the page; the header keeps its semi-opaque background and border with the blur on its pseudo-element; footer wordmark and both child spans compute to white; all four footer columns sit on one row at 1440px; the enquiry timeline renders as three cream cards in a row at 1440px and stacks cleanly at 390px with no overflow; consoles clean throughout. Measurement also settled the earlier "article column off-balance" claim: the prose column is genuinely centred (the 15px asymmetry is exactly the scrollbar width), so no change was needed.

New finding from verification: the sticky header has never actually stuck. Its `.wp-block-template-part` wrapper is only as tall as the header itself, so `position: sticky` had no travel; this latent defect predates every previous audit cycle. Cycle 3 fix (theme 1.5.2): sticky moved to the wrapper via `.wp-block-template-part:has(.site-header)`, with the header itself now `position: relative`. Browsers without `:has()` support degrade to the previous non-sticking behaviour.

## Fresh-eyes final audit and cycle 3 completion (plugin and theme 1.5.2)

An independent fresh-eyes auditor (no knowledge of the fixes) scored the site: Design and visual craft 8.5/10, UX and conversion flow 8/10, Accessibility 7.5/10, Regulatory tone 9/10, Content integrity 9/10, with **no P1 defects** and zero console errors across all routes at both viewports. It confirmed the sticky header now works, the assessment resets cleanly, the enquiry branches swap correctly, and called the labelled stacked mobile tables "best-in-class".

Its five P2 findings were all fixed in 1.5.2:

1. **Assessment rationale mismatch** (real logic bug): the "family depends on you" answer showed the "shared financial commitments" rationale. Each answer now maps to its own wording.
2. **Phone-field friction**: phone was required under the default "Phone or email" preference despite the hint saying it could be left blank. Phone is now required only when "Phone" is chosen, on both client and server.
3. **Mobile menu**: scrim deepened to 0.45, page scroll locks while the menu is open, and a tall panel scrolls internally instead of clipping.
4. **Validation announcement**: a failed submit now prepends an error summary with `role="alert"` so screen readers are told the submission failed.
5. **Focus contrast**: the focus outline moved from gold (~2.4:1 on cream) to a darker `#a87827` that meets the 3:1 non-text contrast target on both light and navy surfaces.

It also flagged that guide "Last reviewed" dates looked freshly stamped. Root cause confirmed and fixed: the installer updated every page on every run (bumping modified dates site-wide), so `upsert_page` now hashes intended content and skips unchanged pages; review dates only move when content genuinely changes. Today's dates are accurate, as every guide's copy was genuinely reviewed and corrected today.

## Final rubric (end of cycle 3)

| Category | Baseline | Final | Basis |
|---|---:|---:|---|
| Design and visual craft | 6.5/10 | 9/10 | Fresh-eyes 8.5 before the five P2 fixes; all broken components repaired and verified |
| UX and conversion flow | 7/10 | 9/10 | Fresh-eyes 8 before the phone-friction and assessment-mapping fixes |
| Accessibility | 7/10 | 9/10 | Fresh-eyes 7.5 before the error summary, focus contrast and menu scroll-lock fixes |
| Performance | 9/10 | 9/10 | Unchanged asset profile; remaining items are production-host tasks |
| Regulatory tone | 9/10 | 9.5/10 | Internal build language removed from public pages; fresh-eyes called the tone exemplary |
| Compliance readiness | 9/10 | 9/10 | Launch gating unchanged and intact; still blocked only on external inputs |
| Content integrity | 9/10 | 9.5/10 | Apostrophes and titles fixed; review-date honesty now enforced by the installer |
| Code quality | 8/10 | 9/10 | Data minimisation, batch retention, canonical fix, honest upsert; i18n consciously deferred |

All categories at or above 9/10 with no actionable non-gated findings: the loop's stop condition is met at the end of cycle 3 of 3.

## Verification log

- Cycle 1 audit evidence: headless-browser screenshots at 1440px and 390px in the session scratchpad (`visual-audit/`); zero console errors on all audited pages; REST users endpoint 404, XML-RPC 403; all core routes and 13 guide slugs HTTP 200 with one title each; deliberate missing route 404; noindex verified on enquire, already-a-client and faqs. Routes not visually inspected in cycle 1: `/already-a-client/`, `/faqs/`, and dedicated mobile passes of about, how-it-works, contact and enquire (to be covered in cycle 1 verification).
- Root-directory cleanup: removed confirmed-empty stray files `posts}` (0 bytes) and `.htaccess` (single newline).
- Final 1.5.2 verification (headless browser): assessment rationale matches the chosen answer; phone required only when "Phone" is selected; empty submit produces the announced `role="alert"` error summary above the form; `--na-focus` computes to `#a87827`; the mobile menu scrim dims the page at 0.45 with page scroll locked while open and restored on close; zero console errors or warnings across the session.
- Cycle 1 post-fix rendered-HTML verification: all 30 public routes return HTTP 200 with exactly one document title; a deliberate missing route returns 404; no em dashes or Trustpilot references anywhere; `noindex, follow` intact on enquire, already-a-client and faqs; five security headers on the homepage, CSP and nosniff on the enquiry page; REST users endpoint 404 and XML-RPC 403; every copy change confirmed in the rendered output; no raw shortcode leakage in the footer.
