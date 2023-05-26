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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        .register-heading {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            color: #333;
            /* Các thuộc tính CSS khác để tạo kiểu đẹp cho tiêu đề */
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
</head>

<body>
    <?php if (!empty($message)) : ?>
        <p><?php echo $message; ?></p> <!-- Hiển thị thông báo lỗi khi có giá trị -->
    <?php endif; ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Shop</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
            <form class="form-inline my-2 my-lg-0" method="POST" action="/shop/controllers/ProductController.php?action=searchProducts">
                <input class="form-control mr-sm-2" type="search" placeholder="Tìm kiếm" aria-label="Search" name="search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Tìm kiếm</button>
            </form>
            <ul class="navbar-nav ml-auto">
                <?php if (isset($_SESSION['user'])) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/shop/views/account.php"><?php echo "Xin chào, " . $_SESSION['user']['Name']; ?></a>
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
    <div class="container">
        <div class="register-heading">
            <span>Đăng ký</span>
        </div>

        <form method="POST" action="/shop/controllers/UserController.php?action=register" id="register-form">
            <div class="form-group">
                <label for="username">Tên người dùng:</label>
                <input type="text" class="form-control" name="username" id="username" required>
                <span id="username-error"></span>
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu:</label>
                <input type="password" class="form-control" name="password" id="password" required>
                <span id="password-error"></span>
            </div>

            <div class="form-group">
                <label for="name">Họ và tên:</label>
                <input type="text" class="form-control" name="name" id="name" required>
                <span id="name-error"></span>
            </div>

            <div class="form-group">
                <label for="address">Địa chỉ:</label>
                <input type="text" class="form-control" name="address" id="address" required>
                <span id="address-error"></span>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" name="email" id="email" required>
                <span id="email-error"></span>
            </div>

            <div class="form-group">
                <label for="phone">Số điện thoại:</label>
                <input type="text" class="form-control" name="phone" id="phone" required>
                <span id="phone-error"></span>
            </div>

            <button type="submit" class="btn btn-primary">Đăng ký</button>
            <script src="/shop/public/js/check_username.js"></script>
        </form>
    </div>

</body>

</html>