# Browser Debugging Guide - Tom Select

## 🔴 Nếu vẫn thấy checkbox list thay vì Tom Select

### Step 1: Hard Refresh Browser (QUAN TRỌNG!)

**Mac:**
```
Cmd + Shift + R
```

**Windows/Linux:**
```
Ctrl + Shift + F5
```

Hoặc:
1. Mở DevTools (F12)
2. Right-click vào nút Refresh
3. Chọn **"Empty Cache and Hard Reload"**

---

### Step 2: Check Browser Console

**Mở Console:**
- Press `F12` → Tab "Console"

**Kiểm tra errors:**

✅ **GOOD - No errors:**
```
VITE ready in 123ms
```

❌ **BAD - Has errors:**
```
Failed to load module script: Expected a JavaScript module
SyntaxError: Unexpected token
```

---

### Step 3: Check Network Tab

**Mở Network:**
- Press `F12` → Tab "Network"
- Reload page (Cmd+R)

**Kiểm tra:**

1. **job-form-5f4puGbh.js**
   - Status: `200 OK` ✅
   - Status: `404 Not Found` ❌ → Chạy `npm run build`
   
2. **job-form-CR_xEeZJ.css**
   - Status: `200 OK` ✅
   - Status: `404 Not Found` ❌ → Chạy `npm run build`

3. **/api/jobs/options/form-data**
   - Status: `200 OK` ✅
   - Status: `500` or `404` ❌ → Check Laravel logs

---

### Step 4: Check Elements Tab

**Inspect Skills Field:**
- F12 → Tab "Elements"
- Find element: `<select id="required_skills">`

**Good HTML (Tom Select rendered):**
```html
<div class="ts-wrapper">
  <div class="ts-control">
    <input placeholder="🔍 Tìm và chọn kỹ năng...">
  </div>
</div>
```

**Bad HTML (Not initialized):**
```html
<select id="required_skills" multiple>
  <!-- Just plain select -->
</select>
```

---

### Step 5: Check JavaScript Loaded

**In Console, type:**
```javascript
window.JobForm
```

**Expected output:**
```javascript
{
  initMultiSelect: ƒ,
  updateCounter: ƒ,
  populateOptions: ƒ,
  TomSelect: ƒ
}
```

**If `undefined`:**
→ JavaScript không load → Check network tab

---

### Step 6: Manually Test Tom Select

**In Console, try:**
```javascript
// Check if Tom Select is on the page
document.querySelector('#required_skills').tomselect
```

**Expected:**
```javascript
TomSelect {...} // Object với nhiều properties
```

**If `undefined`:**
→ Tom Select chưa initialize

**Force initialize:**
```javascript
// Try to initialize manually
import('tom-select').then(module => {
  const TomSelect = module.default;
  const select = new TomSelect('#required_skills', {
    plugins: ['remove_button'],
    placeholder: 'Test...'
  });
  console.log('Manual init:', select);
});
```

---

## 🛠️ Common Fixes

### Fix 1: Clear ALL Caches

```bash
# Backend
docker-compose exec php php artisan optimize:clear

# Rebuild assets
npm run build

# Restart services
docker-compose restart
```

---

### Fix 2: Check Vite is serving correctly

**Test URL in browser:**
```
https://lamgame.localhost/build/assets/job-form-5f4puGbh.js
```

**Should see:**
- JavaScript code (minified)
- Status 200

**If 404:**
```bash
# Rebuild
npm run build

# Check file exists
ls -la public/build/assets/job-form-*
```

---

### Fix 3: Check @vite directive

**View source (Cmd+U):**

Look for:
```html
<script type="module" src="http://localhost:5173/@vite/client"></script>
<script type="module" src="http://localhost:5173/resources/js/job-form.js"></script>
```

OR (production build):
```html
<script type="module" src="/build/assets/job-form-5f4puGbh.js"></script>
<link rel="stylesheet" href="/build/assets/job-form-CR_xEeZJ.css">
```

