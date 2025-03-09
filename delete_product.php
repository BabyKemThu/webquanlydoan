<?php
session_start();

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION["user"])) {
    die("Bạn cần đăng nhập để thực hiện thao tác này.");
}

$servername = "database-1.c3gcga4mwg5b.ap-southeast-2.rds.amazonaws.com";
$username = "admin";
$password = "12345678";
$dbname = "thuthu";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    
    if ($id > 0) {
        $sql = "DELETE FROM products WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            echo "<script>alert('Xóa sản phẩm thành công!'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Lỗi khi xóa sản phẩm.'); window.location.href='index.php';</script>";
        }
        
        $stmt->close();
    } else {
        echo "<script>alert('ID không hợp lệ.'); window.location.href='index.php';</script>";
    }
}

$conn->close();
?>