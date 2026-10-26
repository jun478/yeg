import { expect, test, type Page } from '@playwright/test';

const localOrigin = 'http://localhost:8082';

function sanitizeUrl(rawUrl: string): string {
  const parsedUrl = new URL(rawUrl, localOrigin);
  return `${parsedUrl.origin}${parsedUrl.pathname}`;
}

async function blockExternalWrites(page: Page): Promise<void> {
  await page.route('**/*', async (route) => {
    const request = route.request();
    const method = request.method();

    if (!['GET', 'HEAD'].includes(method)) {
      await route.abort('blockedbyclient');
      return;
    }

    await route.continue();
  });
}

test.beforeEach(async ({ page }) => {
  await blockExternalWrites(page);
});

test('homepage remains within the viewport and produces a design screenshot', async ({ page }, testInfo) => {
  await page.goto('/', { waitUntil: 'domcontentloaded' });

  await expect(page.locator('main.l-main')).toBeVisible();
  await expect(page.locator('.yeg-top-bunkakai-pv')).toBeVisible();
  await expect(page.locator('.yeg-top-leaders-guide')).toBeVisible();
  await expect(page.locator('.yeg-kariyusi')).toBeVisible();
  await expect(page.locator('.yeg-top-schedule')).toBeVisible();

  await page.evaluate(async () => {
    for (let position = 0; position < document.documentElement.scrollHeight; position += window.innerHeight) {
      window.scrollTo(0, position);
      await new Promise((resolve) => window.setTimeout(resolve, 30));
    }
    window.scrollTo(0, 0);
  });
  await page.waitForTimeout(500);

  const pageWidth = await page.evaluate(() => ({
    clientWidth: document.documentElement.clientWidth,
    scrollWidth: document.documentElement.scrollWidth,
  }));
  expect(pageWidth.scrollWidth, 'The page must not have horizontal document overflow.').toBeLessThanOrEqual(
    pageWidth.clientWidth + 1,
  );

  const horizontalOverflow = await page
    .locator('.yeg-top-bunkakai-pv, .yeg-top-leaders-guide, .yeg-kariyusi, .yeg-top-schedule')
    .evaluateAll((elements) =>
      elements
        .map((element) => {
          const box = element.getBoundingClientRect();
          return {
            className: element.className,
            left: box.left,
            right: box.right,
            viewportWidth: document.documentElement.clientWidth,
          };
        })
        .filter((item) => item.left < -1 || item.right > item.viewportWidth + 1),
    );
  expect(horizontalOverflow, 'Primary sections must remain inside the page width.').toEqual([]);

  const clippedHeadings = await page.locator('main h1, main h2, main h3, main h4').evaluateAll((headings) =>
    headings
      .filter((heading) => {
        const style = window.getComputedStyle(heading);
        const clipsOverflow = ['hidden', 'clip'].includes(style.overflowX) || ['hidden', 'clip'].includes(style.overflowY);
        return clipsOverflow && (heading.scrollWidth > heading.clientWidth + 2 || heading.scrollHeight > heading.clientHeight + 2);
      })
      .map((heading) => heading.textContent?.trim() || heading.tagName),
  );
  expect(clippedHeadings, `Clipped headings:\n${clippedHeadings.join('\n')}`).toEqual([]);

  const leadersGuide = page.locator('.yeg-top-leaders-guide iframe');
  await expect(leadersGuide).toBeVisible();
  const leadersGuideBox = await leadersGuide.boundingBox();
  expect(leadersGuideBox?.width ?? 0).toBeGreaterThan(0);
  expect(leadersGuideBox?.height ?? 0).toBeGreaterThan(0);

  const kariyusiImage = page.locator('.yeg-kariyusi img').first();
  await expect(kariyusiImage).toBeVisible();
  const kariyusiImageState = await kariyusiImage.evaluate((image: HTMLImageElement) => ({
    complete: image.complete,
    naturalWidth: image.naturalWidth,
    source: image.currentSrc || image.src,
  }));

  if (new URL(kariyusiImageState.source, localOrigin).origin === localOrigin) {
    expect(kariyusiImageState.complete).toBe(true);
    expect(kariyusiImageState.naturalWidth).toBeGreaterThan(0);
  } else if (!kariyusiImageState.complete || kariyusiImageState.naturalWidth === 0) {
    await testInfo.attach('external-kariyusi-image-diagnostic', {
      body: Buffer.from(
        JSON.stringify({ ...kariyusiImageState, source: sanitizeUrl(kariyusiImageState.source) }, null, 2),
      ),
      contentType: 'application/json',
    });
  }

  await page.screenshot({
    path: testInfo.outputPath('homepage-full.png'),
    fullPage: true,
    animations: 'disabled',
  });
});
