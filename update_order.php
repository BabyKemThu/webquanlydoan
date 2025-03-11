<?php
include "config.php";

// Kiểm tra quyền admin
if (!isset($_SESSION["user"]) || $_SESSION["user"]["role"] !== "admin") {
    echo "<script>alert('Bạn không có quyền truy cập!'); window.location='index.php';</script>";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'] ?? '';
    $status = $_POST['status'] ?? '';

    if (!empty($order_id) && !empty($status)) {
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $order_id);

        if ($stmt->execute()) {
            echo "<script>alert('Cập nhật trạng thái thành công!'); window.location='admin_dashboard.php';</script>";
        } else {
            echo "<script>alert('Lỗi khi cập nhật!'); window.location='admin_dashboard.php';</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Dữ liệu không hợp lệ!'); window.location='admin_dashboard.php';</script>";
    }
}
?>