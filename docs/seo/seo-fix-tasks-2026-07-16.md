# SEO Fix Tasks — lamgame.vn (16/07/2026)

> Cập nhật lần 2: 10:26 AM — sau khi test lại backend đã deploy

## ĐÃ HOÀN THÀNH ✅

- [x] Task 1: Block port 2083 → 301 redirect về homepage
- [x] Task 3: Fix Server Error 5xx → Validation started trong GSC
- [x] Task 4: X-Robots-Tag `noindex, nofollow` cho /api/*
- [x] Task 6: Redirect ghost URLs — tất cả đã 301 đúng:
  - `/game-reviews` → `/blog`
  - `/cs2`, `/valorant`, `/valorant-competitive` → `/blog?tag=fps`
  - `/lol-news` → `/blog?tag=lol`
  - `/vietnam-football` → `/the-thao`
  - `/tin-tuc-game` → `/blog`
  - `/category/game-reviews` → `/blog`
  - `/khoa-hoc/unreal` → `/blog?tag=unreal-engine`
  - `/khoa-hoc/csharp` → `/blog?tag=csharp`
  - `/khoa-hoc/game-design` → `/blog?tag=game-design`
  - `/khoa-hoc/mobile` → `/blog?tag=mobile-game`
  - `/khoa-hoc/3d-game` → `/blog?tag=3d-game`
  - `/khoa-hoc/2d-game` → `/blog?tag=2d-game`
  - `/streamers` → `/cong-dong`
  - `/tournaments` → `/the-thao/lich-thi-dau`
  - `/sports-gaming` → `/the-thao`
  - `/tag/ti-2026`, `/tag/esports` → `/blog`
  - `/index` → `/` (homepage)
  - `/en/` → `/` (homepage)
- [x] GSC: Validate Fix "Server error 5xx" + "Soft 404"
- [x] GSC: Resubmit tất cả sitemaps
- [x] GSC: URL Removal prefix `https://lamgame.vn:2083/` → Processing

---

## CẦN FIX 🔴

### Task 2: HTTP status code 404 — ĐANG TRẢ 200

**Vấn đề:** Page render đúng nội dung "404 Page Not Found" (title, content đều hiển thị 404) nhưng **HTTP response status code vẫn là 200**. Google detect đây là Soft 404.

**Tất cả URL sau đều có title `<title>404 Page Not Found</title>` nhưng status 200:**

```
/tutorials
/guides
/the-sims-5
/vote
/reviews
/source-code
/mini-game-m7
/blog/esports
/blog/dota-2
/blog/free-fire-tips
/blog/pubg-mobile-guide
/blog/cs2-map-callouts
/blog/review-game
/game-reviews/counter-strike-2
/game-reviews/valorant
```

**Fix cần làm:**

Tìm nơi render 404 view mà không set HTTP status 404. Có thể ở:

1. **`routes/web.php` — fallback route:**
   ```php
   // SAI:
   Route::fallback(function () {
       return view('errors.404');  // ← trả 200!
   });
   
   // ĐÚNG:
   Route::fallback(function () {
       return response()->view('errors.404', [], 404);  // ← trả 404!
   });
   ```

2. **`app/Exceptions/Handler.php`** — render method có thể thiếu status code

3. **Blog/GameReview controller** — khi slug không tồn tại:
   ```php
   // SAI:
   if (!$post) {
       return view('errors.404');
   }
   
   // ĐÚNG:
   if (!$post) {
       abort(404);
   }
   ```

**Verify sau khi fix:**
```bash
curl -sI https://lamgame.vn/tutorials | head -1
# Phải trả: HTTP/2 404
```

---

### Task 5: Noindex blog tag/category pages rỗng

**Vấn đề:** Tag/category pages có 0 bài hoặc ít bài. Chỉ `/blog?tag=racing` có noindex, các tag khác thiếu.

**URLs thiếu noindex:**

```
/blog?tag=fps         → NO robots meta
/blog?tag=guide       → NO robots meta
/blog?category=mobile-game  → NO robots meta
/blog?category=fps-games    → NO robots meta
```

**Fix cần làm:**

Trong Blog controller hoặc view `blog/index.blade.php`, thêm logic:

```php
// Controller: pass biến cho view
$shouldNoindex = $posts->total() < 5;

// View (trong <head>):
@if($shouldNoindex ?? false)
    <meta name="robots" content="noindex, follow">
@endif
```

Hoặc đơn giản hơn — noindex TẤT CẢ listing pages có query param `tag` hoặc `category`:

```php
// Middleware hoặc trong view:
@if(request()->has('tag') || request()->has('category'))
    <meta name="robots" content="noindex, follow">
@endif
```

---

### Task 7: Forum /posts/{id} → redirect sang /posts/{slug}

**Vấn đề:** `/forum/posts/1`, `/forum/posts/5`, `/forum/posts/65` trả HTTP 200. Google crawl cả dạng ID lẫn slug → duplicate content.

**Fix cần làm:**

Trong Forum controller (show method):

```php
public function show($identifier)
{
    if (is_numeric($identifier)) {
        $post = ForumPost::find($identifier);
        if ($post) {
            return redirect("/forum/posts/{$post->slug}", 301);
        }
        abort(404);
    }
    
    // Tiếp tục logic bình thường cho slug...
    $post = ForumPost::where('slug', $identifier)->firstOrFail();
}
```

**Verify sau khi fix:**
```bash
curl -sI https://lamgame.vn/forum/posts/1 | grep -i "HTTP\|location"
# Phải trả: HTTP/2 301 + location: /forum/posts/{slug}
```

---

### Task nhỏ: /index.html redirect sai protocol

**Vấn đề:** `/index.html` → `http://lamgame.vn/index` (dùng http, không phải https, và redirect sang /index thay vì /)

**Fix cần làm:**

Trong `routes/redirects.php` hoặc `.htaccess`:

```php
// Sửa thành:
Route::redirect('/index.html', '/', 301);
```

**Verify sau khi fix:**
```bash
curl -sI https://lamgame.vn/index.html | grep -i "location"
# Phải trả: location: https://lamgame.vn/
```

---

## ƯU TIÊN FIX

| # | Task | Impact | Effort | Ghi chú |
|---|------|--------|--------|---------|
| 1 | Task 2 (Status 404) | 🔴 ~50 URLs | **1 dòng code** | Sửa `Route::fallback` thêm status 404 |
| 2 | Task nhỏ (/index.html) | 🟡 1 URL | **1 dòng code** | Sửa redirect target |
| 3 | Task 5 (Noindex tags) | 🟡 ~40 URLs | Low | Thêm meta tag trong blade |
| 4 | Task 7 (Forum redirect) | 🟡 ~10 URLs | Medium | Thêm logic trong controller |

**Quan trọng nhất: Task 2 — chỉ cần thêm `, 404` vào response của fallback route là fix được ~50 URLs cùng lúc.**
