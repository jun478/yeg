# YEG Playwright Tests

This directory contains browser tests for the local YEG WordPress site. It is isolated from the WordPress theme's runtime dependencies.

## Runtime

- Node.js: `v24.22.1` installed through nodebrew
- Target: `http://localhost:8082/`
- Browsers: Chromium and WebKit

The existing nodebrew `current` version remains unchanged. Run every command through `nodebrew exec`:

```bash
cd wp-content/themes/gaia_tcd102_child/tools/playwright
nodebrew exec v24.22.1 -- node --version
nodebrew exec v24.22.1 -- npm --version
```

## Commands

```bash
# Install the locked npm dependencies.
nodebrew exec v24.22.1 -- npm ci

# Install only the required browser engines.
nodebrew exec v24.22.1 -- npx playwright install chromium webkit

# Run all projects and tests.
nodebrew exec v24.22.1 -- npm test

# Run focused suites.
nodebrew exec v24.22.1 -- npm run test:smoke
nodebrew exec v24.22.1 -- npm run test:responsive
nodebrew exec v24.22.1 -- npm run test:interactions

# Run deterministic visual regression tests with mocked content resources.
nodebrew exec v24.22.1 -- npm run test:visual

# Create only missing visual baselines after reviewing the rendered page.
nodebrew exec v24.22.1 -- npm run test:visual:update

# Exercise the real external resources without visual baseline comparison.
nodebrew exec v24.22.1 -- npm run test:external

# Open the generated HTML report.
nodebrew exec v24.22.1 -- npm run test:report
```

## Projects

- `desktop-chromium`: Chromium at 1440 x 900
- `mobile-chromium`: Chromium at 390 x 844 with touch and mobile behavior
- `mobile-webkit`: WebKit using Playwright's iPhone 13 device profile

## Artifacts

- Test results, explicit screenshots, and traces: `test-results/`
- HTML report: `playwright-report/`
- Failure screenshots are captured automatically.
- Responsive tests save a full-page screenshot through `testInfo.outputPath()` for every project.
- Visual tests save AI-reviewable current screenshots in `test-results/` and compare them with the committed baselines under `tests/visual/__screenshots__/`.

Generated artifacts and `node_modules/` are excluded from Git. Keep intentional source-controlled visual baselines next to their tests if visual comparison tests are added later.

## Safety

- Tests target the local WordPress site only.
- Existing external GET/HEAD resources such as fonts, CDN assets, images, video, and the FlipHTML5 iframe may load as part of rendering.
- All non-GET/HEAD requests are blocked unless a future test explicitly opts in.
- Broken local images fail the smoke test; unavailable external images are attached to the report as diagnostics to avoid CDN-related false failures.
- External registration, purchase, LINE, and document links are not clicked.
- No form submissions, email sends, database changes, WordPress admin operations, commits, or pushes are performed.

## Visual Regression

The visual suite keeps the real page structure while replacing unstable content resources with deterministic placeholders:

- The FlipHTML5 document is replaced inside its existing iframe.
- The three `leaders.yeg.jp` images retain their source aspect ratios through SVG placeholders.
- The random hero video and external video use fixed 16:9 placeholder surfaces.
- Google-hosted fonts and tracking resources are blocked; a fixed macOS system font stack is applied.
- Required jsDelivr assets remain available because the schedule and slider layouts depend on them.

Normal smoke and responsive suites continue to request real external resources. Visual baseline updates must be scoped to the visual suite and reviewed before committing. Do not use an unrestricted snapshot update during normal test runs.

Baseline environment details are recorded in `tests/visual/__screenshots__/BASELINE_ENVIRONMENT.md`. Runtime browser and computed-font metadata are also attached to each visual test result.

The homepage currently emits one known JavaScript error from the read-only GAIA parent theme when `.js-megamenu01-slider` is absent. The smoke test records that exact error as a known issue and fails on other unexpected JavaScript errors.
