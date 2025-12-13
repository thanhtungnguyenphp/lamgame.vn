# Quick Testing Guide: Skills & Benefits UX Optimization

## Before Testing
```bash
# Clear caches
php artisan view:clear
php artisan cache:clear

# Start services (if not running)
docker-compose up -d

# Or using make
make start
```

## Access the Page
1. Navigate to: https://lamgame.localhost/viec-lam-game
2. Click on any job posting
3. Look for the new "Kỹ năng" and "Phúc lợi" section below job-meta

## Visual Checklist

### Desktop (>1024px)
- [ ] Skills and Benefits appear in one gray box
- [ ] "Kỹ năng:" label aligns with "Phúc lợi:" label (both ~70px width)
- [ ] Pills are white with gray border (NOT gradient purple/green)
- [ ] No emoji icons (🎯, 🎁)
- [ ] First 4 items visible, "+N more" if >4 items
- [ ] Box is inside job-header-card (white card with shadow)
- [ ] Box appears BELOW the 4 meta items (location, salary, type, date)
- [ ] Box appears ABOVE action buttons (Ứng tuyển ngay, Lưu việc làm)

### Tablet (768-1023px)
- [ ] Same as desktop but narrower
- [ ] Pills wrap to multiple lines if needed
- [ ] No horizontal scrolling

### Mobile (<576px)
- [ ] "Kỹ năng:" and "Phúc lợi:" stack vertically (not side-by-side)
- [ ] Pills wrap naturally
- [ ] No label min-width (labels take natural width)
- [ ] No horizontal overflow
- [ ] Easy to tap "+N more" buttons (min 44px height)

## Interaction Testing

### Skills Toggle
1. Find job with >4 skills
2. Click "+N kỹ năng" button
3. **Expected:** Hidden skills appear below
4. **Expected:** Button text changes to "↑ Thu gọn"
5. Click "↑ Thu gọn"
6. **Expected:** Hidden skills disappear
7. **Expected:** Button text changes back to "+N kỹ năng"

### Benefits Toggle
1. Find job with >4 benefits
2. Click "+N phúc lợi" button
3. **Expected:** Hidden benefits appear below
4. **Expected:** Button text changes to "↑ Thu gọn"
5. Click "↑ Thu gọn"
6. **Expected:** Hidden benefits disappear
7. **Expected:** Button text changes back to "+N phúc lợi"

## Edge Cases

### No Skills
- [ ] If job has no skills, only "Phúc lợi" row appears
- [ ] No error or empty space

### No Benefits
- [ ] If job has no benefits, only "Kỹ năng" row appears
- [ ] No error or empty space

### Both Empty
- [ ] If job has neither, entire .job-quick-info box doesn't appear
- [ ] No visual artifact

### Long Text
- [ ] Skills/benefits with long names (>30 chars) wrap properly
- [ ] No text overflow or cutting off

### Many Items (>10)
- [ ] First 4 visible, rest hidden
- [ ] Toggle button works correctly
- [ ] No performance lag when expanding

## Browser Testing

### Chrome/Edge
- [ ] Layout correct
- [ ] Interactions smooth
- [ ] No console errors

### Firefox
- [ ] Layout correct
- [ ] Flexbox rendering OK
- [ ] No console errors

### Safari (Desktop)
- [ ] Layout correct
- [ ] Border radius renders properly
- [ ] No console errors

### Mobile Safari (iOS)
- [ ] Touch targets adequate
- [ ] Pills readable (not too small)
- [ ] Toggle works on tap

### Chrome Mobile (Android)
- [ ] Same as iOS
- [ ] No zoom issues

## Accessibility Testing

### Keyboard Navigation
1. Tab through page
2. **Expected:** Can focus on "+N kỹ năng" button
3. **Expected:** Can focus on "+N phúc lợi" button
4. Press Enter on focused button
5. **Expected:** Toggle works

### Screen Reader (VoiceOver/NVDA)
1. Navigate to skills section
2. **Expected:** "Kỹ năng:" announced
3. **Expected:** Each pill content announced
4. **Expected:** Button announced with count ("+3 kỹ năng")

### Color Contrast
1. Use browser DevTools or contrast checker
2. Check gray text (#374151) on white background
3. **Expected:** Ratio >4.5:1 (WCAG AA)
4. Check purple button (#667eea)
5. **Expected:** Ratio >4.5:1 (WCAG AA)

## Performance Testing

### Lighthouse
1. Open Chrome DevTools
2. Run Lighthouse audit
3. **Expected:** Performance >90
4. **Expected:** Accessibility >90
5. **Expected:** CLS <0.1

### Network
1. Open Network tab
2. Reload page
3. **Expected:** No additional CSS/JS requests for quick-info
4. **Expected:** Total page size unchanged or smaller

## Comparison Testing

### Before (Old Layout)
If you have screenshots or can rollback:
1. Compare vertical space: Old ~374px vs New ~100px
2. Compare visual weight: Old (colorful) vs New (neutral)
3. Compare readability: Which is easier to scan?

### After (New Layout)
1. Take screenshot of new layout
2. Compare with TopCV screenshot (docs/error_screen/ui_topcv.png)
3. **Expected:** Similar minimalist vibe

## Bug Reporting

If you find issues, note:
- Browser & version
- Screen size (width x height)
- Steps to reproduce
- Screenshot (if visual bug)
- Console errors (if any)

Submit to: GitHub issues or Slack #dev-lamgame

## Quick Fixes

### Pills too wide on mobile
Add to CSS:
```css
@media (max-width: 576px) {
    .info-pill {
        font-size: 0.8rem;
        padding: 0.25rem 0.5rem;
    }
}
```

### Label alignment off
Adjust min-width:
```css
.quick-info-label {
    min-width: 80px; /* Increase from 70px */
}
```

### Hidden content doesn't align
Check padding-left:
```css
.quick-info-content.hidden-skills,
.quick-info-content.hidden-benefits {
    padding-left: 92px; /* Match label width + gap */
}
```

## Success Criteria

✅ **Pass:** New layout is cleaner, more compact, and easier to scan
✅ **Pass:** All interactions work smoothly
✅ **Pass:** No visual bugs on any screen size
✅ **Pass:** No console errors
✅ **Pass:** Accessibility score >90

❌ **Fail:** Revert and investigate if critical issues found

## Next Steps After Testing

1. If all tests pass → Deploy to production
2. Monitor GA for 1 week
3. Collect user feedback
4. Iterate if needed

---

**Test completed by:** _____________  
**Date:** _____________  
**Result:** ☐ Pass ☐ Fail (describe below)  
**Notes:**
