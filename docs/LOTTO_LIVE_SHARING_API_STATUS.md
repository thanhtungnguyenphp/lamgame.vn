# Lotto Live Sharing API — trạng thái và hợp đồng tích hợp

> Cập nhật: 20/10/2026. Tài liệu này mô tả namespace mới cho mobile app. API vé số legacy (`/api/v1/user/tickets`) vẫn hoạt động độc lập và không dùng để chia sẻ vé.

## Trạng thái triển khai

| Hạng mục | Trạng thái | Ghi chú |
|---|---|---|
| Route API | Sẵn sàng | 13 route đã được Laravel đăng ký dưới namespace mới. |
| Cơ sở dữ liệu | Đã chạy local | Migration `2026_10_20_090000_create_ticket_sharing_tables` chạy batch 38 trên MySQL local. |
| Firebase auth | Sẵn sàng, cần cấu hình | Bắt buộc `FIREBASE_IDENTITY_TOOLKIT_API_KEY` (hoặc fallback `FIREBASE_WEB_API_KEY`) để kiểm tra tài khoản disabled/revoked; thiếu khóa thì API fail-closed. |
| Ảnh vé | Sẵn sàng | Lưu ở private disk, JPEG tối đa 8 MB/24 MP, URL có chữ ký 5 phút. |
| Notification | Sẵn sàng | Outbox + queue + FCM device riêng; token FCM invalid bị gỡ. |
| Kết quả vé | Sẵn sàng | `sharing:maintain` chạy mỗi 5 phút, chỉ kết luận thua khi nguồn xổ số hoàn chỉnh. |
| Production | Chưa xác nhận | Cần đặt biến môi trường, chạy migration/queue/scheduler trên môi trường deploy trước khi Flutter E2E. |

## URL, xác thực và quy ước chung

- Base URL: `https://lamgame.vn/api/v1/sharing/v1`.
- Mọi endpoint, **trừ URL ảnh ký tạm**, cần header `Authorization: Bearer <Firebase ID token>`.
- Token phải hợp lệ, chưa hết hạn, email đã xác minh và Firebase account không bị disabled/revoked.
- Thành công: `{"status":"ok","data":{...}}`; API không trả `204`.
- Lỗi: `{"status":"error","error":{"code":"..."}}`; không trả stack trace/HTML cho lỗi nghiệp vụ.
- `401 unauthorized` nghĩa là thiếu/không hợp lệ token; `403 email_unverified` nghĩa là email Firebase chưa xác minh. `503 auth_verification_unavailable` nghĩa là backend chưa kiểm tra được Firebase account state (cấu hình, cache, API-key restriction hoặc mạng); Flutter có thể retry, không đăng xuất người dùng.
- Thời gian là ISO-8601 UTC (`Z`); `draw_date` là `YYYY-MM-DD` theo Asia/Ho_Chi_Minh.
- ID hợp lệ: `[A-Za-z0-9_-]`, dài 1–128. Không dùng Firebase UID/email làm public ID.
- `Idempotency-Key` là 32 ký tự hex thường (`^[a-f0-9]{32}$`), được lưu ít nhất 7 ngày. Dùng lại key với payload khác trả `409`.

### Rate limit

Namespace sharing bị giới hạn 30 request/phút theo **UID + IP + `X-Installation-Id`** (nếu Flutter gửi header). Laravel cũng giới hạn theo IP. Ba API tạo/resolve/report có giới hạn bổ sung 10 request/phút theo IP:

| API | Giới hạn hiệu lực |
|---|---|
| `POST /recipients/resolve`, `POST /shares`, `POST /reports` | 10 request/phút/IP; 30 request/phút/UID-installation-IP |
| API khác | 30 request/phút/UID-installation-IP |

Khi vượt giới hạn: HTTP `429`, code `rate_limited`, kèm `Retry-After`.

## Vòng đời share

```text
awaiting_registration ── đăng ký đúng email ──> pending ── accept ──> accepted
       │                         │                   └─ decline ──> declined
       └─ cancel/expiry ─────────┴─ cancel/expiry ─────────────────> cancelled/expired
```

- Mỗi `client_ticket_id` chỉ được snapshot một lần trong phạm vi UID owner. Không thể upload lại để thay số/ảnh vé.
- Một share `awaiting_registration`, `pending` hoặc `accepted` giữ khóa active ở database; tạo đồng thời chỉ một request thành công.
- `accepted` không thể sửa, hủy hay chuyển cho người khác.
- Với `pending`, hết hạn khi đến giờ quay; worker tự chuyển `expired`. Share `accepted` vẫn lưu lịch sử để nhận kết quả.
- `result_status`: `pending`, `checking`, `won`, `lost`, `correction_pending`; chỉ `won/lost` có `result_final=true`.

