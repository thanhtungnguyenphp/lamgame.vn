const puppeteer = require('puppeteer');
const fs = require('fs');
const path = require('path');

const GAMES_DIR = path.join(__dirname, '..', 'public', 'games');
const THUMB_DIR = path.join(__dirname, '..', 'public', 'storage', 'mini-game-thumbs');

(async () => {
  fs.mkdirSync(THUMB_DIR, { recursive: true });

  const slugs = fs.readdirSync(GAMES_DIR).filter(d =>
    fs.statSync(path.join(GAMES_DIR, d)).isDirectory() &&
    fs.existsSync(path.join(GAMES_DIR, d, 'index.html'))
  );

  const browser = await puppeteer.launch({
    headless: true,
    args: ['--no-sandbox', '--disable-setuid-sandbox', '--disable-gpu'],
  });

  for (const slug of slugs) {
    const outFile = path.join(THUMB_DIR, `${slug}.webp`);
    if (fs.existsSync(outFile)) {
      console.log(`⏭ ${slug} (exists)`);
      continue;
    }

    try {
      const page = await browser.newPage();
      await page.setViewport({ width: 800, height: 600 });
      const fileUrl = `file://${path.join(GAMES_DIR, slug, 'index.html')}`;
      await page.goto(fileUrl, { waitUntil: 'networkidle2', timeout: 15000 });
      // Wait a bit for game to render initial state
      await new Promise(r => setTimeout(r, 2000));
      await page.screenshot({ path: outFile, type: 'webp', quality: 85 });
      await page.close();
      console.log(`✅ ${slug}`);
    } catch (e) {
      console.log(`❌ ${slug}: ${e.message}`);
    }
  }

  await browser.close();
  console.log(`\nDone! Thumbnails saved to ${THUMB_DIR}`);
})();
