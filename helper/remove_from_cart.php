<?php
session_start();
$productId = $_GET['id'];

// Xóa sản phẩm khỏi giỏ hàng
unset($_SESSION['cart'][$productId]);

// Chuyển hướng trở lại trang giỏ hàng
header("Location: /shop/views/user/cart.php");
exit();