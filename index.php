<?php

include "config.php";

// Kiểm tra xem người dùng có đăng nhập không
$user_role = isset($_SESSION["user"]) ? $_SESSION["user"]["role"] : "guest";
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #FCEEF5 !important; font-family: 'Arial', sans-serif; }
        .container { margin-top: 50px; }
        .btn-custom { background-color: #FF85A2; color: white; border: none; padding: 10px 20px; }
        .btn-custom:hover { background-color: #E06682; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="#">🍭 Cửa Hàng Đồ Ăn Vặt</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if ($user_role == "user"): ?>
                    <li class="nav-item"><a class="nav-link" href="cart.php">🛒 Giỏ hàng</a></li>
                <?php endif; ?>
                <?php if (isset($_SESSION["user"])): ?>
                    <li class="nav-item"><a class="nav-link" href="logout.php">🚪 Đăng xuất</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="login.php">🔑 Đăng nhập</a></li>
                    <li class="nav-item"><a class="nav-link" href="register.php">📝 Đăng ký</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <h2 class="text-center">🍩 Danh sách sản phẩm</h2>

    <!-- Chỉ admin có quyền thêm sản phẩm -->
    <?php if ($user_role == "admin"): ?>
        <a href="add_product.php" class="btn btn-custom mb-3">➕ Thêm Sản Phẩm</a>
    <?php endif; ?>

    <div class="row">
        <?php
        $result = $conn->query("SELECT * FROM products");
        while ($row = $result->fetch_assoc()):
        ?>
            <div class="col-md-4">
                <div class="card mb-4">
                    <img src="upload/<?php echo $row['image']; ?>" class="card-img-top" alt="Sản phẩm">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $row['name']; ?></h5>
                        <p class="card-text"><?php echo number_format($row['price'], 0, ',', '.'); ?> VNĐ</p>
                        <a href="product_detail.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">🔍 Xem chi tiết</a>

                        <!-- Nút thêm vào giỏ hàng (Chỉ hiển thị với customer) -->
                        <?php if ($user_role == "user"): ?>
                            <form action="add_to_cart.php" method="POST" style="display:inline;">
                                <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="btn btn-success">🛒 Thêm vào giỏ</button>
                            </form>
                        <?php endif; ?>

                        <!-- Chỉ admin có quyền chỉnh sửa/xóa sản phẩm -->
                        <?php if ($user_role == "admin"): ?>
                            <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">✏️ Chỉnh sửa</a>
                            <a href="delete_product.php?id=<?php echo $row['id']; ?>" class="btn btn-danger"
                               onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">🗑️ Xóa</a>
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
