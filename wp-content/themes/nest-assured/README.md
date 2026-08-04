# Nest Assured block theme

A lightweight Full Site Editing theme built for the Nest Assured protection proposition.

## Design system

The site runs one design system, referred to in the code as **v2** (an editorial
direction). Site-wide chrome and every page style live in `assets/css/v2.css`;
`style.css` holds shared foundations plus the components the guide articles still
use. Page markup is wrapped in `.na-v2`.

- Navy `#102943`
- Warm gold `#c79a54` — decorative only, or on navy. It reaches about 2.4:1 on the
  light surfaces, so **anything carrying meaning** (text, links, progress bars,
  selected states) must use the deeper `--v2-gold-deep` `#8a6a33` instead.
- Paper `#fffdf9`, page `#fbf8f2`
- Georgia headings over a system sans-serif body. This is a known gap: no webfont
  is loaded, so the body face differs between platforms. Replacing it is a design
  task, not a CSS tweak.
- Responsive from 390 px mobile to wide desktop
- Reduced-motion support, a skip link, and print styles that strip the chrome

## Templates

- Static front page, standard pages, search, index and a custom 404
- Shared header and footer template parts
- Guide articles are styled through the `na-editorial-guide` body class, which the
  plugin adds, plus a reading-progress bar in `assets/js/site.js`

## Two things worth knowing before editing

**The post-title block is suppressed on most views.** `page.html` renders
`post-title` as the `h1`, but v2 pages and guide articles carry their own heading,
so `functions.php` filters the block out for them. That filter is gated on the
queried object: an ungated version blanked the linked titles inside query loops
and search results.

**Shortcodes inside template parts must return inline content.** WordPress's core
shortcode block applies `wpautop` *before* `do_shortcode`, so a shortcode that
returns block-level markup ends up wrapped in a paragraph, and one placed inside a
template paragraph produces nested `<p>` elements that browsers tear apart. Put
the block-level wrapper in the template and keep the shortcode's output inline.
The adviser dock avoids the problem entirely by rendering on `wp_footer`.

The header and footer use the optimised WebP bird mark in `assets/images`. The
512 px PNG is retained for the WordPress site icon and future platform exports.
