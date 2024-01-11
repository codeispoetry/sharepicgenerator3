// @ts-check
const { test,expect } = require('@playwright/test')
const { exec } = require('child_process');

const fs = require('fs');
const PNG = require('pngjs').PNG;
const pixelmatch = require('pixelmatch');

test('login', async ({ page }) => {
  const config = {
    url: 'http://localhost:9500',
    user: {
      name: 'mail@domain.com',
      password: 'test',
    },
    save: {
      name: 'test_' + Math.random().toString(36).substring(3, 8),
    }
  }

  page.on('pageerror', exception => {
    throw new Error(`Uncaught exception: "${exception}"`)
  })

 // Delete user
 exec('php cli.php delete ' + config.user.name, (error, stdout, stderr) => {
  if (error) {
   throw new Error(stdout + ' ' + stderr);
  }
  });

  // Create user
  exec('php cli.php create ' + config.user.name + ' ' + config.user.password, (error, stdout, stderr) => {
    if (error) {
     throw new Error(stdout + ' ' + stderr);
    }
  });

  await page.goto(config.url)

  // Login
  await page.getByRole('textbox', { name: 'username' }).fill('mail@tom-rose.de');
  await page.getByPlaceholder('password').fill('geheim');
  await page.getByRole('button', { name: 'login' }).click()
  //await expect(page).toHaveScreenshot({ maxDiffPixels: 300 });

  // Change sharepic
  await page.getByText('mit Vorname Nachname').fill('mit Thomas Rose');
  await page.getByRole('combobox').selectOption('bayaz.png');

  // Download
  const downloadPromise = page.waitForEvent('download');
  await page.getByRole('button', { name: 'Download' }).click();
  const download1 = await downloadPromise;
  const path1 = await download1.path();

  // Save the sharepic
  page.once('dialog', async dialog => {
    await dialog.accept(config.save.name);
  });
  await page.getByRole('button', { name: 'Save' }).click();
 
  // Reload page
  await page.reload();
 
  // Load save sharepic
  await page.getByText('My sharepics').hover();
  await page.click('button.did-1');

  // Download saved sharepic
  const download1Promise = page.waitForEvent('download');
  await page.getByRole('button', { name: 'Download' }).click();
  const download2 = await download1Promise;
  const path2 = await download2.path();


  // Delete saved sharepic
  page.once('dialog', async dialog => {
    await dialog.accept();
  });
  await page.getByText('My sharepics').hover();
  await page.click('button.did-2');
  
  // Compare images
  const img1 = PNG.sync.read(fs.readFileSync(path1));
  const img2 = PNG.sync.read(fs.readFileSync(path2));
  const {width, height} = img1;
  const diff = new PNG({width, height});

  const numDiffPixels = pixelmatch(
      img1.data, img2.data, diff.data, width, height,
      {threshold: 0.1}
  );

  expect(numDiffPixels).toBe(0);

  // Logout
  await page.getByRole('link', { name: 'Logout' }).click();

})