## API chi tiết

### 1. Hồ sơ hiện tại — `GET /me`

Khởi tạo hoặc trả profile public của Firebase user hiện tại. `public_id` được tạo ngẫu nhiên một lần và không đổi.

```json
{"status":"ok","data":{"public_id":"LL_A7FKP91","display_name":"Minh"}}
```

QR phía client phải dùng `lottolive:profile:v1:<public_id>`; QR không cấp quyền truy cập dữ liệu.

### 2. Tìm người nhận — `POST /recipients/resolve`

Yêu cầu `Idempotency-Key`.

```json
{"kind":"public_id","value":"LL_A7FKP91"}
```

`kind` là `public_id` hoặc `email`. Với người đã đăng ký và cho phép tìm qua email, trả:

```json
{"status":"ok","data":{"recipient_token":"opaque-token","kind":"registered","display_label":"Minh","public_id":"LL_A7FKP91"}}
```

Email chưa đăng ký hoặc không discoverable trả `kind: "email_invitation"`, địa chỉ được che, ví dụ `m***@example.com`; không gửi email ở bước này. `recipient_token` gắn với UID sender, đích nhận và hạn 10 phút. Self-share, block, public ID không tồn tại hoặc token hết hạn đều bị từ chối.

### 3. Tạo share — `POST /shares`

Yêu cầu `Idempotency-Key`, `multipart/form-data`:

| Field | Bắt buộc | Nội dung |
|---|---:|---|
| `payload` | Có | JSON ở dưới |
| `image` | Có | JPEG đã bỏ metadata ở client; server vẫn decode/re-encode |

```json
{
  "client_ticket_id":"local-ticket-stable-id",
  "ticket":{"number":"012345","province":"TP.HCM","province_code":"HCM","region":"mien-nam","draw_date":"2026-10-20"},
  "recipient_token":"opaque-token",
  "share_percent":15,
  "message":"Cùng theo dõi kết quả nhé!",
  "terms_version":"2026-10-19.v1"
}
```

Server canonicalize đài từ `province_code`/`region`, kiểm tra lịch quay, giữ zero đầu, chỉ nhận một số 5–6 chữ số, phần trăm nguyên 10–50 và lời nhắn tối đa 300 ký tự. Kết quả `201` trả `TicketShare` đầy đủ. Các lỗi thường gặp: `invalid_ticket`, `image_too_large`, `draw_closed`, `ticket_already_shared`, `ticket_locked`, `recipient_token_expired`, `terms_changed`.

### 4. Danh sách hộp vé — `GET /shares`

Query:

| Tên | Bắt buộc | Giá trị |
|---|---:|---|
| `box` | Có | `sent` hoặc `received` |
| `limit` | Không | 1–50, mặc định 20 |
| `cursor` | Không | Cursor opaque từ response trước |

```json
{"status":"ok","data":{"items":[/* TicketShare */],"next_cursor":"opaque-or-null"}}
```

Cursor dùng sắp xếp ổn định `created_at DESC, id DESC`. Cursor trang kế tiếp được tạo từ **bản ghi cuối cùng đã trả về** (không phải bản ghi look-ahead), với timestamp theo định dạng database để tránh lệch múi giờ MySQL. Điều này bảo đảm không bỏ sót bản ghi ở ranh giới trang. Chỉ sender xem `sent`, chỉ recipient xem `received`.

### 5. Chi tiết share — `GET /shares/{shareId}`

Chỉ participant hiện tại mới xem được. Response là `TicketShare` đầy đủ. `ticket.image_url` là URL HTTPS signed tối đa 5 phút; gọi lại detail để lấy URL mới. Sau decline/cancel/expiry hoặc account deletion, `image_url` có thể là `null`.

### 6. Chấp nhận — `POST /shares/{shareId}/accept`

Chỉ recipient của share `pending` có thể gọi. Yêu cầu `Idempotency-Key`.

```json
{"expected_version":1,"terms_version":"2026-10-19.v1"}
```

Row được lock và so `expected_version`; trả `409 stale_version`, `invalid_transition`, `blocked` hoặc `terms_changed` khi không hợp lệ. Sau cutoff worker chuyển share sang `expired`, nên accept trả `stale_version` hoặc `invalid_transition`. Thành công trả `TicketShare` với `status: "accepted"`.

### 7. Từ chối — `POST /shares/{shareId}/decline`

Chỉ recipient của share `pending`. Yêu cầu `Idempotency-Key`.

```json
{"expected_version":1}
```

