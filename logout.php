<?php
session_start();
session_destroy(); // Xóa toàn bộ session
echo "<script>alert('Bạn đã đăng xuất!'); window.location='index.php';</script>";
?>