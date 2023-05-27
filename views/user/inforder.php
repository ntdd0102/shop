<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Thông tin nhận hàng</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
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
    <h1>Thông tin nhận hàng</h1>


    <form method="POST" action="/shop/controllers/PaymentController.php?action=infor">
        <div class="form-group">
            <label for="name">Họ và tên:</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <div class="form-group">
            <label for="phone">Số điện thoại:</label>
            <input type="tel" class="form-control" id="phone" name="phone" required>
        </div>

        <div class="form-group">
            <label for="address">Địa chỉ nhận hàng:</label>
            <textarea class="form-control" id="address" name="address" required></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Xác nhận</button>
    </form>



    <p>Bạn đã có tài khoản để nhận hàng? Nếu có hãy <a href="/shop/views/login.php">Login</a></p>
</body>

</html>