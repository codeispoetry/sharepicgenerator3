// @ts-check
const { test } = require('@playwright/test')

test('base', async ({ page }) => {
  page.on('pageerror', exception => {
    throw new Error(`Uncaught exception: "${exception}"`)
  })

  await page.goto('http://localhost:9500')
  await page.getByRole('button', { name: 'einloggen' }).click()

  await page.waitForTimeout(1000)
})
