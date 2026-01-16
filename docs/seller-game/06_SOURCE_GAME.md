# SOURCE GAME - TỔNG HỢP

## 1. Mô tả

**Source Game** là trang hiển thị và bán source code game trên nền tảng Làm Game.

## 2. URLs

| Trang | URL | Controller |
|-------|-----|------------|
| Danh sách | `/source-game` | LamGamePageController@sourceGame |
| Chi tiết | `/source-game/{slug}` | LamGamePageController@sourceGameDetail |

## 3. Routes

```php
// routes/web.php
Route::get('source-game', [LamGamePageController::class, 'sourceGame'])
    ->name('lamgame.source-game');
Route::get('source-game/{slug}', [LamGamePageController::class, 'sourceGameDetail'])
    ->name('lamgame.source-game.detail');
```

## 4. Views

| View | Đường dẫn |
|------|-----------|
| Danh sách | `resources/views/lamgame/pages/source-game.blade.php` |
| Chi tiết | `resources/views/lamgame/pages/source-game-detail.blade.php` |
| Product view | `packages/Shop/src/Resources/views/products/source-game-view.blade.php` |

## 5. Tính năng

### 5.1 Trang danh sách
- Hiển thị danh sách source code game
- Lọc theo engine (Unity, Unreal, Godot...)
- Lọc theo ngôn ngữ lập trình
- Tìm kiếm theo tên
- Phân trang

### 5.2 Trang chi tiết
- Thông tin source game
- Hình ảnh/video demo
- Thông tin kỹ thuật (engine, ngôn ngữ, version)
- Giá và nút mua
- Đánh giá từ người dùng

## 6. Liên kết với Seller

Source Game và Seller Game có mối quan hệ chặt chẽ:

```
Seller đăng ký → Upload source game → Hiển thị trên /source-game
                                              ↓
                                    Khách mua → Seller nhận doanh thu
```

## 7. Tài liệu liên quan

- [Tài liệu Source Game](../source_game/README.md)
- [Tài liệu Seller Game](./README.md)
