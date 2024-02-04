// @ts-check
const { test, expect } = require('@playwright/test')
const { exec } = require('child_process')
const fs = require('fs')

const config = {
  url: {
    local: 'http://localhost:9500?self=true'
  },
  user: {
    name: 'tester@case.de',
    password: 'password'
  }
}

test('login', async ({ page }) => {
  page.on('pageerror', exception => {
    throw new Error(`Uncaught exception: "${exception}"`)
  })

  await prepareLocalUser(config)
  await page.goto(config.url.local)

  // Login
  await page.getByRole('textbox', { name: 'username' }).fill(config.user.name)
  await page.getByPlaceholder('password').fill(config.user.password)
  await page.getByRole('button', { name: 'login' }).click()

  // Search and use image from pixabay
  await page.locator('#pixabay_q').fill('Berge')
  await page.locator('[data-click="pixabay.search"]').click()
  await page.locator('#pixabay_results div.image:first-child').click()

  await page.waitForTimeout(1000)
})

async function prepareLocalUser (config) {
  // Delete user
  exec('php cli.php delete ' + config.user.name, (error, stdout, stderr) => {
    if (error) {
      throw new Error(stdout + ' ' + stderr)
    }

    console.log('User deleted ', stdout, stderr)
  })

  // Create user
  exec('php cli.php create ' + config.user.name + ' ' + config.user.password, (error, stdout, stderr) => {
    if (error) {
      throw new Error(stdout + ' ' + stderr)
    }

    console.log('User created ', stdout, stderr)
  })
}
