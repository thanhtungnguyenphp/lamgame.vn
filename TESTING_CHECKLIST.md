# 🧪 Admin Jobs - Testing Checklist

## Quick Start

1. Open browser console
2. Load demo script:
```javascript
// Add to page temporarily
const script = document.createElement('script');
script.src = '/js/admin/jobs-demo.js';
document.head.appendChild(script);
```

3. Run tests:
```javascript
jobsDemo.runFullDemo()
jobsDemo.generateReport()
```

---

## Manual Testing Checklist

### ✅ Page Load
- [ ] Page loads without console errors
- [ ] All CSS styles applied correctly
- [ ] Statistics cards display with correct numbers
- [ ] Table shows jobs data
- [ ] Pagination visible (if applicable)
- [ ] No layout shifts

### ✅ Search Functionality
- [ ] Type in search box → results filter immediately
- [ ] Clear button (X) appears when typing
- [ ] Click clear button → search resets
- [ ] Search terms are highlighted in yellow
- [ ] Results counter updates correctly
- [ ] Empty search shows all results
- [ ] Search works for: job name, company, ID

### ✅ Filter Buttons
- [ ] "Tất cả" shows all jobs
- [ ] "Đã xuất bản" shows only published
- [ ] "Chưa xuất bản" shows only unpublished
- [ ] Active filter has blue background
- [ ] Results counter updates
- [ ] Icons display correctly

### ✅ Sortable Columns
- [ ] Click "Job" header → sorts by name
- [ ] Click again → reverses sort direction
- [ ] Sort icon changes (up/down arrow)
- [ ] Click "Công ty" → sorts by company
- [ ] Click "Trạng thái" → sorts by status
- [ ] Click "Ngày tạo" → sorts by date
- [ ] Hover shows pointer cursor

### ✅ Refresh Button
- [ ] Click refresh button
- [ ] Icon spins during refresh
- [ ] Toast notification appears
- [ ] Data refreshes (if connected to API)

### ✅ Select & Bulk Actions
- [ ] Click "Select All" → all visible jobs selected
- [ ] Bulk actions bar appears
- [ ] Selected count shows correct number
- [ ] Uncheck "Select All" → all deselected
- [ ] Select individual jobs → bulk bar appears
- [ ] Click "Xuất bản" → confirmation modal shows
- [ ] Confirm → jobs update without reload
- [ ] Status badges change color
- [ ] Statistics update
- [ ] Click "Ẩn" → same flow
- [ ] Click "Xóa" → jobs removed with animation

### ✅ Individual Actions
- [ ] Click eye icon → navigates to view page
- [ ] Click edit icon → navigates to edit page
- [ ] Click play/pause → publishes/unpublishes job
- [ ] Click trash icon → confirmation modal shows
- [ ] Confirm delete → row fades out and slides left
- [ ] Row removed from table
- [ ] Statistics decrease by 1

### ✅ Confirmation Modal
- [ ] Modal appears centered
- [ ] Backdrop is blurred
- [ ] Icon matches action type (danger/warning/info)
- [ ] Title and message display correctly
- [ ] Click "Hủy" → modal closes, no action
- [ ] Click "X" → modal closes
- [ ] Click backdrop → modal closes
- [ ] Press ESC → modal closes
- [ ] Click confirm → action executes
- [ ] Modal has smooth scale animation

### ✅ Loading States
- [ ] Loading overlay appears during operations
- [ ] Spinner animates smoothly
- [ ] Loading message displays
- [ ] Backdrop is semi-transparent
- [ ] Loading hides after operation completes

### ✅ Toast Notifications
- [ ] Toast appears top-right
- [ ] Success toast is green
- [ ] Error toast is red
- [ ] Toast auto-dismisses after 3 seconds
- [ ] Toast slides in from right
- [ ] Toast slides out when dismissed
- [ ] Multiple toasts stack correctly

### ✅ Statistics Cards
- [ ] All 4 cards display
- [ ] Numbers are correct
- [ ] Icons display
- [ ] Gradient backgrounds show
- [ ] Hover effect (lift up)
- [ ] After delete → numbers decrease with animation
- [ ] After bulk action → numbers update
- [ ] Animation is smooth (counting effect)

