<?php
include "config.php";

// Kiểm tra xem người dùng đã đăng nhập và có quyền admin chưa
if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "admin") {
    echo "<script>alert('Bạn không có quyền truy cập!'); window.location='index.php';</script>";
    exit();
}

// Lấy danh sách sản phẩm
$result_products = $conn->query("SELECT * FROM products");
// Lấy danh sách người dùng
$result_users = $conn->query("SELECT iduser, taikhoan, role FROM user");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🎀 Quản trị - Cửa hàng đồ ăn vặt Em Kem 🍭</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #FFE4E1;
            font-family: 'Arial', sans-serif;
        }
        .container {
            margin-top: 20px;
        }
        h2 {
            color: #D63384;
        }
        .btn-custom {
            background-color: #FF69B4;
            color: white;
            font-weight: bold;
        }
        .btn-custom:hover {
            background-color: #FF1493;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #FFC0CB;">
    <div class="container">
        <a class="navbar-brand" href="admin_dashboard.php">🍩 Quản trị Cửa hàng 🍬</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link btn btn-danger text-white" href="logout.php">🚪 Đăng xuất</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <h2 class="text-center">🍡 Quản lý Sản phẩm</h2>
    <a href="add_product.php" class="btn btn-custom mb-3">➕ Thêm sản phẩm mới</a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>🍰 Tên sản phẩm</th>
                <th>💰 Giá</th>
                <th>🖼️ Hình ảnh</th>
                <th>⚙️ Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result_products->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row["id"]; ?></td>
                    <td><?php echo $row["name"]; ?></td>
                    <td><?php echo number_format($row["price"], 0, ",", "."); ?> VNĐ</td>
                    <td><img src="upload/<?php echo $row["image"]; ?>" width="50"></td>
                    <td>
                        <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">✏️ Chỉnh sửa</a>
                        <a href="delete_product.php?id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?');">🗑️ Xóa</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <h2 class="text-center mt-5">👥 Quản lý Người dùng</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>👤 Tài khoản</th>
                <th>🔑 Vai trò</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result_users->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row["iduser"]; ?></td>
                    <td><?php echo $row["taikhoan"]; ?></td>
                    <td><?php echo ucfirst($row["role"]); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
