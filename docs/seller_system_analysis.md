# Phân Tích Hệ Thống Quản Lý Người Bán (Seller Management System)

Tài liệu này phân tích chi tiết cấu trúc dữ liệu và luồng nghiệp vụ của hệ thống Marketplace liên quan đến Người dùng, Người bán và Cửa hàng.

---

## 1. Cấu Trúc Bảng Dữ Liệu

### 1.1 Bảng `users`
Lưu trữ thông tin cốt lõi của tất cả tài khoản. Hệ thống sử dụng cơ chế "Phân vai" (Role-based) thông qua các cờ trạng thái thay vì tách bảng riêng.

| Trường | Kiểu dữ liệu | Mô tả |
| :--- | :--- | :--- |
| `id` | BIGINT | Khóa chính tự tăng. |
| `is_seller` | TINYINT(1) | **0**: Khách hàng, **1**: Người bán. |
| `seller_active` | TINYINT | **0**: Chờ duyệt, **1**: Đã duyệt, **2**: Từ chối. |
| `featured` | TINYINT | **1**: Shop nổi bật (hiển thị ưu tiên), **0**: Bình thường. |
| `balance` | DECIMAL(28,8)| Số dư ví của người dùng (tiền bán hàng tích lũy). |
| `id_card` | VARCHAR | Số CCCD/Passport dùng để định danh pháp lý khi làm Seller. |
| `bank_name` | VARCHAR | Tên ngân hàng nhận tiền. |
| `bank_account_number` | VARCHAR | Số tài khoản ngân hàng. |
| `bank_branch` | VARCHAR | Chi nhánh ngân hàng. |
| `seller_activated_at`| DATETIME | Ngày Admin phê duyệt lên Seller. |
| `kv` | TINYINT | Trạng thái KYC (Xác thực danh tính chuyên sâu). |

### 1.2 Bảng `shops`
Chứa thông tin cấu hình giao diện và vận hành của từng gian hàng. Một Seller (User) sẽ sở hữu một Shop.

| Trường | Kiểu dữ liệu | Mô tả |
| :--- | :--- | :--- |
| `seller_id` | BIGINT | FK liên kết với `users.id`. |
| `name` | VARCHAR | Tên hiển thị của gian hàng (Brand name). |
| `phone` | VARCHAR | Hotline riêng của cửa hàng. |
| `logo` | VARCHAR | Đường dẫn ảnh đại diện shop. |
| `cover` | VARCHAR | Đường dẫn ảnh bìa shop. |
| `address` | TEXT | Địa chỉ lấy hàng/văn phòng của shop. |
| `opens_at` | TIME | Giờ mở cửa. |
| `closed_at` | TIME | Giờ đóng cửa. |
| `social_links` | JSON | Lưu các link Facebook, TikTok, Instagram... |
| `meta_title` | VARCHAR | Tiêu đề SEO của shop. |

### 1.3 Bảng `sell_logs`
Lưu trữ lịch sử giao dịch bán hàng để phục vụ thống kê và đối soát.

| Trường | Kiểu dữ liệu | Mô tả |
| :--- | :--- | :--- |
| `seller_id` | BIGINT | Người bán nhận tiền. |
| `product_id` | BIGINT | Sản phẩm được bán. |
| `qty` | INT | Số lượng trong đơn hàng. |
| `product_price` | DECIMAL | Giá bán tại thời điểm giao dịch. |
| `product_commission`| DECIMAL | Phí hệ thống thu (theo % hoặc cố định). |
| `after_commission` | DECIMAL | Số tiền thực tế cộng vào ví Seller. |

---

## 2. Luồng Nghiệp Vụ (Workflow)

### 2.1 Đăng ký Người bán (Onboarding)
1.  **Giai đoạn 1**: Người dùng đăng ký tài khoản Customer thông thường.
2.  **Giai đoạn 2**: Người dùng nhấn "Đăng ký bán hàng". Hệ thống yêu cầu cung cấp:
    *   Thông tin cá nhân (Họ tên, CCCD).
    *   Thông tin thanh toán (Ngân hàng).
3.  **Giai đoạn 3**: Tài khoản được set `is_seller = 1` nhưng `seller_active = 0`.
    *   *Hạn chế*: Seller không thể đăng nhập vào trang Dashboard Seller ở giai đoạn này.

### 2.2 Phê duyệt (Approval)
1.  Admin nhận thông báo có Seller mới.
2.  Admin kiểm tra tính hợp lệ của CCCD và ngân hàng.
3.  **Duyệt**: Hệ thống set `seller_active = 1`, ghi nhận ngày `seller_activated_at`.
4.  **Từ chối**: Hệ thống set `seller_active = 2` (hoặc reset về 0) và có thể gửi email thông báo lý do.

### 2.3 Quản lý Tài chính
*   **Ví tiền**: Mọi giao dịch thành công sẽ trừ phí hệ thống (Commission) và cộng vào `balance` của User.
*   **Rút tiền**: Seller thực hiện lệnh rút tiền dựa trên `balance`. Admin sẽ chuyển khoản vào thông tin ngân hàng đã lưu trong bảng `users`.

---

## 3. Quy Tắc Bảo Mật & Ràng Buộc
*   Một tài khoản `users` chỉ được liên kết với **duy nhất một** bản ghi `shops`.
*   Chỉ những User có `is_seller = 1` AND `seller_active = 1` mới được phép truy cập các Route bắt đầu bằng `/seller/*`.
*   Thông tin nhạy cảm như `id_card` và `bank_account_number` chỉ hiển thị trong trang quản trị nội bộ (Admin) và trang cấu hình của chính Seller đó.
