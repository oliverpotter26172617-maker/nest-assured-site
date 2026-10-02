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


<!-- codex-design-workflow:start -->
## Codex visual workflow

Before designing, building or reviewing visual work, read `.agents/skills/design-with-taste/SKILL.md` and follow its specialist workflow. This is the authoritative Codex visual workflow in this repository, ahead of older `.claude/` or `.Codex/` workflow guidance. Preserve all project-specific brand, accessibility, compliance and security requirements and explicit exceptions; this workflow does not override them.

Reuse recorded project design choices. For new unselected work, gather aesthetic, reference, intent and always/never guardrails; present five distinct directions, honour Oliver's choice, then compare three body variants. Use flat CSS imagery until selection, create the hero asset afterwards, and refine the rendered result through a live tweaks panel. For substantial work, use one shared brief, scoped native agents where permitted, independent rendered review and at most two correction passes. Report unavailable preview or delegation honestly.

Never commit, push or merge without explicit authorisation. Never touch `.env` files or secrets. Wrap existing systems, and do not add dependencies without approval. Preserve regulated-content approval and child-data safeguards. Prepared files, local checks and visual review are separate from cloud activation, sign-off and publication.
<!-- codex-design-workflow:end -->
