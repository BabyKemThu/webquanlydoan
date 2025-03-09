<?php
include "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $taikhoan = trim($_POST["taikhoan"]);
    $matkhau = trim($_POST["matkhau"]);

    if (empty($taikhoan) || empty($matkhau)) {
        echo "<script>alert('Vui lòng nhập đầy đủ tài khoản và mật khẩu!');</script>";
    } else {
        // Sử dụng Prepared Statements để tránh SQL Injection
        $stmt = $conn->prepare("SELECT iduser, taikhoan, matkhau FROM user WHERE taikhoan = ?");
        $stmt->bind_param("s", $taikhoan);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Kiểm tra mật khẩu
            if (password_verify($matkhau, $row["matkhau"])) {
                $_SESSION["user"] = [
                    "iduser" => $row["iduser"],
                    "taikhoan" => $row["taikhoan"]
                ];
                echo "<script>alert('Đăng nhập thành công!'); window.location='index.php';</script>";
            } else {
                echo "<script>alert('Sai mật khẩu!');</script>";
            }
        } else {
            echo "<script>alert('Tài khoản không tồn tại!');</script>";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #FCEEF5 !important;
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
            color: #882D61;
            margin-bottom: 20px;
        }
        .btn-login {
            background-color: #FF85A2;
            border: none;
            width: 100%;
            padding: 10px;
            font-size: 1.1rem;
            color: white;
            border-radius: 5px;
        }
        .btn-login:hover {
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
    <h2>Đăng nhập</h2>
    <form method="post">
        <input type="text" name="taikhoan" class="form-control mb-3" placeholder="Tài khoản" required>
        <input type="password" name="matkhau" class="form-control mb-3" placeholder="Mật khẩu" required>
        <button type="submit" class="btn btn-login">Đăng nhập</button>
    </form>
    <p class="text-center mt-3">
        <a href="register.php">Chưa có tài khoản? Đăng ký ngay</a>
    </p>
</div>

</body>
</html>