import os from 'node:os';
import path from 'node:path';
import { expect, type Browser, type BrowserName, type Page, type TestInfo } from '@playwright/test';

const localOrigin = 'http://localhost:8082';
const allowedExternalResources = new Set([
  'https://cdn.jsdelivr.net/npm/@splidejs/splide-extension-intersection@0.2.0/dist/js/splide-extension-intersection.min.js',
  'https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide-core.min.css',
  'https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js',
  'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css',
  'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js',
]);

type PlaceholderDefinition = {
  height: number;
  palette: [string, string, string];
  width: number;
};

const imagePlaceholders = new Map<string, PlaceholderDefinition>([
  [
    '/cms/wp-content/uploads/2026/10/kariyusi_wear.jpg',
    { height: 640, palette: ['#d7f3fb', '#22b6e8', '#061842'], width: 1920 },
  ],
  [
    '/cms/wp-content/uploads/2026/04/YEGリーダーズ-研修会-アクセスガイド.png',
    { height: 506, palette: ['#eaf7fb', '#87d9ef', '#087ba5'], width: 900 },
  ],
  [
    '/cms/wp-content/uploads/2026/04/AdobeStock_354245962.jpeg',
    { height: 506, palette: ['#f3eee6', '#d5ad72', '#6c410e'], width: 900 },
  ],
]);

function normalizePathname(pathname: string): string {
  return decodeURIComponent(pathname).replace(/\/{2,}/g, '/');
}

function placeholderSvg({ height, palette, width }: PlaceholderDefinition): string {
  const [background, accent, foreground] = palette;

  return `
    <svg xmlns="http://www.w3.org/2000/svg" width="${width}" height="${height}" viewBox="0 0 ${width} ${height}">
      <defs>
        <linearGradient id="background" x1="0" y1="0" x2="1" y2="1">
          <stop offset="0" stop-color="${background}"/>
          <stop offset="1" stop-color="${accent}"/>
        </linearGradient>
      </defs>
      <rect width="${width}" height="${height}" fill="url(#background)"/>
      <circle cx="${Math.round(width * 0.18)}" cy="${Math.round(height * 0.36)}" r="${Math.round(height * 0.22)}" fill="${foreground}" opacity="0.16"/>
      <path d="M0 ${Math.round(height * 0.78)} C ${Math.round(width * 0.24)} ${Math.round(height * 0.48)}, ${Math.round(width * 0.5)} ${Math.round(height * 1.04)}, ${width} ${Math.round(height * 0.58)} L ${width} ${height} L0 ${height} Z" fill="${foreground}" opacity="0.32"/>
      <rect x="${Math.round(width * 0.38)}" y="${Math.round(height * 0.36)}" width="${Math.round(width * 0.42)}" height="${Math.max(10, Math.round(height * 0.055))}" rx="${Math.max(5, Math.round(height * 0.028))}" fill="${foreground}" opacity="0.42"/>
      <rect x="${Math.round(width * 0.38)}" y="${Math.round(height * 0.48)}" width="${Math.round(width * 0.28)}" height="${Math.max(8, Math.round(height * 0.038))}" rx="${Math.max(4, Math.round(height * 0.019))}" fill="${foreground}" opacity="0.3"/>
    </svg>
  `;
}

const flipHtmlPlaceholder = `<!doctype html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <style>
      * { box-sizing: border-box; }
      html, body { height: 100%; margin: 0; }
      body {
        align-items: center;
        background: linear-gradient(145deg, #eaf4f7, #d8e8ee);
        color: #061842;
        display: flex;
        font-family: Arial, "Hiragino Sans", sans-serif;
        justify-content: center;
        overflow: hidden;
      }
      .book {
        aspect-ratio: 16 / 10;
        background: #fdfefe;
        border: 1px solid rgba(6, 24, 66, 0.18);
        border-radius: 6px;
        box-shadow: 0 18px 42px rgba(6, 24, 66, 0.18);
        display: grid;
        grid-template-columns: 1fr 1fr;
        max-height: 76%;
        max-width: 78%;
        overflow: hidden;
        position: relative;
        width: 680px;
      }
      .page { padding: 10%; position: relative; }
      .page:first-child { background: linear-gradient(155deg, #061842, #087ba5); }
      .page:last-child { background: linear-gradient(155deg, #ffffff, #eaf7fb); }
      .page::before, .page::after {
        border-radius: 999px;
        content: "";
        display: block;
      }
      .page:first-child::before { background: #22b6e8; height: 12%; margin-top: 24%; width: 64%; }
      .page:first-child::after { background: rgba(255,255,255,.7); height: 4%; margin-top: 10%; width: 42%; }
      .page:last-child::before { background: #061842; height: 8%; margin-top: 16%; width: 72%; }
      .page:last-child::after { background: #87d9ef; height: 34%; margin-top: 14%; width: 100%; }
      .fold { background: rgba(6,24,66,.12); bottom: 0; left: 50%; position: absolute; top: 0; width: 1px; }
      .label { bottom: 7%; font-size: 11px; left: 0; letter-spacing: .14em; opacity: .65; position: absolute; right: 0; text-align: center; }
    </style>
  </head>
  <body>
    <div class="book" role="img" aria-label="リーダーズガイド外部コンテンツのビジュアルテスト用プレースホルダー">
      <div class="page"></div><div class="page"></div><span class="fold"></span>
    </div>
    <div class="label">EXTERNAL DOCUMENT PLACEHOLDER</div>
  </body>
</html>`;

