<?php
// Kiểm tra nếu có tham số 'error' trong URL và có giá trị là 1
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $message = "Đăng ký thành công";
} else {
    $message = ""; // Đặt giá trị mặc định là rỗng nếu không có lỗi
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Thông tin người dùng</title>
    <style>
        .password-container {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            top: 8px;
            right: 10px;
            cursor: pointer;
        }
    </style>
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
     <?php if (!empty($message)) : ?>
        <p><?php echo $message; ?></p> <!-- Hiển thị thông báo lỗi khi có giá trị -->
    <?php endif; ?>

    <h1>Thông tin người dùng</h1>

    <p><strong>Tên:</strong> <?php echo $user['Name']; ?></p>
    <p><strong>Username:</strong> <?php echo $user['Username']; ?></p>
    <p><strong>Password:</strong>
        <span class="password-container">
            <span id="password" class="password"><?php echo str_repeat('*', strlen($user['Pass'])); ?></span>
            <span class="password-toggle" onclick="togglePasswordVisibility()">
                <img src="/shop/public/icon/eye-icon.png" alt="Show Password" width="16" height="16">
            </span>
        </span>
    </p>
    <p><strong>Địa chỉ:</strong> <?php echo $user['Address']; ?></p>
    <p><strong>Email:</strong> <?php echo $user['Email']; ?></p>
    <p><strong>Số điện thoại:</strong> <?php echo $user['Phone']; ?></p>
    <p><a href="/shop/views/update_account.php">Cập nhật tài khoản</a></p>
    <script>
        function togglePasswordVisibility() {
            var password = document.getElementById('password');
            if (password.classList.contains('password')) {
                password.textContent = '<?php echo $user['Pass']; ?>';
                password.classList.remove('password');
            } else {
                password.textContent = '<?php echo str_repeat('*', strlen($user['Pass'])); ?>';
                password.classList.add('password');
            }
        }
    </script>
</body>
</html>
