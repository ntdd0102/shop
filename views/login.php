<?php
// Kiểm tra nếu có tham số 'error' trong URL và có giá trị là 1
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $message = "Đăng ký thành công";
} else {
    $message = ""; // Đặt giá trị mặc định là rỗng nếu không có lỗi
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        .register-button {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 12px 24px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <?php if (!empty($message)) : ?>
        <p><?php echo $message; ?></p> <!-- Hiển thị thông báo lỗi khi có giá trị -->
    <?php endif; ?>
    <h1>Login</h1>
    <form method="POST" action="/shop/controllers/UserController.php?action=login">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username"><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password"><br>

        <input type="submit" value="Đăng nhập">
    </form>
    <div class="register-button">
        <a href="/shop/views/user/register.php">Đăng ký</a>
    </div>
    <?php
    session_start();
    if (isset($_SESSION['login_message'])) {
        echo $_SESSION['login_message'];
        unset($_SESSION['login_message']);
    }
    ?>
</body>

</html>