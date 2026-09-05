# Top 10 Source Game Revenue Catalog

Danh sách này được chọn từ sản phẩm trả phí có record downloadable link. Không ghi đè database; nguồn merchandising nằm tại `config/source-game-revenue.php`.

> Audit filesystem ngày 02/09/2026: 5 SKU đã được đóng gói vào private disk đúng với Bagisto download controller; 5 SKU còn lại vẫn chỉ có record DB và chưa có file.

| SKU | Sản phẩm | Giá hiện tại | Trạng thái asset đã xác minh |
|---|---|---:|---|
| tower-defense | Phòng Thủ | 249.750đ | 1 ảnh DB; demo chỉ dừng ở splash; thiếu file ZIP/docs |
| endless-runner | Chạy Bất Tận | 249.750đ | 4 ảnh; demo chạy; ZIP private có README/license/checksum |
| top-down-shooter | Bắn Tàu | 249.750đ | 4 ảnh; demo đã sửa và chạy; ZIP private có README/license/checksum |
| bubble-shooter | Bắn Bóng Bóng | 249.750đ | 1 ảnh DB; desktop trống/mobile không hỗ trợ; thiếu file ZIP/docs |
| chess-ai | Cờ Vua Online | 249.750đ | 1 ảnh DB; đã sửa đường dẫn bundle nhưng demo vẫn dừng splash; thiếu file ZIP/docs |
| roguelike-dungeon | Thoát Mê Cung | 124.750đ | 4 ảnh; demo chạy; ZIP private có README/license/checksum |
| match3-candy | Kẹo Ngọt Xếp 3 | 124.750đ | 1 ảnh DB; demo chỉ dừng ở splash; thiếu file ZIP/docs |
| card-game-engine | Xếp Bài Một Mình | 124.750đ | 4 ảnh; demo chạy; ZIP private có README/license/checksum |
| quiz-trivia | Đố Vui Kiến Thức | 124.750đ | 4 ảnh; demo chạy; ZIP private có README/license/checksum |
| fps-multiplayer | FPS Multiplayer Template | 749.750đ | Không có ảnh/demo/file ZIP/docs |

## Tiến độ xác minh

- 5/10 có demo đã chạy và tương tác bằng headless browser.
- 5/10 có ba gameplay screenshots thật, khác trạng thái.
- 10/10 có record downloadable link; 5/10 đã có ZIP trên private disk đúng đường dẫn DB.
- 5/10 ZIP có README, LICENSE và CHECKSUMS.sha256; 5/10 còn thiếu.
- Không dùng splash screen, canvas trống hoặc placeholder làm screenshot sản phẩm.

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
