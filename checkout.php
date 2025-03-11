<?php
include "config.php";

// Xử lý khi nhấn "Đặt hàng"
if (isset($_POST['place_order'])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    if (empty($_SESSION['cart'])) {
        $error = "Giỏ hàng trống!";
    } else {
        // Tính tổng tiền đơn hàng
        $total_price = 0;
        foreach ($_SESSION['cart'] as $item) {
            $total_price += $item['price'] * $item['quantity'];
        }

        // Lưu đơn hàng vào database
        $stmt = $conn->prepare("INSERT INTO orders (name, phone, address, total_price) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $name, $phone, $address, $total_price);
        $stmt->execute();
        $order_id = $stmt->insert_id; // Lấy ID đơn hàng vừa tạo
        $stmt->close();

        // Lưu sản phẩm vào order_items
        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        foreach ($_SESSION['cart'] as $item) {
            $stmt->bind_param("iiii", $order_id, $item['id'], $item['quantity'], $item['price']);
            $stmt->execute();
        }
        $stmt->close();

        // Xóa giỏ hàng sau khi đặt hàng thành công
        unset($_SESSION['cart']);

        // Chuyển hướng đến trang thông báo thành công
        header("Location: success.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #FCEEF5 !important;
            font-family: 'Arial', sans-serif;
        }
        .checkout-container {
            background-color: #fff;
            border-radius: 12px;
            padding: 30px;
            max-width: 500px;
            margin: 50px auto;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #882D61;
            text-align: center;
            margin-bottom: 20px;
        }
        .form-label {
            font-weight: bold;
            color: #882D61;
        }
        .btn-order {
            background-color: #FF85A2;
            color: white;
            font-size: 18px;
            padding: 10px;
            border-radius: 5px;
            border: none;
            width: 100%;
            transition: 0.3s;
        }
        .btn-order:hover {
            background-color: #E06682;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="checkout-container">
            <h2>Thông tin nhận hàng</h2>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"> <?php echo $error; ?> </div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="mb-3">
                    <label class="form-label">Họ và Tên</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text" name="phone" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Địa chỉ</label>
                    <textarea name="address" class="form-control" rows="3" required></textarea>
                </div>
                <button type="submit" name="place_order" class="btn-order">Đặt hàng</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>