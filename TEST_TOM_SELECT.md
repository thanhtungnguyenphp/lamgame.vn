# Test Checklist - Tom Select Implementation

## ✅ Setup Complete

- [x] Tom Select installed (`npm install tom-select`)
- [x] Files created (multiselect.js, job-form.js, CSS)
- [x] Vite config updated with job-form.js
- [x] Production build successful
- [x] Manifest.json includes job-form assets
- [x] Laravel cache cleared
- [x] Nginx restarted
- [x] Docker services running

## 🧪 Manual Testing Steps

### 1. Open the Form

**URL:** https://lamgame.localhost/admin/jobs/create

**Expected:**
- Page loads without errors
- No JavaScript errors in console (F12)
- Form displays properly

---

### 2. Check Tom Select Elements

**Look for:**

**"Kỹ năng yêu cầu" field:**
```
┌─────────────────────────────────────┐
│ 🔍 Tìm và chọn kỹ năng...          │
└─────────────────────────────────────┘
Chưa chọn kỹ năng nào
```

**"Phúc lợi" field:**
```
┌─────────────────────────────────────┐
│ 🔍 Tìm và chọn phúc lợi...         │
└─────────────────────────────────────┘
Chưa chọn phúc lợi nào
```

**If you see plain select boxes instead:**
- Check browser console for errors
- Verify network tab shows job-form.js loaded (200 OK)
- Hard refresh: Cmd+Shift+R (Mac) or Ctrl+Shift+R

---

### 3. Test Skills Multiselect

#### 3.1 Click to Open
- Click on "Kỹ năng yêu cầu" field
- **Expected:** Dropdown opens with list of skills

#### 3.2 Search
- Type "unity" in search box
- **Expected:** List filters to show only Unity
- **Expected:** Search icon (🔍) visible

#### 3.3 Select Item
- Click on "Unity" option
- **Expected:** Tag appears: `[Unity ×]`
- **Expected:** Counter updates: "1 kỹ năng đã chọn"
- **Expected:** Checkmark (✓) appears next to Unity in dropdown

#### 3.4 Select Multiple
- Search and select "Firebase"
- Search and select "PHP"
- **Expected:** 3 tags displayed
- **Expected:** Counter: "3 kỹ năng đã chọn"

#### 3.5 Remove Item
- Click × button on "Unity" tag
- **Expected:** Tag disappears
- **Expected:** Counter: "2 kỹ năng đã chọn"
- **Expected:** Unity no longer has checkmark in dropdown

#### 3.6 Clear All
- Remove all tags
- **Expected:** Counter: "Chưa chọn kỹ năng nào"
- **Expected:** Field shows placeholder

---

### 4. Test Benefits Multiselect

Repeat all steps from section 3 for "Phúc lợi" field.

**Sample benefits to test:**
- Bảo hiểm sức khỏe
- 13th-salary
- remote-work

---

### 5. Test Search with Vietnamese

- Search for "Bảo hiểm"
- **Expected:** Filters correctly with Vietnamese characters
- Search for "sức khỏe"
- **Expected:** Shows matching benefits

---

### 6. Test Keyboard Navigation

#### 6.1 Tab Navigation
- Press Tab to focus on Skills field
- **Expected:** Field gets focus, dropdown opens

#### 6.2 Arrow Keys
- Press Down Arrow
- **Expected:** First option highlighted
- Press Down Arrow again
- **Expected:** Next option highlighted

#### 6.3 Enter to Select
- With option highlighted, press Enter
- **Expected:** Item selected, tag appears

#### 6.4 Escape to Close
- Press Escape
- **Expected:** Dropdown closes

---

### 7. Test Form Submission

#### 7.1 Fill Form
- Select 2-3 skills
- Select 2-3 benefits
- Fill required fields:
  - Tiêu đề Job
  - Loại Job
  - Cấp độ kinh nghiệm
  - Địa điểm làm việc
  - Mô tả ngắn
  - Mô tả chi tiết
  - Email liên hệ
  - Tên công ty

#### 7.2 Submit
- Click "Đăng Job" button
- **Expected:** Form submits successfully
- **Expected:** Redirects to job list
- **Expected:** Success message appears

