# Top 10 Source Game Revenue Catalog

Danh sách này được chọn từ sản phẩm trả phí có record downloadable link. Không ghi đè database; nguồn merchandising nằm tại `config/source-game-revenue.php`.

> Audit production ngày 05/09/2026: 9 SKU đã có demo tương tác, screenshot gameplay và ZIP private kèm README/license/checksum. `fps-multiplayer` vẫn khóa bán vì chưa có project Unreal gốc; không thay bằng template HTML5 giả.

| SKU | Sản phẩm | Giá hiện tại | Trạng thái asset đã xác minh |
|---|---|---:|---|
| tower-defense | Phòng Thủ | 249.750đ | Demo HTML5 mới chạy; 3 screenshot; ZIP private có README/license/checksum |
| endless-runner | Chạy Bất Tận | 249.750đ | 4 ảnh; demo chạy; ZIP private có README/license/checksum |
| top-down-shooter | Bắn Tàu | 249.750đ | 4 ảnh; demo đã sửa và chạy; ZIP private có README/license/checksum |
| bubble-shooter | Bắn Bóng Bóng | 249.750đ | Demo HTML5 mới chạy; 3 screenshot; ZIP private có README/license/checksum |
| chess-ai | Cờ Vua Online | 249.750đ | Demo mới có legal move/check/checkmate/AI; 3 screenshot; ZIP private đầy đủ |
| roguelike-dungeon | Thoát Mê Cung | 124.750đ | 4 ảnh; demo chạy; ZIP private có README/license/checksum |
| match3-candy | Kẹo Ngọt Xếp 3 | 124.750đ | Demo mới có swap/cascade/moves; 3 screenshot; ZIP private đầy đủ |
| card-game-engine | Xếp Bài Một Mình | 124.750đ | 4 ảnh; demo chạy; ZIP private có README/license/checksum |
| quiz-trivia | Đố Vui Kiến Thức | 124.750đ | 4 ảnh; demo chạy; ZIP private có README/license/checksum |
| fps-multiplayer | FPS Multiplayer Template | 749.750đ | Thiếu project Unreal, ảnh, demo và ZIP; tiếp tục `Chưa mở bán` |

## Tiến độ xác minh

- 9/10 có demo chạy và tương tác bằng headless browser.
- 9/10 có ba gameplay screenshots thật, khác trạng thái.
- 10/10 có record downloadable link; 9/10 có ZIP trên private disk đúng đường dẫn DB.
- 9/10 ZIP có README, LICENSE và CHECKSUMS.sha256.
- Không dùng splash screen, canvas trống hoặc placeholder làm screenshot sản phẩm.
- Blocker còn lại: cần bàn giao project Unreal Networking gốc cho `fps-multiplayer`; không thể tự suy diễn từ tên SKU.

## Definition of Done cho từng sản phẩm

- [ ] Có ít nhất 3 screenshot gameplay thật.
- [ ] Có demo chạy được hoặc video gameplay thật.
- [x] Có downloadable link.
- [ ] Có README/hướng dẫn cài đặt trong gói tải.
- [ ] Mô tả rõ engine, version và nền tảng hỗ trợ.
- [ ] Điều khoản license và hoàn tiền được liên kết.
- [ ] Kiểm thử add-to-cart → checkout → download.

Đóng gói các SKU đã xác minh vào private disk (ghi file, không sửa DB):

```bash
php artisan source-games:package-revenue-catalog --dry-run
php artisan source-games:package-revenue-catalog --force
```

Chạy audit không ghi dữ liệu:

```bash
php artisan source-games:audit-revenue-catalog
```

Dùng `--strict` trong CI để trả exit code khác 0 khi catalog chưa đạt yêu cầu.
