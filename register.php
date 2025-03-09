<?php
require_once "config.php"; // Kết nối database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $taikhoan = trim($_POST["taikhoan"]);
    $matkhau = password_hash($_POST["matkhau"], PASSWORD_DEFAULT); // Mã hóa mật khẩu

    // Kiểm tra tài khoản đã tồn tại chưa
    $checkUser = $conn->prepare("SELECT iduser FROM user WHERE taikhoan = ?");
    $checkUser->bind_param("s", $taikhoan);
    $checkUser->execute();
    $checkUser->store_result();

    if ($checkUser->num_rows > 0) {
        echo "Tài khoản đã tồn tại!";
    } else {
        // Chèn dữ liệu vào bảng user
        $stmt = $conn->prepare("INSERT INTO user (taikhoan, matkhau) VALUES (?, ?)");
        $stmt->bind_param("ss", $taikhoan, $matkhau);
        
        if ($stmt->execute()) {
            echo "Đăng ký thành công! <a href='login.php'>Đăng nhập ngay</a>";
        } else {
            echo "Lỗi khi đăng ký: " . $stmt->error;
        }
    }

    $checkUser->close();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #FCEEF5 !important; /* Nền hồng nhạt */
            font-family: 'Arial', sans-serif;
        }
        .container {
            max-width: 400px;
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
        .btn-register {
            background-color: #FF85A2; /* Hồng pastel đậm */
            border: none;
            width: 100%;
            padding: 10px;
            font-size: 1.1rem;
            color: white;
            border-radius: 5px;
        }
        .btn-register:hover {
            background-color: #E06682;
        }
        .text-center a {
            color: #C85C8E;
            text-decoration: none;
        }
        .text-center a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Đăng ký tài khoản</h2>
    <form method="post">
        <input type="text" name="taikhoan" class="form-control mb-3" placeholder="Tài khoản" required>
        <input type="password" name="matkhau" class="form-control mb-3" placeholder="Mật khẩu" required>
        <button type="submit" class="btn btn-register">Đăng ký</button>
    </form>
    <p class="text-center mt-3">
        <a href="login.php">Đã có tài khoản? Đăng nhập ngay</a>
    </p>
</div>

</body>
</html>