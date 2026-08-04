# AI Instructions

This is a local WordPress site managed by [WordPress Studio](https://developer.wordpress.com/studio/).
For WordPress Studio instructions, see @STUDIO.md

> **Customising this file:** Feel free to edit, extend, or replace the contents below.


## Design workflow (applies locally and in cloud sessions)

Before designing, building, or reviewing anything with a visual surface (pages, templates, components, emails, charts), invoke the `design-with-taste` skill at `.claude/skills/design-with-taste/SKILL.md` and follow it. The skill is committed to this repo so cloud sessions load it too. Non-negotiables:

- Never one-shot. Cast wide first: five distinct aesthetic directions, pick one, three body variants of the winner, then the hero image, then small tweaks. Fidelity rises as you narrow.
- Every build needs four inputs before any markup: aesthetic (a named design family), reference (screenshots or live URLs, match the feel, never copy), intent (what it is, who it is for, what they should do), and guardrails (explicit always and never lists).
- Banned by default: blue-purple gradients, gradient text, glossy 3D blobs, Inter or system-font-only typography, rounded-everything, icon-grid feature rows, untextured stock photography, lorem ipsum.
- Reserve hero image slots with flat CSS stand-ins during layout. Resolve imagery only after a direction is chosen.
- Iterate visually, never blindly: render or screenshot what you build and look at it, offer a live tweaks panel, never guess in the terminal.
