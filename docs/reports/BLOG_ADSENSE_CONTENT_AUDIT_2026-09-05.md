# Blog AdSense Content Audit — 30 bài ưu tiên

Ngày audit: 2026-09-05
Phạm vi: 194 bài published; chọn 30 bài rủi ro cao nhất để xử lý trước.
Ràng buộc: không thay đổi production DB/schema, URL, publisher, `ads.txt` hoặc phạm vi AdSense.

## Phương pháp chọn

Trường `blogs.views` đang bằng `0` cho toàn bộ 194 bài và repo chưa có dữ liệu Search Console theo URL. Vì vậy không thể gọi trung thực đây là “top traffic”. Audit đã quét toàn bộ 194 record và xếp ưu tiên theo:

- Nội dung dưới 300/600 từ.
- Không có H2 hoặc tutorial không có code/demo.
- Tên bài, meta title và slug không khớp.
- Meta description quá dài/ngắn hoặc chứa URL thô.
- Nội dung trùng/near-duplicate.
- Thiếu nguồn, trạng thái editorial review và internal link.
- Chủ đề ít liên quan tới game development hoặc đã lỗi thời.

Các ngưỡng từ chỉ là tín hiệu triage, không phải tiêu chuẩn chất lượng tuyệt đối của Google.

## Kết quả toàn bộ 194 bài

- 194 bài published; 0 bài có `views > 0`.
- 38 bài dưới 300 từ; 83 bài dưới 600 từ.
- 194/194 chưa có `reviewed_at` và chưa có `sources`.
- 2 bài có title/meta không khớp; 3 bài có name/slug không khớp.
- Hai bài Dirty Bomb có nội dung near-duplicate 100%.
- Nhiều bài game-news/review cũ không thể hiện giá trị riêng cho game developer.

## Lỗi kỹ thuật đã sửa an toàn

Baseline trên 30 bài:

- 30/30 HTTP 200 và canonical đúng.
- 30/30 có hai Article schema trùng nhau.
- 14/30 có nhiều hơn một H1 do body cũ chứa H1.
- Meta description render có thể dài hơn 160 ký tự.
- Article schema dùng author text cũ `Example` dù đã có author model `LamGame Team`.
- Avatar fallback `/images/default-avatar.png` trả 404.
- Auto internal-link còn trỏ `/world-cup-2026` đang trả 410.

Đã sửa ở lớp source/template, không ghi DB:

1. Chỉ render một Article schema, có `@id`, canonical entity, logo thật và author model.
2. Word count/reading time dùng Unicode để đếm tiếng Việt đúng hơn.
3. Body H1 cũ được hạ thành H2 lúc render; title trang là H1 duy nhất.
4. Meta description render được strip HTML, chuẩn hóa khoảng trắng và giới hạn 160 ký tự.
5. Related category dùng `FIND_IN_SET` thay vì `LIKE` để tránh category `1` khớp nhầm `10`.
6. Avatar fallback chuyển sang asset local tồn tại.
7. Gỡ auto-link tới route World Cup 410.

Sau sửa, 30/30 đạt: HTTP 200, canonical exact, một H1, JSON-LD hợp lệ, đúng một Article schema, meta description ≤160, không static-load AdSense. 49 ảnh local và các internal destination trong body đều không còn 404/410.

## 30 bài ưu tiên cần editorial action trong DB

