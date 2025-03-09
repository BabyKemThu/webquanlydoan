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

// Xử lý thêm vào giỏ hàng
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] += 1;
            $found = true;
            break;
        }
    }

    if (!$found) {
        $_SESSION['cart'][] = [
            'id' => $product_id,
            'name' => $product_name,
            'price' => $product_price,
            'image' => $product_image,
            'quantity' => 1
        ];
    }

    header("Location: cart.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang chủ - Cửa hàng đồ ăn vặt Em Kem</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #FFF0F5;
            font-family: 'Arial', sans-serif;
        }
        .navbar {
            background-color: #FAD2E1 !important;
        }
        .navbar-nav .nav-link {
            color: #6A0572 !important;
            font-weight: bold;
        }
        .navbar-nav .nav-link:hover {
            color: #ff4081 !important;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: 0.3s;
        }
        .card:hover {
            transform: scale(1.05);
        }
        .btn-custom {
            background-color: #ff69b4;
            color: white;
            font-weight: bold;
        }
        .btn-custom:hover {
            background-color: #ff4081;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">🍭 Cửa hàng đồ ăn vặt Em Kem 🍬</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">Trang chủ</a></li>
                <li class="nav-item"><a class="nav-link" href="cart.php">Giỏ hàng</a></li>
                <?php if (isset($_SESSION["user"])): ?>
                    <li class="nav-item"><a class="nav-link">Xin chào, <strong><?php echo $_SESSION["user"]["taikhoan"]; ?></strong></a></li>
                    <li class="nav-item"><a class="nav-link btn btn-danger text-white" href="logout.php">Đăng xuất</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link btn btn-primary text-white" href="login.php">Đăng nhập</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-warning text-white" href="register.php">Đăng ký</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h2 class="text-center mb-4" style="color: #882D61;">🍓 Danh Sách Sản Phẩm 🍓</h2>
    <?php if (isset($_SESSION["user"])): ?>
        <a href="add_product.php" class="btn btn-custom mb-3">➕ Thêm Sản Phẩm Mới</a>
    <?php endif; ?>
    <div class="row">
        <?php
        $result = $conn->query("SELECT * FROM products");
        while ($row = $result->fetch_assoc()):
        ?>
        <div class="col-md-4">
            <div class="card mb-4">
                <img src="upload/<?php echo $row["image"]; ?>" class="card-img-top" alt="<?php echo $row["name"]; ?>" style="height: 200px; object-fit: cover;">
                <div class="card-body text-center">
                    <h5 class="card-title" style="color: #D63384;"><?php echo $row["name"]; ?></h5>
                    <p class="card-text"><strong><?php echo number_format($row["price"], 0, ",", "."); ?> VNĐ</strong></p>
                    <form method="post" action="">
                        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="product_name" value="<?php echo $row['name']; ?>">
                        <input type="hidden" name="product_price" value="<?php echo $row['price']; ?>">
                        <input type="hidden" name="product_image" value="<?php echo $row['image']; ?>">
                        <button type="submit" name="add_to_cart" class="btn btn-custom w-100">🛒 Thêm vào giỏ</button>
                    </form>
                    <?php if (isset($_SESSION["user"])): ?>
                        <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn btn-warning w-100 mt-2">✏️ Chỉnh sửa</a>
                        <a href="delete_product.php?id=<?php echo $row['id']; ?>" class="btn btn-danger w-100 mt-2" onclick="return confirm('Bạn có chắc chắn muốn xóa?');">🗑️ Xóa</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>