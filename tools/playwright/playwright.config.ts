import { defineConfig, devices } from '@playwright/test';

export default defineConfig({
  testDir: './tests',
  outputDir: 'test-results',
  snapshotPathTemplate: '{testDir}/visual/__screenshots__/{projectName}/{arg}{ext}',
  fullyParallel: true,
  forbidOnly: true,
  retries: 1,
  reporter: [
    ['list'],
    ['html', { outputFolder: 'playwright-report', open: 'never' }],
  ],
  use: {
    baseURL: 'http://localhost:8082',
    navigationTimeout: 30_000,
    actionTimeout: 10_000,
    screenshot: 'only-on-failure',
    trace: 'on-first-retry',
    colorScheme: 'light',
    locale: 'ja-JP',
    reducedMotion: 'reduce',
    timezoneId: 'Asia/Tokyo',
  },
  projects: [
    {
      name: 'desktop-chromium',
      use: {
        browserName: 'chromium',
        viewport: { width: 1440, height: 900 },
      },
    },
    {
      name: 'mobile-chromium',
      use: {
        browserName: 'chromium',
        viewport: { width: 390, height: 844 },
        screen: { width: 390, height: 844 },
        isMobile: true,
        hasTouch: true,
      },
    },
    {
      name: 'mobile-webkit',
      use: {
        ...devices['iPhone 13'],
      },
    },
  ],
});
