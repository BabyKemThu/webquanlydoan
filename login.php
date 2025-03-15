<?php
include "config.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $taikhoan = trim($_POST["taikhoan"]);
    $matkhau = trim($_POST["matkhau"]);

    if (empty($taikhoan) || empty($matkhau)) {
        echo "<script>alert('Vui lòng nhập đầy đủ tài khoản và mật khẩu!');</script>";
    } else {
        if ($stmt = $conn->prepare("SELECT iduser, taikhoan, matkhau, role FROM user WHERE taikhoan = ?")) {
            $stmt->bind_param("s", $taikhoan);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                
                if (password_verify($matkhau, $row["matkhau"])) {
                    $_SESSION["user"] = [
                        "iduser" => $row["iduser"],
                        "taikhoan" => $row["taikhoan"],
                        "role" => $row["role"]
                    ];
                    
                    if ($row["role"] == "admin") {
                        header("Location: admin_dashboard.php");
                    } else {
                        header("Location: index.php");
                    }
                    exit();
                } else {
                    echo "<script>alert('Sai mật khẩu!');</script>";
                }
            } else {
                echo "<script>alert('Tài khoản không tồn tại!');</script>";
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fce4ec; /* Nền hồng nhạt */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            display: flex;
            justify-content: space-between;
            width: 80%;
            max-width: 1000px;
        }
        .left {
            flex: 1;
            padding-right: 50px;
        }
        .left h1 {
            color: #d81b60;
            font-size: 40px;
            font-weight: bold;
        }
        .left p {
            font-size: 18px;
            color: #333;
        }
        .login-box {
            flex: 1;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .login-box input {
            width: 90%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        .login-box button {
            width: 100%;
            background-color: #d81b60;
            color: white;
            font-size: 18px;
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
        .login-box button:hover {
            background-color: #ad1457;
        }
        .forgot {
            display: block;
            margin: 15px 0;
            color: #d81b60;
            font-size: 14px;
            text-decoration: none;
        }
        .register-btn {
            background-color: #42b72a;
            margin-top: 15px;
        }
        .register-btn:hover {
            background-color: #36a420;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left">
            <h1>Cửa hàng đồ ăn vặt EM THƯ</h1>
            <p>"Ngon khó cưỡng – Ăn đi chờ chi!" 🍟🍫</p>
        </div>
        <div class="login-box">
            <form method="post">
                <input type="text" name="taikhoan" placeholder="Tài khoản" required>
                <input type="password" name="matkhau" placeholder="Mật khẩu" required>
                <button type="submit">Đăng nhập</button>
                <a href="#" class="forgot">Quên mật khẩu?</a>
                <button class="register-btn">Tạo tài khoản mới</button>
            </form>
        </div>
    </div>
</body>
</html>
