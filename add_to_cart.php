<?php
session_start();

$servername = "database-1.c3gcga4mwg5b.ap-southeast-2.rds.amazonaws.com";
$username = "admin";
$password = "12345678";
$dbname = "thuthu";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Xóa sản phẩm khỏi giỏ hàng
if (isset($_POST['remove'])) {
    $id = $_POST['id'];
    unset($_SESSION['cart'][$id]);
}

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #FCEEF5 !important;
            font-family: 'Arial', sans-serif;
        }
        .navbar {
            background-color: #FAD2E1 !important;
        }
        .cart-item {
            display: flex;
            align-items: center;
            background: white;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .cart-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }
        .cart-item-info {
            flex-grow: 1;
            padding: 0 15px;
        }
        .cart-item-actions {
            text-align: right;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">Cửa hàng đồ ăn vặt</a>
    </div>
</nav>

<div class="container mt-4">
    <h2 class="text-center mb-4" style="color: #882D61;">Giỏ hàng của bạn</h2>
    <?php if (!empty($_SESSION['cart'])): ?>
        <?php foreach ($_SESSION['cart'] as $id => $quantity):
            $result = $conn->query("SELECT * FROM products WHERE id = $id");
            $row = $result->fetch_assoc();
        ?>
        <div class="cart-item">
            <img src="upload/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
            <div class="cart-item-info">
                <h5><?php echo $row['name']; ?></h5>
                <p><strong><?php echo number_format($row['price'], 0, ',', '.'); ?> VNĐ</strong></p>
                <p>Số lượng: <?php echo $quantity; ?></p>
            </div>
            <div class="cart-item-actions">
                <form method="POST">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <button type="submit" name="remove" class="btn btn-danger">Xóa</button>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
        <div class="text-end mt-4">
            <a href="checkout.php" class="btn btn-success">Thanh toán</a>
        </div>
    <?php else: ?>
        <p class="text-center">Giỏ hàng trống!</p>
    <?php endif; ?>
</div>
</body>
</html>