#### 7.3 Verify Data
- View created job
- **Expected:** Selected skills are saved
- **Expected:** Selected benefits are saved

---

### 8. Test Browser Console

**Open Developer Tools (F12):**

#### Check for Errors
```javascript
// Should be empty or only warnings
console.error
```

#### Verify Tom Select Loaded
```javascript
// In console, type:
window.JobForm
// Expected: Object with initMultiSelect, updateCounter, etc.
```

#### Check Form Data Before Submit
```javascript
// In console, before submitting:
const form = document.querySelector('form');
const formData = new FormData(form);
for (let [key, value] of formData.entries()) {
    if (key.includes('skill') || key.includes('benefit')) {
        console.log(key, value);
    }
}
// Expected: 
// required_skills[] 123
// required_skills[] 124
// job_benefits[] 45
// etc.
```

---

### 9. Test Network Tab

**Open Developer Tools → Network:**

#### Check Resources Loaded
- `job-form-5f4puGbh.js` → Status 200
- `job-form-CR_xEeZJ.css` → Status 200
- `/api/jobs/options/form-data` → Status 200

#### Check Response Data
- Click on `/api/jobs/options/form-data`
- Preview tab should show:
```json
{
  "data": {
    "attributes": {
      "required_skills": {
        "options": [...]
      },
      "job_benefits": {
        "options": [...]
      }
    }
  }
}
```

---

### 10. Test Mobile Responsive

#### Desktop → Mobile View
- Press F12 → Toggle device toolbar (Cmd+Shift+M)
- Select iPhone or Android device
- **Expected:** Tags wrap properly
- **Expected:** Touch targets are large enough (>44px)
- **Expected:** Dropdown fits in viewport

#### Test Touch Interactions
- Tap to open dropdown
- Tap to select items
- Tap × to remove tags
- **Expected:** All interactions work smoothly

---

## 🐛 Common Issues & Solutions

### Issue 1: Tom Select not appearing (plain select box)

**Solutions:**
```bash
# 1. Check Vite build
npm run build
# Should show job-form.js in output

# 2. Clear browser cache
# Hard refresh: Cmd+Shift+R

# 3. Check browser console for errors
# F12 → Console tab

# 4. Verify file exists
ls -la public/build/assets/job-form-*.js
```

---

### Issue 2: Search not working

**Check:**
- Console errors
- API response has data
- Options are populated

**Debug:**
```javascript
// In console:
const select = document.querySelector('#required_skills');
const tomselect = select.tomselect;
console.log(tomselect.options); // Should show options
```

---

### Issue 3: Form submission missing values

**Debug:**
```javascript
// Before submit:
document.querySelector('form').addEventListener('submit', (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);
    for (let [k,v] of formData.entries()) console.log(k,v);
});
// Then try submit again
```

---

## ✅ Success Criteria

All of the following must work:

- [ ] Form loads without errors
- [ ] Tom Select renders (not plain select)
- [ ] Search filters options
- [ ] Tags display for selected items
- [ ] Remove button (×) works
- [ ] Counter updates correctly
- [ ] Keyboard navigation works
- [ ] Form submits with correct values
- [ ] Job is created with skills/benefits
- [ ] Mobile view is responsive
- [ ] No console errors

---

## 📊 Quick Check Commands

```bash
# Check services running
docker-compose ps
# Expected: php, nginx "Up"

# Check built files exist
ls -la public/build/assets/job-form-*
# Expected: .js and .css files

# Check manifest
cat public/build/manifest.json | grep job-form
# Expected: "resources/js/job-form.js"

# Tail Laravel logs
docker-compose logs -f php
# Watch for errors when accessing form
```

---

## 🎉 Test Complete

If all tests pass:
1. ✅ Implementation is successful
2. ✅ Ready for production deployment
3. ✅ Can apply to other forms (edit, filters, etc.)

**Next Steps:**
- Deploy to staging
- Get QA approval
- Monitor user feedback
- Track metrics (form completion rate, time on page)

---

**Test Date:** ___________  
**Tester:** ___________  
**Browser:** ___________  
**Result:** ✅ Pass / ❌ Fail  
**Notes:** ___________
