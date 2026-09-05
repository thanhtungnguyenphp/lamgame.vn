# AdSense Integration — Active, Consent-Gated

Publisher hiện tại do chủ tài khoản cung cấp:

- Client: `ca-pub-5812352607411986`
- Seller: `pub-5812352607411986`
- Trạng thái production: `ADSENSE_ENABLED=true`

## Kiến trúc

Không hardcode snippet trong layout. Hai layout gọi `partials.adsense`; partial này:

1. Render verification meta khi publisher hợp lệ.
2. Chỉ chuẩn bị loader trên route editorial được allowlist.
3. Chỉ thêm `adsbygoogle.js` sau khi `LamGameConsent.allows('advertising')` trả `true`.
4. Reload trang khi người dùng thu hồi advertising consent để loại bỏ runtime đã tải.

Cấu hình nằm tại `config/adsense.php` và `.env`:

```env
ADSENSE_ENABLED=true
ADSENSE_CLIENT=ca-pub-5812352607411986
ADSENSE_SELLER_ID=pub-5812352607411986
```

## Phạm vi hiện tại

Được phép:

- `lamgame.blog` (`/blog`)
- `blog.show` (`/blog/{slug}`)

Bị loại trừ: checkout, account/customer, admin/API, game, Source Game, AI Tools, Hire và Portfolio.

## Consent

- Consent Mode v2 mặc định `denied`.
- Script quảng cáo không được request trước advertising consent.
- Verification meta không tạo cookie và được render để AdSense xác minh site.
- Privacy Policy và footer cho phép người dùng thay đổi consent.

## Validation bắt buộc sau mỗi thay đổi

1. First visit: không có request `pagead2.googlesyndication.com`.
2. Reject: lựa chọn được lưu, script vẫn không tải.
3. Advertising opt-in trên blog: `#lg-adsense` được thêm với đúng client.
4. Advertising opt-in trên route bị loại trừ: không có `#lg-adsense`.
5. `ads.txt` và `app-ads.txt` trả HTTP 200, đúng seller record.
