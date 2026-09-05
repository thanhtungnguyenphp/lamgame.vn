# ads.txt — Active AdSense Seller

Seller record do chủ tài khoản cung cấp:

```text
google.com, pub-5812352607411986, DIRECT, f08c47fec0942fa0
```

Record được đặt tại:

- `public/ads.txt`
- `public/app-ads.txt`

Kiểm tra:

```bash
curl -i https://lamgame.vn/ads.txt
curl -i https://lamgame.vn/app-ads.txt
```

Yêu cầu:

- HTTP 200.
- `Content-Type: text/plain`.
- Không có seller ID cũ/khác ngoài danh sách được chủ tài khoản phê duyệt.
- Nếu thay publisher, phải cập nhật đồng thời `.env`, hai file seller và tài liệu này.
