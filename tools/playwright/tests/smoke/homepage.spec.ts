import { expect, test, type ConsoleMessage, type Page, type TestInfo } from '@playwright/test';

const localOrigin = 'http://localhost:8082';
const knownParentThemeErrors = [
  "Cannot read properties of null (reading 'querySelectorAll')",
  "null is not an object (evaluating 'slider.querySelectorAll')",
];
const knownWebKitMediaError =
  /^Button failed to load, iconName = (?:invalid|pip|airplay)-placard, layoutTraits = \[MacOSLayoutTraits Inline\], src = blob:http:\/\/localhost:8082\/[0-9a-f-]+$/;
const intentionallyBlockedRequestError = 'Failed to load resource: net::ERR_BLOCKED_BY_CLIENT.Inspector';

type BrowserDiagnostic = {
  message: string;
  source: string;
  url?: string;
};

function sanitizeUrl(rawUrl: string): string | undefined {
  try {
    const parsedUrl = new URL(rawUrl, localOrigin);
    return `${parsedUrl.origin}${parsedUrl.pathname}`;
  } catch {
    return undefined;
  }
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

function consoleDiagnostic(message: ConsoleMessage): BrowserDiagnostic {
  const locationUrl = message.location().url;

  return {
    message: message.text(),
    source: `console-${message.type()}`,
    url: locationUrl ? sanitizeUrl(locationUrl) : undefined,
  };
}

function isExpectedDiagnostic(diagnostic: BrowserDiagnostic): boolean {
  if (diagnostic.source === 'console-warning') {
    return true;
  }

  if (knownParentThemeErrors.includes(diagnostic.message)) {
    return true;
  }

  if (knownWebKitMediaError.test(diagnostic.message)) {
    return true;
  }

  if (diagnostic.message === intentionallyBlockedRequestError && diagnostic.url) {
    const url = new URL(diagnostic.url);
    return (
      url.origin === 'https://www.google.com' &&
      (url.pathname === '/ccm/collect' || /^\/rmkt\/collect\/\d+\/$/.test(url.pathname))
    );
  }

  return false;
}

async function attachDiagnostics(testInfo: TestInfo, diagnostics: BrowserDiagnostic[]): Promise<void> {
  if (diagnostics.length === 0) {
    return;
  }

  await testInfo.attach('browser-diagnostics', {
    body: Buffer.from(JSON.stringify(diagnostics, null, 2)),
    contentType: 'application/json',
  });
}

test.beforeEach(async ({ page }) => {
  await blockExternalWrites(page);
});

test('homepage loads its main content without unexpected browser errors', async ({ page }, testInfo) => {
  const diagnostics: BrowserDiagnostic[] = [];

  page.on('pageerror', (error) => {
    diagnostics.push({ message: error.message, source: 'pageerror' });
  });
  page.on('console', (message) => {
    if (['error', 'warning'].includes(message.type())) {
      diagnostics.push(consoleDiagnostic(message));
    }
  });

  const response = await page.goto('/', { waitUntil: 'domcontentloaded' });

  expect(response, 'The homepage navigation must return a response.').not.toBeNull();
  expect(response?.status(), 'The homepage must return HTTP 200.').toBe(200);
  await expect(page).toHaveTitle(/日本商工会議所青年部第44回全国リーダーズ研修/);
  await expect(page.locator('main.l-main')).toBeVisible();
  await expect(page.getByRole('heading', { name: '研修会で出会う学び' })).toBeVisible();
  await expect(page.getByRole('heading', { name: 'リーダーズガイド' })).toBeVisible();
  await expect(page.getByRole('heading', { name: 'スケジュール' })).toBeVisible();

  await page.evaluate(async () => {
    for (let position = 0; position < document.documentElement.scrollHeight; position += window.innerHeight) {
      window.scrollTo(0, position);
      await new Promise((resolve) => window.setTimeout(resolve, 30));
    }
    window.scrollTo(0, 0);
  });
  await page.waitForTimeout(500);

  const brokenImages = await page.locator('img').evaluateAll((images) =>
    images
      .filter((image) => {
        const source = image.currentSrc || image.getAttribute('src') || '';
        return source !== '' && (!image.complete || image.naturalWidth === 0);
      })
      .map((image) => image.currentSrc || image.getAttribute('src') || ''),
  );

  const localBrokenImages = brokenImages.filter((source) => new URL(source, localOrigin).origin === localOrigin);
  const externalBrokenImages = brokenImages.filter((source) => new URL(source, localOrigin).origin !== localOrigin);

  if (externalBrokenImages.length > 0) {
    await testInfo.attach('external-image-diagnostics', {
      body: Buffer.from(JSON.stringify(externalBrokenImages.map(sanitizeUrl).filter(Boolean), null, 2)),
      contentType: 'application/json',
    });
  }

  expect(localBrokenImages, `Broken local images:\n${localBrokenImages.join('\n')}`).toEqual([]);

  await attachDiagnostics(testInfo, diagnostics);
  const unexpectedDiagnostics = diagnostics.filter((diagnostic) => !isExpectedDiagnostic(diagnostic));
  expect(
    unexpectedDiagnostics,
    `Unexpected browser errors:\n${JSON.stringify(unexpectedDiagnostics, null, 2)}`,
  ).toEqual([]);
});
