<?php
// Kiểm tra nếu có tham số 'error' trong URL và có giá trị là 1
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $message = "Cập nhật thành công";
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
    <style>
    .navbar {
        font-family: Arial, sans-serif;
        font-size: 14px;
        /* Thay đổi kiểu chữ và kích thước */
    }

    .navbar-brand,
    .navbar-nav .nav-link {
        color: #fff;
        /* Màu chữ */
    }

    .navbar-brand:hover,
    .navbar-nav .nav-link:hover {
        color: #ffd700;
        /* Màu chữ khi hover */
    }
    </style>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
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

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Shop</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="/shop/index.php">Trang chủ</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/shop/controllers/OrderController.php?action=viewHistory">History</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/shop/views/user/order.php">Order</a>
                </li>
            </ul>
            <form class="form-inline my-2 my-lg-0" method="POST"
                action="/shop/controllers/ProductController.php?action=searchProducts">
                <input class="form-control mr-sm-2" type="search" placeholder="Tìm kiếm" aria-label="Search"
                    name="search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Tìm kiếm</button>
            </form>
            <ul class="navbar-nav ml-auto">
                <?php if (isset($_SESSION['user'])) : ?>
                <li class="nav-item">
                    <a class="nav-link"
                        href="/shop/views/account.php"><?php echo "Xin chào, " . $_SESSION['user']['Name']; ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/shop/controllers/UserController.php?action=logout">Đăng xuất</a>
                </li>
                <?php else : ?>
                <li class="nav-item">
                    <a class="nav-link" href="/shop/views/login.php">Đăng nhập</a>
                </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a class="nav-link" href="/shop/views/user/cart.php">Giỏ hàng</a>
                </li>
            </ul>
        </div>
    </nav>
    <?php if (!empty($message)) : ?>
    <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>
    <h1 class="mb-4">Thông tin người dùng</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Tên: <?php echo $user['Name']; ?></h5>
            <p class="card-text">Username: <?php echo $user['Username']; ?></p>
            <p class="card-text">
                Password:
                <span class="password-container">
                    <span id="password" class="password"><?php echo str_repeat('*', strlen($user['Pass'])); ?></span>
                    <span class="password-toggle" onclick="togglePasswordVisibility()">
                        <img src="/shop/public/icon/eye-icon.png" alt="Show Password" width="16" height="16">
                    </span>
                </span>
            </p>
            <p class="card-text">Địa chỉ: <?php echo $user['Address']; ?></p>
            <p class="card-text">Email: <?php echo $user['Email']; ?></p>
            <p class="card-text">Số điện thoại: <?php echo $user['Phone']; ?></p>
            <a href="/shop/views/update_account.php" class="btn btn-primary">Cập nhật tài khoản</a>
        </div>
    </div>

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