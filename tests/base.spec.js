// @ts-check
const { test } = require('@playwright/test')
const { exec } = require('child_process')
const fs = require('fs-extra')

const config = {
  url: {
    local: 'http://localhost:9500?self=true'
  },
  user: {
    name: 'tester@case.de',
    password: 'password'
  }
}

test.beforeAll('Clear user', async () => {
  await prepareLocalUser(config)
})

test.beforeEach(async ({ page }) => {
  page.on('pageerror', exception => {
    throw new Error(`Uncaught exception: "${exception}"`)
  })

  await page.goto(config.url.local)

  // Login
  await page.getByRole('textbox', { name: 'username' }).fill(config.user.name)
  await page.getByPlaceholder('password').fill(config.user.password)
  await page.getByRole('button', { name: 'login' }).click()
})

test('Overall test', async ({ page }) => {
  // Search and use image from pixabay
  await page.locator('#tab_btn_search').click()
  await page.locator('#pixabay_q').fill('Berge')
  await page.locator('[onclick="pixabay.search()"]').click()
  await page.locator('#pixabay_results div.image:first-child').click()

  // Change color of copyright
  await page.locator('#tab_btn_background').click()
  await page.locator('#copyright_color').fill('#f96654')

  // Add eyecatcher, then edit and move it
  await page.getByTitle('Eyecatcher').click()
  await page.getByText('Add sticker').click()

  const element = await page.locator('#sharepic > [data-cockpit="eyecatcher"]').first()
  const boundingBox = await element.boundingBox()
  await page.mouse.move(boundingBox.x + boundingBox.width / 2, boundingBox.y + 10)
  await page.mouse.down()
  await page.mouse.move(100, 100)
  await page.mouse.up()
  await element.fill('Hello World')

  // await page.locator('#cockpit_addpicture input[type="file"]').setInputFiles('tests/image.jpg')

  // Wait for the image to be loaded
  await page.waitForTimeout(1000)
  // Download
  const downloadPromise = page.waitForEvent('download')
  await page.locator('.workbench-below > [onclick="api.create()"]').click()
  const download = await downloadPromise
  const path = await download.path()
  fs.move(path, 'tests/tmp/test-sharepic.png',
    { overwrite: true },
    function (err) {
      if (err) throw err
      console.log('See tests/tmp/test-sharepic.png')
    }
  )

  // Timeout needed to wait for the download to be moved.
  await page.waitForTimeout(100)
})

async function prepareLocalUser (config) {
  // Delete user
  exec('php cli.php delete ' + config.user.name, (error, stdout, stderr) => {
    if (error) {
      throw new Error(stdout + ' ' + stderr)
    }

    console.log('User deleted')
  })

  // Create user
  exec('php cli.php create ' + config.user.name + ' ' + config.user.password, (error, stdout, stderr) => {
    if (error) {
      throw new Error(stdout + ' ' + stderr)
    }

    console.log('User created ')
  })

  // Delete sharepic
  exec('rm tests/tmp/test-sharepic.png', () => {
    console.log('Old downloaded sharepic deleted')
  })
}
