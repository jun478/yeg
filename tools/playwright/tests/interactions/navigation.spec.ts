import { expect, test, type Page } from '@playwright/test';

const localOrigin = 'http://localhost:8082';

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

test('primary navigation works without activating external actions', async ({ page }, testInfo) => {
  await page.goto('/', { waitUntil: 'domcontentloaded' });

  if (testInfo.project.name === 'desktop-chromium') {
    const detailsLink = page.locator('.p-global__nav-wrapper').getByRole('link', { name: /開催要項/ }).first();
    await expect(detailsLink).toBeVisible();
    const detailsHref = await detailsLink.getAttribute('href');
    expect(detailsHref).toBeTruthy();

    await Promise.all([
      page.waitForURL((url) => url.origin === localOrigin && url.pathname !== '/'),
      detailsLink.click(),
    ]);
    expect(new URL(page.url()).origin).toBe(localOrigin);
    return;
  }

  const menuButton = page.locator('.js-menu-button:visible');
  await expect(menuButton).toHaveCount(1);
  await menuButton.click();

  const drawer = page.locator('#js-drawer');
  await expect(page.locator('html')).toHaveClass(/show-drawer/);
  await expect
    .poll(async () => Math.abs((await drawer.boundingBox())?.x ?? Number.POSITIVE_INFINITY))
    .toBeLessThanOrEqual(1);
  await expect(drawer.locator("a[href='/details']")).toBeVisible();

  await page.locator('#js-drawer-close-button').click();
  await expect
    .poll(async () => (await drawer.boundingBox())?.x ?? 0)
    .toBeGreaterThanOrEqual(page.viewportSize()?.width ?? 390);

  const externalLinks = page.locator("a[href^='http']:not([href^='http://localhost:8082'])");
  expect(await externalLinks.count()).toBeGreaterThan(0);
});
