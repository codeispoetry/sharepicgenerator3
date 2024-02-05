// @ts-check
const { test, expect } = require('@playwright/test')
const { exec } = require('child_process')
const fs = require('fs-extra');


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
  await page.locator('#pixabay_q').fill('Berge')
  await page.locator('[data-click="pixabay.search"]').click()
  await page.locator('#pixabay_results div.image:first-child').click()


  // Change color of copyright
  await page.locator('button[data-pseudoselect="copyright"]').click()
  await page.locator('#copyright_color').fill('#f96654')

  // Use top menu
  await page.locator('#menu_add').hover()
  await page.locator('#menu_add button:nth-child(2)').click()

  // Edit and move the add pic
  const element = await page.locator('#sharepic > [data-id="addpicture"]').first();
  const boundingBox = await element.boundingBox();
  await page.mouse.move(boundingBox.x + boundingBox.width -1, boundingBox.y + 1 );
  await page.mouse.down();
  await page.mouse.move(boundingBox.x + boundingBox.width + 250, boundingBox.y + 50 );
  await page.mouse.up();

  await page.locator('#addpic_pic_angular').click()
  await page.locator('#addpic_text_right').click()
  await page.locator('#sharepic > [data-id="addpicture"] .ap_text').fill('Test Text');
  await page.locator('#cockpit_addpicture input[type="file"]').setInputFiles('tests/image.jpg');



  // Wait for the image to be loaded
  await page.waitForTimeout(3000)
  // Download
  const downloadPromise = page.waitForEvent('download')
  await page.locator('#inlinecockpit button[data-click="api.create"]').click()
  const download = await downloadPromise
  const path = await download.path()
  fs.move(path, 'tests/tmp/test-sharepic.png', 
    { overwrite: true },
    function (err) {
      if (err) throw err
      console.log('See tests/tmp/test-sharepic.png');
    }
  );

  // Timeout needed to wait for the download to be moved.
  await page.waitForTimeout(100)

})

async function prepareLocalUser (config) {
  // Delete user
  exec('php cli.php delete ' + config.user.name, (error, stdout, stderr) => {
    if (error) {
      throw new Error(stdout + ' ' + stderr)
    }

    console.log('User deleted');
  })

  // Create user
  exec('php cli.php create ' + config.user.name + ' ' + config.user.password, (error, stdout, stderr) => {
    if (error) {
      throw new Error(stdout + ' ' + stderr)
    }

    console.log('User created ')
  })
}
