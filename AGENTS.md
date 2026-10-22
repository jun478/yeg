# GAIA Child Theme Instructions

## Scope

- These instructions apply to `wp-content/themes/gaia_tcd102_child/` and its descendants.
- This directory is the Git-managed implementation area for YEG customizations.
- Use `wp-content/themes/gaia_tcd102/` only as a read-only reference.
- Never modify WordPress core from a child-theme task.

## Implementation Rules

- Preserve the existing child-theme structure and load order.
- Prefer the smallest focused change that satisfies the request.
- Keep new functionality in an appropriate file under `functions/` and load it through the existing child-theme entry point when necessary.
- Preserve existing shortcode names, hooks, CSS classes, breakpoints, and rendering order unless the user requests a change.
- Follow existing formatting and naming conventions; avoid unrelated refactors.
- Escape output with the appropriate WordPress escaping function.
- Sanitize and validate input, use nonces and capability checks for state-changing requests, and use prepared queries when database access is unavoidable.
- Do not add dependencies, external scripts, trackers, image services, or other third-party integrations without prior approval.
- Do not place credentials, API keys, environment values, or private data in theme files, generated assets, logs, or test artifacts.

## Design and Assets

- Respect the current GAIA/YEG visual language and responsive behavior.
- Minor CSS, spacing, typography, and responsive fixes may be implemented within the requested scope.
- Obtain approval before major top-page, site-wide, brand-color, header, footer, or large-content changes.
- Keep SVG and generated image assets inside an explicitly approved child-theme asset directory.
- AI image generation requires an explicit Main Agent assignment; do not create a new external-service connection.

## Validation

- Run PHP syntax checks for every changed PHP file.
- Check CSS and JavaScript changes for syntax errors and unintended global effects.
- For rendered UI changes, verify the local WordPress page at representative desktop and mobile widths when browser testing is authorized.
- Do not perform real mail sends, production API calls, destructive database operations, commits, or pushes.
- Report the exact files changed, validation performed, and remaining browser or environment limitations.
