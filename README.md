# webquanlydoan

Website PHP (thuần) cho bài tập/đồ án: đăng nhập/đăng ký, xem sản phẩm, giỏ hàng, thanh toán và trang quản trị để quản lý sản phẩm/đơn hàng.

## Tính năng chính
### Người dùng
- Đăng ký / Đăng nhập / Đăng xuất
- Xem danh sách sản phẩm
- Thêm vào giỏ hàng, cập nhật giỏ hàng
- Checkout / đặt hàng

### Quản trị (Admin)
- Dashboard quản trị
- Thêm / sửa / xoá sản phẩm
- Cập nhật trạng thái đơn hàng

## Công nghệ
- PHP (thuần)
- MySQL/MariaDB
- HTML/CSS/JS (tùy theo code trong dự án)
- Khuyến nghị chạy bằng XAMPP/WAMP/Laragon

## Cấu trúc thư mục (tóm tắt)
- `index.php`: trang chính
- `login.php`, `register.php`, `logout.php`: xác thực
- `cart.php`, `add_to_cart.php`, `checkout.php`, `success.php`: giỏ hàng & thanh toán
- `admin_dashboard.php`, `add_product.php`, `edit_product.php`, `delete_product.php`: quản trị sản phẩm
- `update_order.php`: cập nhật đơn hàng
- `upload/`: lưu file upload (ảnh sản phẩm, ...)

## Cài đặt & chạy local
### 1) Chuẩn bị
- Cài XAMPP (hoặc WAMP/Laragon)
- Bật Apache + MySQL

### 2) Clone source
```bash
git clone https://github.com/BabyKemThu/webquanlydoan.git
