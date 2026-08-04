---
name: design-with-taste
description: >-
  Use for ANY visual, UI, or UX design work: landing pages, web pages, app screens,
  components, dashboards, emails, decks, brand or marketing artefacts, artifacts,
  charts, and any front-end build where appearance is part of the deliverable.
  Also use when reviewing or critiquing an existing design, or when a build is
  "technically fine but looks generic". Enforces wide-then-narrow exploration,
  the four-part design brief, an anti-slop guardrail list, and visual rather than
  blind iteration. Do not use for pure back-end, data, or infrastructure work with
  no visual surface.
---

# Design With Taste

Default AI design output is generic because the average of everything is generic.
The fix is not a better model or a longer spec. It is taste applied as constraint:
a named aesthetic, real references, explicit intent, and a hard never-list, then
several options compared side by side instead of one lucky guess.

Announce that you are using this skill, then follow it.

## Rule 0 — Never one-shot

One-shot design is a lottery ticket. Producing a single version and refining it
means the first output silently becomes the whole design space.

Always cast wide first, then narrow:

1. **Five directions.** Build the same page or screen in five genuinely different
   aesthetic families, each in its own folder (`v1/` … `v5/`). Same intent, same
   guardrails, same content for all five. Never blend directions; each version
   commits fully to its own aesthetic.
2. **Pick one.** Show all five, get a choice, then build three body variants of
   the winner.
3. **Pick again.** Only now resolve the hero image or main visual.
4. **Tinker.** Fonts, colour, spacing, motion. Small moves only.

Fidelity rises as you narrow. Do not invert this order and do not skip to step 4.

If the user only wants one version, say plainly that this is the step most
responsible for generic output, then comply.

## The four-part brief

Never start a build without all four. If any are missing, derive what you can
from the repo or brand, and ask for the rest rather than inventing them.

**1 · Aesthetic.** The named design family, ideally from a reference the user
already likes. Not "modern and clean", which means nothing. Something like
"print-tech x data", "dither mono", "vast quiet", "classical remix",
"warm editorial". The name is the point: vocabulary is what makes a style
reproducible.

**2 · Reference.** Screenshots or live URLs. Match the *feel*, never copy the
layout or lift assets. State explicitly which qualities you are drawing from
(density, contrast, type scale, texture) so the reference constrains rather
than dictates.

**3 · Intent.** What it is, who it is for, what they should do. Name the primary
conversion action and where it appears. A reader should be able to state what
the thing is within three seconds of landing.

**4 · Guardrails.** Explicit always and never lists. This is what kills slop
before it renders, so write it before writing any markup.

## Standing never-list

Treat these as banned unless the user explicitly asks for one. They are the
tells that mark a design as machine-generated:

- Blue-to-purple gradients, and gradient text headlines
- Glossy 3D blobs, abstract floating shapes, generic isometric illustration
- Inter or system-font-only typography; any single-font page
- Rounded-everything, uniform border radius on every element
- Icon-grid feature rows: three or four cards, each with a generic icon, a
  two-word title, and a sentence of filler
- Untextured stock photography, and smiling-people-at-laptops
- Evenly distributed rainbow palettes with no dominant ground colour
- Centre-aligned everything with the same vertical rhythm down the whole page
- "Supercharge / Unlock / Seamless / Effortless / Elevate" marketing verbs
- Emoji used as interface iconography

## Standing always-list

- One monumental element anchors the page. Something is clearly the biggest,
  loudest, or most textured thing on screen.
- Type at extremes: a genuinely large display size against a genuinely small
  functional size. Avoid the mushy middle where everything is 16px to 24px.
- A single dominant ground colour with one warm or sharp accent, not a spread.
- Imagery is processed, not raw: halftone, dither, grain, duotone, ASCII,
  linework, or heavy crop. Raw stock reads as filler.
- Technical marginalia where it suits the aesthetic: coordinates, IDs, ruler
  ticks, timestamps, small mono labels. It signals a human made deliberate choices.
- Asymmetry somewhere. Perfect symmetry throughout reads as a template.
- Real content, or realistic content. Never lorem ipsum, never "Feature One".

## Hero image protocol

Do not generate or source imagery during the layout pass. It confuses two
separate decisions and wastes time.

For each version, reserve the hero slot exactly where the placement note says.
Fill it with a flat CSS stand-in in a colour drawn from the direction's own
palette. Size all surrounding typography and negative space as though the real
image were already there, so the final image drops in with zero layout change.
Resolve imagery only after a direction has been chosen.

## Audit before you present

Before showing any design, check these seven dimensions and fix what fails.
State briefly what you checked.

1. **Typography** — hierarchy readable at a glance, deliberate scale, no orphan
   sizes, line length between roughly 55 and 80 characters for body copy.
2. **Colour** — a dominant ground, sufficient contrast for WCAG AA, accent used
   sparingly enough to still mean something.
3. **Spatial** — consistent spacing scale, intentional density, whitespace that
   groups rather than merely pads.
4. **Responsive** — check the real breakpoints, not just a desktop screenshot.
   No horizontal body scroll. Tables, code, and diagrams scroll inside their
   own container.
5. **Interaction** — hover, focus, active, disabled, loading, empty, and error
   states all exist. Keyboard focus is visible.
6. **Motion** — purposeful and weighted, no gratuitous fade-up-on-scroll on
   every element. Respect `prefers-reduced-motion`.
7. **UX writing** — specific over clever, verbs over nouns, no filler. Every CTA
   says what happens next.

Also verify light and dark rendering unless the design deliberately commits to
one, and check that nothing depends on colour alone to convey meaning.

## Iterate visually, never blindly

Never guess in the terminal, and never ask the user for vaguer direction like
"should it feel more premium". Build the controls, then turn the dials.

Refinement order once a direction is chosen:

1. Resolve the hero. Offer several options, pick one, refine its colour.
2. Transitions. No hard cuts between hero and body.
3. Motion. Weighted page loads, considered easing.
4. Offer a live tweaks panel: heading font, body font, sizes, tracking, weights,
   accent colour, spacing scale, all adjustable in the browser. Turning dials on
   a live page beats another round of prose description.
5. Keep feeding references until it looks like the user rather than like a tool.

Screenshot or render what you build and look at it before declaring it done. If
you cannot see it, say so rather than asserting it looks right.

## Reuse what works

When a direction lands, capture it so the next build starts ahead: the design
family name, the vocabulary that describes it, the keywords that reproduce it,
the palette, the type pairing, and a reference screenshot. Group these by design
family rather than by project. That library is the moat; a project folder is not.

## Oliver's non-negotiables

These override anything above and apply to every visual deliverable:

- UK English throughout, in interface copy and content alike.
- Never use em dashes. Use commas, hyphens, or restructure.
- M3M and Major Money Matters public-facing mortgage content is an FCA-regulated
  financial promotion. Risk warnings must be present and legible, never
  de-emphasised for aesthetic reasons, and never below the fold when they relate
  to a claim above it. Reviews shown are Google only, never Trustpilot.
- Bobbin Babes is child-facing. Safeguarding and child data exposure are always
  in scope: no identifiable children without confirmed consent, no data capture
  beyond what is necessary.
- Wrap existing design systems, do not replace them, unless told to rebuild.
- No new fonts, icon sets, or UI dependencies without approval. Prefer what the
  project already loads.
