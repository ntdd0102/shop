<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Thông tin nhận hàng</title>
</head>

<body>
    <h1>Thông tin nhận hàng</h1>


    <form method="POST" action="/shop/controllers/PaymentController.php?action=infor">



        <label for="name">Họ và tên:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="phone">Số điện thoại:</label>
        <input type="tel" id="phone" name="phone" required><br><br>

        <label for="address">Địa chỉ nhận hàng:</label>
        <textarea id="address" name="address" required></textarea><br><br>

        <input type="submit" value="Xác nhận">
    </form>

    <?php
    var_dump($_SESSION['onl']);
    ?>
    <p>Bạn đã có tài khoản để nhận hàng? Nếu có hãy <a href="/shop/views/login.php">Login</a></p>
</body>

</html>