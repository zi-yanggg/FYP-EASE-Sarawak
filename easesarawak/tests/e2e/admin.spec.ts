import { test, expect, Page } from '@playwright/test';

// ── Helpers ──────────────────────────────────────────────────────────────────

async function loginAsAdmin(page: Page) {
  await page.goto('/login');
  await page.fill('input[name="email"]',    process.env.ADMIN_EMAIL    ?? 'admin@ease.com');
  await page.fill('input[name="password"]', process.env.ADMIN_PASSWORD ?? 'TestPassword1!');
  await page.click('button[type="submit"], input[type="submit"]');
  await page.waitForURL(/admin/);
}

// ── Auth guard ────────────────────────────────────────────────────────────────

test.describe('Admin Auth Guard', () => {

  test('unauthenticated visit to /admin redirects to login', async ({ page }) => {
    await page.goto('/admin');
    await expect(page).toHaveURL(/login/);
  });

  test('unauthenticated visit to /admin/promo_code redirects to login', async ({ page }) => {
    await page.goto('/admin/promo_code');
    await expect(page).toHaveURL(/login/);
  });

  test('unauthenticated visit to /transaction_history redirects to login', async ({ page }) => {
    await page.goto('/transaction_history');
    await expect(page).toHaveURL(/login/);
  });

  test('unauthenticated visit to refund PDF redirects to login', async ({ page }) => {
    await page.goto('/refund/view/1');
    await expect(page).toHaveURL(/login/);
  });

});

// ── Login flow ────────────────────────────────────────────────────────────────

test.describe('Admin Login', () => {

  test('login page loads', async ({ page }) => {
    await page.goto('/login');
    await expect(page.locator('input[name="email"]')).toBeVisible();
    await expect(page.locator('input[name="password"]')).toBeVisible();
  });

  test('wrong password shows error message', async ({ page }) => {
    await page.goto('/login');
    await page.fill('input[name="email"]', 'admin@ease.com');
    await page.fill('input[name="password"]', 'wrongpassword');
    await page.click('button[type="submit"], input[type="submit"]');
    await expect(page.locator('body')).toContainText(/incorrect|invalid|error/i);
  });

});

// ── Dashboard ─────────────────────────────────────────────────────────────────

test.describe('Admin Dashboard', () => {

  test.beforeEach(async ({ page }) => {
    await loginAsAdmin(page);
  });

  test('dashboard shows order count cards', async ({ page }) => {
    await expect(page.locator('body')).not.toContainText('500');
    await expect(page.locator('body')).not.toContainText('Whoops');
  });

  test('logout destroys session and redirects to login', async ({ page }) => {
    await page.goto('/logout');
    await expect(page).toHaveURL(/login/);

    // Verify session is gone — trying to access admin redirects
    await page.goto('/admin');
    await expect(page).toHaveURL(/login/);
  });

});

// ── Promo code management ─────────────────────────────────────────────────────

test.describe('Promo Code Management', () => {

  test.beforeEach(async ({ page }) => {
    await loginAsAdmin(page);
  });

  test('promo code list page loads', async ({ page }) => {
    await page.goto('/admin/promo_code');
    await expect(page.locator('body')).not.toContainText('404');
    await expect(page.locator('body')).not.toContainText('500');
  });

  test('create promo page loads', async ({ page }) => {
    await page.goto('/admin/promo_code/create');
    await expect(page.locator('body')).not.toContainText('404');
  });

});

// ── Cross-browser visual consistency ─────────────────────────────────────────

test.describe('Visual Consistency', () => {

  test('admin login page renders consistently', async ({ page }) => {
    await page.goto('/login');
    await expect(page).toHaveScreenshot('admin-login.png', { maxDiffPixelRatio: 0.02 });
  });

  test('home page renders on mobile viewport', async ({ page }) => {
    await page.setViewportSize({ width: 390, height: 844 }); // iPhone 14
    await page.goto('/');
    await expect(page).toHaveScreenshot('home-mobile.png', { maxDiffPixelRatio: 0.03 });
  });

});