| # | Bài | Từ | Rủi ro chính | Hành động đề xuất |
|---:|---|---:|---|---|
| 1 | [League of Legends — slug Top 10 game PC](https://lamgame.vn/blog/top-10-game-pc-cu-nhung-van-cuc-hay-2024) | 163 | Title/meta/slug sai chủ đề, mỏng, năm cũ | Xác minh nội dung thật; sửa name/meta hoặc slug + 301 nếu đổi URL |
| 2 | [FIFA Online 4 — slug Grand Chase M](https://lamgame.vn/blog/grand-chase-m-game-nhap-vai-hanh-dong-anime-3d-sieu-hot) | 161 | Title/meta/slug sai chủ đề, mỏng | Xác minh nội dung thật; sửa name/meta hoặc slug + 301 nếu đổi URL |
| 3 | [Tools phát triển game HTML5 2024](https://lamgame.vn/blog/tools-phat-trien-game-html5-tot-nhat-2024) | 73 | Rất mỏng, URL trong meta, không cấu trúc/nguồn | Viết lại tutorial hiện hành có benchmark/code hoặc archive |
| 4 | [Dirty Bomb — Splash Damage](https://lamgame.vn/blog/dirty-bomb-game-fps-mien-phi-hay-nhat-tu-splash-damage) | 518 | Trùng 100% bài #5 | Chọn URL chính, gộp nội dung và 301 URL phụ |
| 5 | [Dirty Bomb — Đánh giá chi tiết](https://lamgame.vn/blog/dirty-bomb-danh-gia-chi-tiet-game-fps-mien-phi) | 518 | Trùng 100% bài #4 | Chọn URL chính, gộp nội dung và 301 URL phụ |
| 6 | [Star Wars Battlefront 2 2017](https://lamgame.vn/blog/star-wars-battlefront-2-game-ban-sung-khung-2017) | 287 | Mỏng, stale, không nguồn | Refresh dưới góc nhìn game-dev hoặc archive sau xét duyệt |
| 7 | [Hướng dẫn Unity 2023](https://lamgame.vn/blog/huong-dan-unity-2023-tinh-nang-moi) | 162 | Tutorial mỏng, không code, đã cũ | Viết lại Unity hiện hành ≥900 từ, có project/code/screenshot |
| 8 | [5Kitu](https://lamgame.vn/blog/5kitu-game-ghep-chu-tieng-viet-gay-nghien) | 169 | Mỏng, không nguồn/cấu trúc | Phân tích thiết kế game chữ Việt hoặc archive |
| 9 | [Captain Heroes](https://lamgame.vn/blog/captain-heroes-game-sieu-anh-hung-mobile) | 181 | Mỏng, game review cũ | Refresh thành case study mobile game hoặc archive |
| 10 | [Cartel Kings](https://lamgame.vn/blog/cartel-kings-game-chien-thuat-b-ng-dang) | 214 | Mỏng, game review cũ | Refresh theo game design/monetization hoặc archive |
| 11 | [Call of Duty WWII](https://lamgame.vn/blog/call-of-duty-wwii-su-tro-lai-cua-thoi-ky-the-chien-2) | 132 | Rất mỏng, tin cũ | Archive hoặc viết lại thành phân tích thiết kế có nguồn |
| 12 | [Dota 2 — Ranked Match](https://lamgame.vn/blog/dota-2-valve-yeu-cau-so-dien-thoai-cho-ranked-match) | 281 | Tin cũ, không nguồn | Refresh theo anti-cheat/game systems hoặc archive |
| 13 | [Grand Chase M](https://lamgame.vn/blog/grand-chase-m-game-nhap-vai-hanh-dong-mobile-tuyet-voi) | 255 | Mỏng, review cũ | Case study combat/progression hoặc archive |
| 14 | [Grand Sphere](https://lamgame.vn/blog/grand-sphere-game-mobile-rpg-voi-do-hoa-tuyet-dep) | 290 | Mỏng, review cũ | Case study RPG mobile hoặc archive |
| 15 | [Minidom](https://lamgame.vn/blog/minidom-game-chien-thuat-thoi-gian-thuc-mini) | 298 | Mỏng, không nguồn | Phân tích RTS loop hoặc archive |
| 16 | [Resident Evil 0 HD](https://lamgame.vn/blog/resident-evil-0-hd-remaster-kinh-di-sinh-ton-kinh-dien) | 252 | Mỏng, review cũ | Phân tích level/horror design hoặc archive |
| 17 | [Nintendo New 2DS XL](https://lamgame.vn/blog/nintendo-new-2ds-xl-may-choi-game-cam-tay-moi) | 135 | Rất mỏng, tin hardware cũ | Archive; không ưu tiên rewrite |
| 18 | [Deformers Open Beta](https://lamgame.vn/blog/deformers-game-online-tu-the-order-1886-open-beta) | 259 | Tin beta cũ, không nguồn | Archive hoặc retrospective có nguồn |
| 19 | [StarCraft miễn phí](https://lamgame.vn/blog/starcraft-mien-phi-phien-ban-cho-khong-tu-blizzard) | 278 | Tin cũ, không nguồn | Refresh theo distribution strategy hoặc archive |
| 20 | [Riot hé lộ tướng thứ 135](https://lamgame.vn/blog/riot-he-lo-tuong-thu-135-cua-lien-minh-huyen-thoai) | 305 | URL trong meta, tin cũ | Archive hoặc retrospective champion design |
| 21 | [Game Design 101](https://lamgame.vn/blog/game-design-101-nguyen-tac-thiet-ke-game) | 206 | Pillar tiềm năng nhưng quá mỏng | Viết lại ≥1.200 từ với core loop, balancing và ví dụ thật |
| 22 | [Hướng dẫn game dàn trận thập niên 80](https://lamgame.vn/blog/huong-dan-tai-va-cai-dat-game-dan-tran-thap-nien-80-tren-pc-hien-dai-ylso) | 240 | Tutorial mỏng, không nguồn/code | Mở rộng quy trình hợp pháp; tránh link ROM/copyright |
| 23 | [Defender of the Crown 1986](https://lamgame.vn/blog/danh-gia-chi-tiet-game-dan-tran-defender-of-the-crown-1986-llzz) | 221 | Mỏng, ít topical fit | Retrospective game design có nguồn hoặc archive |
| 24 | [CS:GO 2024](https://lamgame.vn/blog/csgo-danh-gia-chi-tiet-game-fps-huyen-thoai-2024) | 237 | Mỏng, năm cũ, ít topical fit | Refresh thành case study FPS systems hoặc archive |
| 25 | [Boss Boxing](https://lamgame.vn/blog/boss-boxing-game-dam-boc-mobile-song-dong) | 354 | Ngắn, không nguồn/cấu trúc | Case study game Việt hoặc archive |
| 26 | [Overwatch — Eichenwald](https://lamgame.vn/blog/overwatch-cap-nhat-ban-do-eichenwald-moi) | 407 | Tin cũ, không nguồn | Phân tích map design hoặc archive |
| 27 | [Deadwalk: The Last War](https://lamgame.vn/blog/deadwalk-the-last-war-game-zombie-sinh-ton) | 301 | Ngắn, review cũ | Phân tích survival loop hoặc archive |
| 28 | [Bayonetta & Vanquish PC](https://lamgame.vn/blog/bayonetta-vanquish-ban-cap-nhat-moi-cho-pc) | 440 | Tin cũ, không nguồn | Retrospective port/performance hoặc archive |
| 29 | [HIT mobile](https://lamgame.vn/blog/hit-game-mobile-hanh-dong-cuc-hap-dan) | 498 | Ngắn, review cũ | Case study action mobile hoặc archive |
| 30 | [Hidden Heroes](https://lamgame.vn/blog/hidden-heroes-game-chien-thuat-an-danh-hay-ho) | 317 | Ngắn, review cũ | Phân tích strategy design hoặc archive |

## Thứ tự editorial đề xuất

### P0 — cần quyết định trước khi sửa DB

1. Sửa hai record name/meta/slug sai chủ đề (#1, #2).
2. Gộp cặp Dirty Bomb và thiết lập 301 (#4, #5).
3. Viết lại hoặc archive bài HTML5 73 từ (#3).

### P1 — nên giữ và viết lại vì phù hợp định vị

- Hướng dẫn Unity 2023 → bản Unity hiện hành.
- Game Design 101 → pillar page.
- Tools HTML5 → benchmark/tutorial thực hành.
- Các case study game Việt: 5Kitu, Boss Boxing nếu xác minh được nguồn.

### P2 — review/news cũ

Chỉ giữ khi có thể bổ sung phân tích gốc cho game developer. Nếu không, archive/410/noindex theo từng URL sau khi kiểm tra Search Console và backlink. Không xóa/noindex hàng loạt trong lúc AdSense đang xét duyệt.

## AdSense invariants

- Không thay đổi publisher, `ads.txt`, consent mode hoặc route allowlist.
- 30/30 trang không static-load `adsbygoogle.js`; script chỉ được tạo sau Advertising consent.
- Không thêm ad unit gần code, video, nút Play/Download.
- Không mở rộng AdSense sang Source Game, game play, checkout, AI Tools hoặc Hire.
- Không tự động viết lại 30 bài bằng AI; từng bài phải có nguồn, ví dụ/code hoặc phân tích gốc và editorial review.

## Việc chưa thực hiện vì cần quyền sửa production DB

- Không cập nhật `name`, `slug`, meta, description, `reviewed_at`, `reviewed_by` hoặc `sources`.
- Không merge, redirect, archive, noindex hay xóa bài.
- Không thay đổi ngày publish/update.

Các thay đổi này cần được duyệt theo từng batch nhỏ, giữ URL hoặc có 301 rõ ràng, rồi validate HTTP/canonical/schema/sitemap sau mỗi batch.
