# Nest Assured, financial promotion review pack

**Prepared for:** Sesame compliance review
**Firm:** Nest Assured, protection advice connected to Major Money Matters
**Status:** Pre-launch. Nothing on the site is indexable and the enquiry form does not render.
**Date prepared:** 5 August 2026

---

## 1. What is being submitted

A 54 page website: 33 educational guides, 7 product pages, 2 planning calculators, and the supporting pages (home, about, how it works, contact, enquire, existing clients, editorial policy, FAQs, and four legal pages).

The site does not quote, compare insurers, or transact. It explains how UK protection products work and routes the reader into a regulated adviser conversation.

## 2. What the site deliberately does not do

These are the rules the build was written to, and they are enforced in code rather than by editorial discipline alone.

| Rule | How it is enforced |
|---|---|
| No premium, payout, percentage or market statistic anywhere in the guides | Verified by scan. There are no numeric claims in any guide. |
| No personal recommendation | Guides describe mechanisms and trade-offs and route decisions to an adviser. |
| No claim of independence, whole of market, impartiality, or commission position | Removed from all published copy. A wp-admin warning fires if any of those terms is entered into the approved-copy settings. |
| No testimonials, reviews, ratings or awards | None published. The review slot renders nothing until a verified Google Business Profile URL is supplied. Trustpilot is unsupported by design. |
| Google reviews only | The only review integration accepts a Google Business Profile URL. |

## 3. The compliance gate

Nothing regulated is published until it has been entered and signed off. The gate is fail closed: it blocks by default and opens only on an explicit act.

**Controls that must be supplied before launch**

- FCA reference
- Regulatory status wording
- Privacy notice
- Complaints wording
- Financial promotions wording
- Adviser biography and photograph
- Approved FAQs
- Enquiry retention period

**While any control is missing, or sign-off has not been recorded:**

- Every page carries `noindex`, asserted twice (core and Yoast) and on feeds
- The enquiry form does not render, in every environment
- The adviser dock says enquiries open shortly rather than offering a booking
- robots.txt does not advertise a sitemap
- Regulatory wording, adviser credentials, FCA reference and the review slot all render as nothing rather than as placeholder text

**Sign-off** is a separate, recorded act: an approver name, a UTC timestamp, and a hash of the exact wording approved. Editing any approved copy invalidates the sign-off automatically, so approval always refers to specific wording rather than to the site in general.

## 4. Items needing a decision

### 4.1 Adviser biography, priority item

The stored `ollie_bio` setting currently contains:

> "Ollie works the full whole-of-market panel. Advice is independent, and the recommendation is on cover and price, never on commission."

This has **not** been altered, because it is approved-copy data rather than code. It needs a decision before launch. "Independent" is a protected status term, and an appointed representative advising from a panel is normally restricted. The site warns about this in wp-admin but will publish whatever is entered.

### 4.2 The planning calculators

Two tools at `/calculators/`, also embedded in four guides:

- **Cover gap estimator.** Adds what the visitor says would need covering, subtracts what they say they already have.
- **Income timeline.** Maps their own sick pay and savings against their own outgoings.

Both use **only figures the visitor enters**. There is no premium, insurer, product or market data. Nothing is submitted or stored, so no personal data leaves the browser. Every output is framed as a starting point for a conversation, and each carries a standing notice that it is not advice, a quote or a recommendation.

This is the item most likely to attract attention, because a tool producing a monetary figure on an appointed representative's site sits near the advice boundary. It is offered for an explicit view.

### 4.3 Writer judgement calls flagged for review

- **Directional pricing statements.** Some guides say, for example, that decreasing term cover typically costs less than equivalent level cover. These are structural facts about how products are priced, not market data, and each is hedged. Flagged in case any directional statement is unwanted.
- **Named legislation.** Guides reference the Consumer Insurance (Disclosure and Representations) Act 2012 and the Access to Medical Reports Act 1988 when explaining disclosure and GP reports. Flagged in case naming legislation on public pages is not preferred.
- **A firm self-description** appears in the two tax guides: "Nest Assured advises on protection insurance and does not provide legal or tax advice."

## 5. Corrections already made

The content was audited before submission and the following were found and fixed. They are listed so the reviewer can see what was checked rather than having to rediscover it.

| Issue | Correction |
|---|---|
| Inheritance tax guide stated the spouse exemption in terms of UK domicile | Domicile ceased to be the connecting factor on 6 April 2025. Rewritten around the residence test, with a direction to confirm the current position. |
| Bereavement Support Payment framed around marriage and civil partnership | Cohabiting partners with dependent children have been eligible since 2023. Corrected, with a pointer to GOV.UK. |
| Joint life guide suggested a survivor could use a separation or guaranteed insurability option after a claim | A first death policy ends when it pays. Rewritten to describe the limited survivor option that some insurers offer, and to say it must be confirmed in the wording. |
| Death in service described as support for someone unable to work | It pays on death only. Corrected, and the sick pay sequence corrected with it. |
| Taper relief described without its precondition | It reduces the tax on a failed gift, and only where the gift exceeds the available nil rate band. |
| An unsubstantiated claim about television advertising | Removed. |
| "Every guide reviewed by Ollie" on the about page, and per-guide review credits | No review had been recorded. All review claims removed from pages and from structured data. A reviewer is named only where a review is recorded against that guide. |
| A homepage chart implying a premium-to-age relationship | Removed. The figures were not sourced. |

## 6. How to review

- The site runs locally. All pages are reachable from the primary navigation and the footer.
- The 33 guides are listed at `/guides/`, grouped by subject.
- The four legal pages are linked from the footer on every page, including the complaints procedure.
- Approved copy is entered at **Settings, then Nest Assured** in wp-admin. That screen lists every outstanding control and records the sign-off.

## 7. Known limitations at time of submission

- Contact telephone number and monitored email address are not yet supplied, so no telephone route is published. Settings exist for both.
- The FAQs page holds no approved content and is intentionally unlinked.
- Terms of use and a cookie policy do not exist yet.
- The site has never been publicly accessible and has never been indexed.
