<?php
session_start();

// Chặn truy cập nếu giỏ hàng trống
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Xử lý xóa sản phẩm khỏi giỏ hàng
if (isset($_POST["remove"])) {
    $product_id = $_POST["product_id"];
    foreach ($_SESSION["cart"] as $key => $item) {
        if ($item["id"] == $product_id) {
            unset($_SESSION["cart"]["$key"]);
            break;
        }
    }
    $_SESSION["cart"] = array_values($_SESSION["cart"]); // Reset key array
}

// Xử lý cập nhật số lượng sản phẩm
if (isset($_POST["update_quantity"])) {
    $product_id = $_POST["product_id"];
    $new_quantity = max(1, (int)$_POST["quantity"]);

    foreach ($_SESSION["cart"] as &$item) {
        if ($item["id"] == $product_id) {
            $item["quantity"] = $new_quantity;
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng - Cửa hàng đồ ăn vặt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #ffe6f2;
        }
        .navbar {
            background-color: #ff85a2 !important;
        }
        .cart-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
        }
        .cart-item {
            border-bottom: 1px solid #ff85a2;
        }
        .btn-primary {
            background-color: #ff4d79;
            border: none;
        }
        .btn-primary:hover {
            background-color: #e6005c;
        }
        .btn-danger {
            background-color: #ff4d4d;
            border: none;
        }
        .btn-danger:hover {
            background-color: #cc0000;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">Cửa hàng đồ ăn vặt</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link text-white" href="index.php">Trang chủ</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="cart.php">Giỏ hàng</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h2 class="text-center text-danger">🛒 Giỏ hàng của bạn</h2>
    <div class="cart-container shadow-sm">
        <?php if (!empty($_SESSION["cart"])): ?>
            <?php $total = 0; ?>
            <?php foreach ($_SESSION["cart"] as $item): ?>
                <div class="cart-item d-flex justify-content-between align-items-center py-3">
                    <img src="upload/<?php echo $item['image']; ?>" width="80" height="80" class="rounded">
                    <div>
                        <p class="mb-1 fw-bold text-dark"><?php echo $item['name']; ?></p>
                        <p class="mb-1 text-danger fw-bold"><?php echo number_format($item['price'], 0, ",", "."); ?> VNĐ</p>
                        <form method="post" action="" class="d-inline">
                            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" class="text-center w-25">
                            <button type="submit" name="update_quantity" class="btn btn-sm btn-primary">Cập nhật</button>
                        </form>
                        <form method="post" action="" class="d-inline">
                            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                            <button type="submit" name="remove" class="btn btn-sm btn-danger">Xóa</button>
                        </form>
                    </div>
                </div>
                <?php $total += $item['price'] * $item['quantity']; ?>
            <?php endforeach; ?>
            <p class="total-price text-end fw-bold mt-3 text-danger">Tổng cộng: <?php echo number_format($total, 0, ",", "."); ?> VNĐ</p>
            <?php if (isset($_SESSION["user"])): ?>
                <a href="checkout.php" class="btn btn-success w-100">Thanh toán</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-warning w-100" onclick="return confirm('Bạn cần đăng nhập để thanh toán. Chuyển đến trang đăng nhập?');">Đăng nhập để thanh toán</a>
            <?php endif; ?>
        <?php else: ?>
            <p class="text-center text-muted">Giỏ hàng trống.</p>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>