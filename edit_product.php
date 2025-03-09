<?php
include "config.php";

// Lấy ID sản phẩm từ URL
if (isset($_GET["id"])) {
    $id = intval($_GET["id"]);
    $result = $conn->query("SELECT * FROM products WHERE id=$id");

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "<script>alert('Sản phẩm không tồn tại!'); window.location='index.php';</script>";
        exit();
    }
}

// Xử lý cập nhật sản phẩm
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST["name"]);
    $price = floatval($_POST["price"]);
    
    if (!empty($_FILES["image"]["name"])) {
        $target_dir = "upload/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        $image = basename($_FILES["image"]["name"]);
        $sql = "UPDATE products SET name='$name', price='$price', image='$image' WHERE id=$id";
    } else {
        $sql = "UPDATE products SET name='$name', price='$price' WHERE id=$id";
    }

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Cập nhật thành công!'); window.location='index.php';</script>";
        exit();
    } else {
        echo "<script>alert('Lỗi cập nhật!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #FCEEF5;
            font-family: 'Arial', sans-serif;
        }
        .container {
            max-width: 500px;
            margin-top: 50px;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #D63384;
            margin-bottom: 20px;
        }
        .btn-primary {
            background-color: #FF85A2;
            border: none;
        }
        .btn-primary:hover {
            background-color: #E06682;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Chỉnh sửa sản phẩm</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Tên sản phẩm</label>
            <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($product['name']); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Giá</label>
            <input type="number" step="0.01" name="price" class="form-control" value="<?php echo $product['price']; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Ảnh hiện tại</label><br>
            <img src="upload/<?php echo $product['image']; ?>" class="img-fluid rounded" style="max-width: 200px;">
        </div>
        <div class="mb-3">
            <label class="form-label">Cập nhật ảnh mới</label>
            <input type="file" name="image" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary w-100">Lưu thay đổi</button>
        <a href="index.php" class="btn btn-secondary w-100 mt-2">Hủy</a>
    </form>
</div>

</body>
</html>