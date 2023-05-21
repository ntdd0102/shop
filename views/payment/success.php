<?php
// Kiểm tra xem cookie tên đơn hàng đã tồn tại hay chưa
if (isset($_COOKIE['order_name'])) {
    $orderName = $_COOKIE['order_name'];
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Đặt hàng thành công</title>
</head>

<body>
    <h1>Đặt hàng thành công</h1>
    <p>Tên đơn hàng: <?php echo $orderName; ?></p>
    <p>Vui lòng lưu trữ lại tên đơn hàng của bạn để tiện việc tra cứu</p>
    <a href="/shop/controllers/OrderController.php?action=viewHistory">Xem lịch sử mua hàng</a>
</body>

</html>