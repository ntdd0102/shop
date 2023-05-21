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

    <?php if (isset($_SESSION['onl']) && $_SESSION['onl'] == 1) { ?>
        <form method="POST" action="/shop/controllers/PaymentController.php?action=infor">
        <?php } else { ?>
            <form method="POST" action="/shop/controllers/OrderSuccessController.php">
            <?php } ?>

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
</body>

</html>