**If missing @vite tags:**
→ Check `layouts/job-admin.blade.php` has `@stack('scripts')`

---

### Fix 4: Check Layout File

**File:** `resources/admin-themes/default/views/layouts/job-admin.blade.php`

**Must have:**
```blade
<head>
  @stack('styles')
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
  @yield('content')
  @stack('scripts')  <!-- MUST HAVE THIS -->
</body>
```

---

### Fix 5: Disable Browser Extensions

Some extensions block JavaScript modules:
- Disable ad blockers
- Disable privacy extensions
- Try incognito mode (Cmd+Shift+N)

---

## 🧪 Test Script

**Run in browser console:**
```javascript
// Comprehensive test
(function() {
  console.log('=== Tom Select Debug ===');
  
  // 1. Check window.JobForm
  console.log('1. JobForm loaded?', typeof window.JobForm !== 'undefined' ? '✅' : '❌');
  
  // 2. Check select element exists
  const select = document.querySelector('#required_skills');
  console.log('2. Select element exists?', select ? '✅' : '❌');
  
  // 3. Check Tom Select instance
  if (select) {
    console.log('3. Tom Select initialized?', select.tomselect ? '✅' : '❌');
    
    if (select.tomselect) {
      console.log('   - Options count:', Object.keys(select.tomselect.options).length);
      console.log('   - Selected items:', select.tomselect.items);
    }
  }
  
  // 4. Check API response
  fetch('/api/jobs/options/form-data')
    .then(r => r.json())
    .then(data => {
      const skills = data.data?.attributes?.required_skills?.options;
      console.log('4. API returns skills?', skills?.length > 0 ? '✅' : '❌');
      if (skills) console.log('   - Skills count:', skills.length);
    })
    .catch(e => console.log('4. API Error:', '❌', e.message));
    
  console.log('======================');
})();
```

---

## 📸 Screenshot Comparison

### Before (Checkbox - BAD)
```
☐ Unity
☐ Dart
☐ Firebase
☐ REST API
...
```

### After (Tom Select - GOOD)
```
┌─────────────────────────────────────┐
│ 🔍 Tìm và chọn kỹ năng...          │
└─────────────────────────────────────┘
Chưa chọn kỹ năng nào
```

---

## 💡 Pro Tips

1. **Always use Incognito first** - Eliminates cache issues
2. **Check View Source** - Verify @vite tags are there
3. **Mobile simulation** - F12 → Toggle device (Cmd+Shift+M)
4. **Network throttling** - Test on slow connection
5. **Preserve log** - In Console, check "Preserve log"

---

## 🆘 Still Not Working?

**Last resort checklist:**

```bash
# 1. Completely rebuild
rm -rf public/build
npm run build

# 2. Clear everything
docker-compose exec php php artisan optimize:clear
docker-compose restart

# 3. Check file permissions
ls -la public/build/assets/job-form-*
# Should be readable (rw-r--r--)

# 4. Test API directly
curl https://lamgame.localhost/api/jobs/options/form-data

# 5. Check PHP errors
docker-compose logs php | tail -50

# 6. Check Nginx errors
docker-compose logs nginx | tail -50
```

**If STILL not working:**
1. Take screenshot of:
   - Browser console (F12)
   - Network tab (all requests)
   - Elements tab (HTML for #required_skills)
2. Check `storage/logs/laravel.log`
3. Verify Docker services: `docker-compose ps`

---

## ✅ Success Indicators

You'll know it's working when:
1. ✅ No console errors
2. ✅ Search box with 🔍 icon appears
3. ✅ Clicking opens dropdown with options
4. ✅ Selecting shows tags: `[Unity ×]`
5. ✅ Counter shows: "X kỹ năng đã chọn"

---

**Current Status:**
- [x] Tom Select installed
- [x] Files created
- [x] Vite built successfully
- [x] Cache cleared
- [ ] Browser shows Tom Select → **TEST NOW!**

**URL:** https://lamgame.localhost/admin/jobs/create
