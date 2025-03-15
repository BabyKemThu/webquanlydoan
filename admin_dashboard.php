<?php
include "config.php";

// Kiểm tra quyền admin
if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "admin") {
    echo "<script>alert('Bạn không có quyền truy cập!'); window.location='index.php';</script>";
    exit();
}

// Lấy danh sách sản phẩm
$result_products = $conn->query("SELECT * FROM products");
// Lấy danh sách đơn hàng
$result_orders = $conn->query("SELECT * FROM orders");

// Xóa đơn hàng
if (isset($_GET['delete_order'])) {
    $order_id = $_GET['delete_order'];
    $conn->query("DELETE FROM orders WHERE id='$order_id'");
    echo "<script>alert('Đã xóa đơn hàng!'); window.location='admin_dashboard.php';</script>";
}

// Cập nhật trạng thái đơn hàng
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_order"])) {
    $id = $_POST["id"];
    $status = $_POST["status"];
    $conn->query("UPDATE orders SET status='$status' WHERE id='$id'");
    echo "<script>alert('Đã cập nhật đơn hàng!'); window.location='admin_dashboard.php';</script>";
}

// Xóa sản phẩm
if (isset($_GET['delete_product'])) {
    $product_id = $_GET['delete_product'];
    $conn->query("DELETE FROM products WHERE id='$product_id'");
    echo "<script>alert('Đã xóa sản phẩm!'); window.location='admin_dashboard.php';</script>";
}

// Thêm sản phẩm
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add_product"])) {
    $name = $_POST["name"];
    $price = $_POST["price"];
    $image = $_FILES["image"]["name"];
    $target = "uploads/" . basename($image);
    move_uploaded_file($_FILES["image"]["tmp_name"], $target);
    $conn->query("INSERT INTO products (name, price, image) VALUES ('$name', '$price', '$image')");
    echo "<script>alert('Đã thêm sản phẩm!'); window.location='admin_dashboard.php';</script>";
}

// Chỉnh sửa sản phẩm
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit_product"])) {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $price = $_POST["price"];
    $image = $_FILES["image"]["name"];
    if ($image) {
        $target = "upload/" . basename($image);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target);
        $conn->query("UPDATE products SET name='$name', price='$price', image='$image' WHERE id='$id'");
    } else {
        $conn->query("UPDATE products SET name='$name', price='$price' WHERE id='$id'");
    }
    echo "<script>alert('Đã cập nhật sản phẩm!'); window.location='admin_dashboard.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🎀 Quản trị - Cửa hàng đồ ăn vặt Em Thư 🍭</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <style>
        body {
            background-color: #FFC0CB;
            animation: fadeIn 1s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .btn {
            transition: transform 0.2s;
        }
        .btn:hover {
            transform: scale(1.05);
        }
        img {
            transition: transform 0.3s;
        }
        img:hover {
            transform: rotate(3deg) scale(1.05);
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
    <h2 class="text-center mt-5">🍲 Quản lý Đồ ăn</h2>
    <button class="btn btn-primary" onclick="document.getElementById('addForm').style.display='block'">➕ Thêm sản phẩm</button>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>🍔 Tên món</th>
                <th>💰 Giá</th>
                <th>📷 Ảnh</th>
                <th>⚙️ Chức năng</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result_products->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row["id"]; ?></td>
                    <td><?php echo $row["name"]; ?></td>
                    <td><?php echo $row["price"]; ?> VND</td>
                    <td><img src="upload/<?php echo $row["image"]; ?>" width="100"></td>
                    <td>
                        <a href="?delete_product=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?');">🗑️ Xóa</a>
                        <a href="edit_product.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">✏️ Sửa</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<div class="container">
    <h2 class="text-center mt-5">📦 Quản lý Đơn hàng</h2>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>👤 Khách hàng</th>
                <th>💰 Tổng tiền</th>
                <th>📅 Ngày đặt</th>
                <th>📌 Trạng thái</th>
                <th>⚙️ Chức năng</th>
            </tr>
        </thead>
        <tbody>
              <?php while ($row = $result_orders->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row["id"]; ?></td>
                    <td><?php echo $row["name"]; ?></td>
                    <td><?php echo $row["total_price"]; ?> VND</td>
                    <td><?php echo $row["created_at"]; ?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <select name="status" class="form-select">
                                <option value="Đang xử lý" <?php if ($row["status"] == "Đang xử lý") echo "selected"; ?>>Đang xử lý</option>
                                <option value="Đã xác nhận" <?php if ($row["status"] == "Đã xác nhận") echo "selected"; ?>>Đã xác nhận</option>
                                <option value="Hoàn thành" <?php if ($row["status"] == "Hoàn thành") echo "selected"; ?>>Hoàn thành</option>
                            </select>
                            <button type="submit" name="update_order" class="btn btn-primary mt-2">✔️ Cập nhật</button>
                        </form>
                    </td>
                    <td>
                        <a href="?delete_order=<?php echo $row['id']; ?>" class="btn btn-danger">🗑️ Xóa</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
