<!-- update_account.php -->
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
    <title>Cập nhật tài khoản</title>
</head>

<body>
    <?php
    session_start();

    // Kiểm tra xem người dùng đã đăng nhập hay chưa
    if (isset($_SESSION['user'])) {
        $user = $_SESSION['user'];
    } else {
        header('Location: /shop/views/login.php');
        exit();
    }
    ?>
    <h1>Cập nhật tài khoản</h1>
    <?php if (!empty($message)) : ?>
        <p><?php echo $message; ?></p> <!-- Hiển thị thông báo lỗi khi có giá trị -->
    <?php endif; ?>
    <form method="POST" action="/shop/controllers/UserController.php?action=update_account">
        <p>
            <label for="name">Tên:</label>
            <input type="text" id="name" name="name" value="<?php echo $user['Name']; ?>" required>
        </p>
        <p>
            <label for="address">Địa chỉ:</label>
            <input type="text" id="address" name="address" value="<?php echo $user['Address']; ?>" required>
        </p>
        <p>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $user['Email']; ?>" required>
        </p>
        <p>
            <label for="phone">Số điện thoại:</label>
            <input type="tel" id="phone" name="phone" value="<?php echo $user['Phone']; ?>" required>
        </p>
        <p>
            <button type="submit">Cập nhật</button>
        </p>
    </form>
</body>

</html>