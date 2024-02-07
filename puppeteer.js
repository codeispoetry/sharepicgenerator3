const puppeteer = require('puppeteer');

(async () => {
  const path = process.argv[2]
  const w = +process.argv[3]
  const h = +process.argv[4] + 77// 77 is the height of the title bar
  const url = process.argv[5]

  const browser = await puppeteer.launch({
    headless: false,
    defaultViewport: null,
    args: [`--window-size=${w},${h}`, '--no-sandbox']
  })

  const page = await browser.newPage()
  await page.goto(url)

  await page.screenshot({ path, fullPage: true })

  await browser.close()
})()