Thành công chuyển `declined`, giải phóng active lock; gửi lại cần tạo share mới, không hồi sinh share cũ.

### 8. Hủy lời mời — `POST /shares/{shareId}/cancel`

Chỉ sender của share `awaiting_registration` hoặc `pending`. Yêu cầu `Idempotency-Key`.

```json
{"expected_version":1}
```

Không thể hủy share `accepted`.

### 9. Đăng ký thiết bị push — `PUT /devices/{installationId}`

`installationId` là ID ngẫu nhiên do app cài đặt sinh, không phải credential. JSON:

```json
{"fcm_token":"FCM_TOKEN","platform":"android"}
```

`platform` là `android` hoặc `ios`. Một FCM token chỉ gắn với một UID; khi user đổi account trên cùng thiết bị, backend rebind token để account cũ không còn nhận push.

### 10. Hủy thiết bị push — `DELETE /devices/{installationId}`

Chỉ xóa liên kết thuộc UID hiện tại. Luôn trả:

```json
{"status":"ok","data":{}}
```

### 11. Block/unblock — `PUT /blocks/{publicId}`

```json
{"blocked":true}
```

Block áp dụng hai chiều cho resolve/create/accept/push; các lời mời pending liên quan bị hủy, history accepted được giữ. `blocked:false` chỉ gỡ block, không tự hồi sinh share cũ. `TicketShare.sender_blocked` cho recipient biết sender đang bị block.

### 12. Báo cáo — `POST /reports`

Yêu cầu `Idempotency-Key`; chỉ participant được báo cáo.

```json
{"share_id":"sh_123","reason":"spam"}
```

`reason`: `spam`, `fraud`, `harassment`, `inappropriate`. Server lưu snapshot moderation nội bộ; người khác không thể đọc báo cáo hoặc nội dung moderation.

### 13. Ảnh private — `GET /images/{shareId}`

Đây là URL được backend ký khi trả `TicketShare.ticket.image_url`, nên **không cần Bearer header**. Không gọi route này bằng `shareId` trần. URL hết hạn sau 5 phút, trả `Cache-Control: private, no-store`.

## Cấu trúc `TicketShare`

Mọi item list/detail dùng cùng cấu trúc:

```json
{
  "share_id":"sh_123","version":1,"viewer_role":"sender",
  "sender":{"public_id":"LL_OWNER01","display_name":"An"},
  "recipient_label":"Minh",
  "ticket":{"number":"012345","province":"TP.HCM","province_code":"HCM","region":"mien-nam","draw_date":"2026-10-20","image_url":"https://..."},
  "share_percent":15,"message":"Cùng theo dõi kết quả nhé!",
  "status":"pending","result_status":"pending","result_final":false,"matched_prizes":[],
  "created_at":"2026-10-19T03:00:00Z","expires_at":"2026-10-20T09:00:00Z",
  "terms_version":"2026-10-19.v1","sender_blocked":false
}
```

## Tác vụ nền và vận hành

| Thành phần | Cách chạy | Chức năng |
|---|---|---|
| `sharing:maintain` | Scheduler mỗi 5 phút | Expire lời mời, dò snapshot khi dữ liệu quay hoàn chỉnh, phát outbox. |
| `ProcessSharingOutbox` | Queue worker | Gửi push/email generic, retry/backoff, đánh dấu dead-letter `failed`. |
| `sharing:delete-account <firebase_uid>` | Gọi từ webhook xóa Firebase đã xác thực | Thu hồi device/access/media private; giữ audit accepted theo retention policy. |

Push chỉ chứa `type`, `share_id`, `event_id`, `result_version`; không chứa số vé, phần trăm, lời nhắn, email hoặc URL ảnh. Inbox/detail luôn là source of truth.

## Cấu hình deploy bắt buộc

```dotenv
FIREBASE_IDENTITY_TOOLKIT_API_KEY=...
# Hoặc giữ FIREBASE_WEB_API_KEY=... để dùng fallback tương thích cũ.
FIREBASE_WEB_API_KEY=...
SHARING_IMAGE_DISK=private
SHARING_REQUIRE_FIREBASE_ACCOUNT_LOOKUP=true
SHARING_TERMS_VERSION=2026-10-19.v1
```

Sau khi đặt biến môi trường hoặc đổi API key, chạy `php artisan config:clear` rồi `php artisan sharing:check-firebase`. Lệnh này không in secret và chỉ thành công khi project ID, chứng thư Firebase, API key và outbound Identity Toolkit đều hợp lệ. Sau deploy cần chạy migration, queue worker và scheduler. Chi tiết schema máy đọc được nằm tại [openapi-sharing-v1.yaml](openapi-sharing-v1.yaml).