export async function installVisualResourceMocks(page: Page): Promise<void> {
  await page.route('**/*', async (route) => {
    const request = route.request();
    const method = request.method();
    const url = new URL(request.url());

    if (!['GET', 'HEAD'].includes(method)) {
      await route.abort('blockedbyclient');
      return;
    }

    if (url.origin === 'https://online.fliphtml5.com') {
      if (request.resourceType() === 'document' && normalizePathname(url.pathname) === '/akihj/0922-5Hx9/') {
        await route.fulfill({ body: flipHtmlPlaceholder, contentType: 'text/html; charset=utf-8', status: 200 });
      } else {
        await route.abort('blockedbyclient');
      }
      return;
    }

    if (url.origin === 'https://leaders.yeg.jp') {
      const placeholder = imagePlaceholders.get(normalizePathname(url.pathname));

      if (placeholder) {
        await route.fulfill({ body: placeholderSvg(placeholder), contentType: 'image/svg+xml', status: 200 });
      } else {
        await route.abort('blockedbyclient');
      }
      return;
    }

    if (url.origin === 'https://fonts.googleapis.com') {
      await route.fulfill({ body: '', contentType: 'text/css; charset=utf-8', status: 200 });
      return;
    }

    if (url.origin === 'https://fonts.gstatic.com') {
      await route.abort('blockedbyclient');
      return;
    }

    if (url.origin === localOrigin && request.resourceType() === 'media') {
      await route.fulfill({ body: '', contentType: 'video/mp4', status: 204 });
      return;
    }

    const isAllowedExternalResource =
      url.search === '' && allowedExternalResources.has(`${url.origin}${url.pathname}`);

    if (url.origin !== localOrigin && !isAllowedExternalResource) {
      await route.abort('blockedbyclient');
      return;
    }

    await route.continue();
  });
}

export async function stabilizeVisualPage(page: Page, stylesheetPath: string): Promise<void> {
  await page.goto('/', { waitUntil: 'domcontentloaded' });
  await expect(page.locator('main.l-main')).toBeVisible();
  await page.addStyleTag({ path: stylesheetPath });

  await page.evaluate(async () => {
    document.querySelectorAll('video').forEach((video) => {
      video.pause();
      video.autoplay = false;
      video.muted = true;
    });

    const firstScheduleTab = document.querySelector<HTMLElement>(
      '.yeg-top-schedule .p-fp-news__tab-labels-item:first-child',
    );
    firstScheduleTab?.click();

    for (let position = 0; position < document.documentElement.scrollHeight; position += window.innerHeight) {
      window.scrollTo(0, position);
      await new Promise((resolve) => window.setTimeout(resolve, 20));
    }

    await Promise.all(
      Array.from(document.images, (image) => (image.complete ? Promise.resolve() : image.decode().catch(() => undefined))),
    );
    await document.fonts.ready;
    window.scrollTo(0, 0);
    await new Promise<void>((resolve) => requestAnimationFrame(() => requestAnimationFrame(() => resolve())));
  });

  await page.mouse.move(0, 0);
  await expect(page.locator('.yeg-top-leaders-guide__embed')).toBeVisible();
  await expect(page.locator('.yeg-top-leaders-guide iframe')).toHaveCount(1);
  await expect(page.locator('.yeg-top-access__image')).toHaveCount(2);
}

export async function attachVisualEnvironment(
  page: Page,
  browser: Browser,
  browserName: BrowserName,
  testInfo: TestInfo,
): Promise<void> {
  const browserEnvironment = await page.evaluate(() => {
    const bodyStyle = window.getComputedStyle(document.body);
    const heading = document.querySelector('main h2');
    const headingStyle = heading ? window.getComputedStyle(heading) : null;

    return {
      bodyFontFamily: bodyStyle.fontFamily,
      devicePixelRatio: window.devicePixelRatio,
      documentFontsStatus: document.fonts.status,
      headingFontFamily: headingStyle?.fontFamily,
      locale: navigator.language,
      timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
      viewport: { height: window.innerHeight, width: window.innerWidth },
    };
  });
  const environment = {
    architecture: process.arch,
    browser: browserName,
    browserVersion: browser.version(),
    node: process.version,
    osPlatform: os.platform(),
    osRelease: os.release(),
    playwrightProject: testInfo.project.name,
    snapshotDirectory: path.relative(process.cwd(), testInfo.snapshotDir),
    ...browserEnvironment,
  };

  await testInfo.attach('visual-environment', {
    body: Buffer.from(JSON.stringify(environment, null, 2)),
    contentType: 'application/json',
  });
}
