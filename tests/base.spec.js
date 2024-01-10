// @ts-check
const { test,expect } = require('@playwright/test')

test('login', async ({ page }) => {
  page.on('pageerror', exception => {
    throw new Error(`Uncaught exception: "${exception}"`)
  })

  await page.goto('http://localhost:9500')

  await page.getByRole('textbox', { name: 'username' }).fill('mail@tom-rose.de');
  await page.getByPlaceholder('password').fill('geheim');
  await page.getByRole('button', { name: 'login' }).click()
  await expect(page).toHaveScreenshot();

  await page.getByRole('link', { name: 'Logout' }).click();
  await expect(page).toHaveScreenshot();

})
