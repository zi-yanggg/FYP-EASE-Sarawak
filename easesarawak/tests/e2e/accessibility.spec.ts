import { test, expect } from '@playwright/test';
import { checkA11y, injectAxe } from 'axe-playwright';

const publicPages = ['/', '/booking', '/bookingdetail', '/about', '/policy', '/login'];

for (const path of publicPages) {
  test(`${path} has no critical accessibility violations`, async ({ page }) => {
    await page.goto(path);
    await injectAxe(page);
    await checkA11y(page, undefined, {
      axeOptions: {
        runOnly: { type: 'tag', values: ['wcag2a', 'wcag2aa'] },
      },
      detailedReport: true,
      detailedReportOptions: { html: true },
    });
  });
}
