import path from 'node:path';
import { fileURLToPath } from 'node:url';
import { expect, test } from '@playwright/test';
import {
  attachVisualEnvironment,
  installVisualResourceMocks,
  stabilizeVisualPage,
} from '../support/visual-stability';

const currentDirectory = path.dirname(fileURLToPath(import.meta.url));
const stabilityStylesheet = path.join(currentDirectory, 'visual-stability.css');

test.beforeEach(async ({ page }) => {
  await installVisualResourceMocks(page);
});

test('homepage matches the reviewed visual baseline', async ({ browser, browserName, page }, testInfo) => {
  await stabilizeVisualPage(page, stabilityStylesheet);

  await expect(page).toHaveScreenshot('homepage.png', {
    animations: 'disabled',
    caret: 'hide',
    fullPage: true,
    maxDiffPixels: 0,
    scale: 'css',
    threshold: 0.2,
  });

  const currentScreenshot = testInfo.outputPath('homepage-visual-current.png');
  await page.screenshot({
    animations: 'disabled',
    caret: 'hide',
    fullPage: true,
    path: currentScreenshot,
    scale: 'css',
  });
  await testInfo.attach('ai-visual-screenshot', { contentType: 'image/png', path: currentScreenshot });
  await attachVisualEnvironment(page, browser, browserName, testInfo);
});