### ✅ Animations
- [ ] Table rows fade in on page load
- [ ] Staggered animation (rows appear one by one)
- [ ] Delete animation (fade + slide)
- [ ] Modal scale in/out
- [ ] Button hover effects
- [ ] Filter button transitions
- [ ] Smooth color changes

### ✅ Keyboard Shortcuts
- [ ] Press Ctrl/Cmd + K → search box focused
- [ ] Press Ctrl/Cmd + A → all jobs selected
- [ ] Press ESC → selection cleared
- [ ] Press ESC in modal → modal closes
- [ ] Tab navigation works
- [ ] Enter in search works

### ✅ Responsive Design

#### Desktop (> 1024px)
- [ ] Full layout displays
- [ ] Sidebar visible
- [ ] All features accessible
- [ ] Proper spacing

#### Tablet (768px - 1024px)
- [ ] Layout adjusts
- [ ] Sidebar collapsible
- [ ] Touch-friendly buttons
- [ ] No horizontal scroll

#### Mobile (< 768px)
- [ ] Mobile menu toggle appears
- [ ] Stats stack vertically
- [ ] Search full-width
- [ ] Filter buttons show icons only
- [ ] Table scrolls horizontally
- [ ] Modal fits screen
- [ ] Bulk actions stack
- [ ] Touch targets large enough

### ✅ Accessibility

#### Screen Reader
- [ ] Announcements work (use screen reader)
- [ ] Search results announced
- [ ] Filter changes announced
- [ ] Action results announced
- [ ] All buttons have labels

#### Keyboard Navigation
- [ ] Tab through all interactive elements
- [ ] Focus visible on all elements
- [ ] No keyboard traps
- [ ] Logical tab order
- [ ] Modal traps focus

#### Visual
- [ ] Color contrast sufficient
- [ ] Text readable
- [ ] Icons have meaning
- [ ] No color-only information
- [ ] Focus indicators visible

### ✅ Performance

#### Speed
- [ ] Search responds within 300ms
- [ ] Animations are smooth (60fps)
- [ ] No lag when scrolling
- [ ] Modal opens instantly
- [ ] Page loads quickly

#### Memory
- [ ] No memory leaks (check DevTools)
- [ ] Event listeners cleaned up
- [ ] No console errors
- [ ] No console warnings

### ✅ Browser Compatibility

#### Chrome/Edge
- [ ] All features work
- [ ] Animations smooth
- [ ] No visual bugs

#### Firefox
- [ ] All features work
- [ ] Animations smooth
- [ ] No visual bugs

#### Safari
- [ ] All features work
- [ ] Animations smooth
- [ ] No visual bugs

#### Mobile Browsers
- [ ] iOS Safari works
- [ ] Android Chrome works
- [ ] Touch interactions work

---

## Automated Tests (Console)

```javascript
// Run full demo
jobsDemo.runFullDemo()

// Generate feature report
jobsDemo.generateReport()

// Test individual features
jobsDemo.testKeyboardShortcuts()
jobsDemo.testAccessibility()
jobsDemo.testResponsive()
jobsDemo.testPerformance()
```

---

## Bug Report Template

```markdown
### Bug Description
[Describe the issue]

### Steps to Reproduce
1. 
2. 
3. 

### Expected Behavior
[What should happen]

### Actual Behavior
[What actually happens]

### Environment
- Browser: 
- OS: 
- Screen size: 
- Console errors: 

### Screenshots
[If applicable]
```

---

## Performance Benchmarks

### Target Metrics
- Page load: < 2s
- Search response: < 300ms
- Animation FPS: 60fps
- Memory usage: < 50MB
- Lighthouse score: > 90

### How to Test
1. Open DevTools
2. Go to Performance tab
3. Record interaction
4. Check metrics

---

## Accessibility Audit

### Tools
- Chrome DevTools Lighthouse
- WAVE browser extension
- Screen reader (NVDA/JAWS/VoiceOver)

### Target Scores
- Lighthouse Accessibility: > 95
- WAVE: 0 errors
- Keyboard navigation: 100% coverage

---

## Sign-off

### Tested By
- Name: _______________
- Date: _______________
- Browser: _______________
- Result: ☐ Pass ☐ Fail

### Issues Found
1. 
2. 
3. 

### Notes
_______________________________________________
_______________________________________________
_______________________________________________

---

**Status:** ☐ Ready for Production ☐ Needs Fixes
