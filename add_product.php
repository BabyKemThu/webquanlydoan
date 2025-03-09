<?php
include "config.php";
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $price = $_POST["price"];

    // Upload ảnh
    $image = $_FILES["image"]["name"];
    $target = "upload/" . basename($image);
    move_uploaded_file($_FILES["image"]["tmp_name"], $target);

    $sql = "INSERT INTO products (name, price, image) VALUES ('$name', '$price', '$image')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Thêm sản phẩm thành công!'); window.location='index.php';</script>";
    } else {
        echo "Lỗi: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #FCEEF5 !important; /* Nền hồng nhạt */
            font-family: 'Arial', sans-serif;
        }
        .container {
            max-width: 450px;
            margin-top: 80px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #882D61; /* Hồng đậm */
            margin-bottom: 20px;
        }
        .form-control {
            border-radius: 5px;
        }
        .btn-submit {
            background-color: #FF85A2; /* Hồng pastel đậm */
            border: none;
            width: 100%;
            padding: 10px;
            font-size: 1.1rem;
            color: white;
            border-radius: 5px;
        }
        .btn-submit:hover {
            background-color: #E06682;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Thêm sản phẩm</h2>
    <form method="post" enctype="multipart/form-data">
        <input type="text" name="name" class="form-control mb-3" placeholder="Tên đồ ăn" required>
        <input type="number" name="price" class="form-control mb-3" placeholder="Giá đồ ăn" required>
        <input type="file" name="image" class="form-control mb-3" required>
        <button type="submit" class="btn btn-submit">Thêm sản phẩm</button>
    </form>
</div>

</body>
</html>