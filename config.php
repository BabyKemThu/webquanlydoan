<?php
session_start();
$servername = "database-1.c3gcga4mwg5b.ap-southeast-2.rds.amazonaws.com";
$username = "admin";
$password = "12345678";
$dbname = "thuthu";

// Kết nối database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>