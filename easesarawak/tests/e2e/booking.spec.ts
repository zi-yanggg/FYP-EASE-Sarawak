import { test, expect } from '@playwright/test';

test.describe('Public Booking Flow', () => {

  test('home page loads and shows service prices', async ({ page }) => {
    await page.goto('/');
    await expect(page).toHaveTitle(/EASE/i);
    // Prices should be visible (loaded from DB via service_management)
    await expect(page.locator('body')).toContainText(/RM/i);
  });

  test('booking page loads correctly', async ({ page }) => {
    await page.goto('/booking');
    await expect(page).toHaveURL(/booking/);
    await expect(page.locator('body')).not.toContainText('404');
  });

  test('booking detail page loads', async ({ page }) => {
    await page.goto('/bookingdetail');
    await expect(page.locator('body')).not.toContainText('404');
  });

  test('customer detail page loads', async ({ page }) => {
    await page.goto('/bookingcustomerdetail');
    await expect(page.locator('body')).not.toContainText('404');
  });

  test('promo code input accepts valid format', async ({ page }) => {
    await page.goto('/bookingcustomerdetail');
    const promoInput = page.locator('input[name="promo_code"], input[placeholder*="promo" i]');
    if (await promoInput.count() > 0) {
      await promoInput.fill('TESTCODE');
      await expect(promoInput).toHaveValue('TESTCODE');
    }
  });

  test('mobile viewport - booking page is usable', async ({ page }) => {
    await page.setViewportSize({ width: 375, height: 812 }); // iPhone SE
    await page.goto('/booking');
    await expect(page.locator('body')).not.toContainText('404');
    // No horizontal scroll
    const bodyWidth = await page.evaluate(() => document.body.scrollWidth);
    const viewportWidth = await page.evaluate(() => window.innerWidth);
    expect(bodyWidth).toBeLessThanOrEqual(viewportWidth + 5); // 5px tolerance
  });

});

test.describe('Promo Code API', () => {

  test('invalid promo code returns error response', async ({ request }) => {
    const response = await request.post('/checkPromoCode', {
      data: { promo_code: 'DEFINITELYINVALID99' },
      headers: { 'Content-Type': 'application/json' },
    });

    const body = await response.json();
    expect(body.valid).toBe(false);
  });

  test('empty promo code returns error', async ({ request }) => {
    const response = await request.post('/checkPromoCode', {
      data: { promo_code: '' },
      headers: { 'Content-Type': 'application/json' },
    });

    const body = await response.json();
    expect(body.valid).toBe(false);
  });

});

test.describe('Refund Form', () => {

  test('refund form is accessible from home page', async ({ page }) => {
    await page.goto('/#refund-form');
    await expect(page.locator('body')).not.toContainText('404');
  });

  test('refund PDF requires login', async ({ request }) => {
    const response = await request.get('/refund/view/1', { maxRedirects: 0 });
    // Must redirect (302) not serve content (200)
    expect(response.status()).toBe(302);
    expect(response.headers()['location']).toContain('login');
  });

});
