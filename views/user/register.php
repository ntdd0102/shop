<?php
// Kiểm tra nếu có tham số 'error' trong URL và có giá trị là 1
if (isset($_GET['error']) && $_GET['error'] == 1) {
    $message = "Đăng ký thất bại";
} else {
    $message = ""; // Đặt giá trị mặc định là rỗng nếu không có lỗi
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Đăng ký</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

</head>

<body>
    <?php if (!empty($message)) : ?>
        <p><?php echo $message; ?></p> <!-- Hiển thị thông báo lỗi khi có giá trị -->
    <?php endif; ?>
    <h2>Đăng ký</h2>
    <form method="POST" action="/shop/controllers/UserController.php?action=register" id="register-form">
        <label for="username">Tên người dùng:</label>
        <input type="text" name="username" id="username" required><br><br>
        <span id="username-error"></span><br><br>

        <label for="password">Mật khẩu:</label>
        <input type="password" name="password" id="password" required><br><br>
        <span id="password-error"></span><br><br>

        <label for="name">Họ và tên:</label>
        <input type="text" name="name" id="name" required><br><br>
        <span id="name-error"></span><br><br>

        <label for="address">Địa chỉ:</label>
        <input type="text" name="address" id="address" required><br><br>
        <span id="address-error"></span><br><br>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required><br><br>
        <span id="email-error"></span><br><br>

        <label for="phone">Số điện thoại:</label>
        <input type="text" name="phone" id="phone" required><br><br>
        <span id="phone-error"></span><br><br>

        <input type="submit" value="Đăng ký">
        <script src="/shop/public/js/check_username.js"></script>
    </form>
</body>

</html>