<!-- update_account.php -->
<?php
// Kiểm tra nếu có tham số 'error' trong URL và có giá trị là 1
if (isset($_GET['error']) && $_GET['error'] == 1) {
    $message = "Cập nhật thất bại";
} else {
    $message = ""; // Đặt giá trị mặc định là rỗng nếu không có lỗi
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Cập nhật tài khoản</title>
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
    <h1 class="mb-4">Cập nhật tài khoản</h1>
    <?php if (!empty($message)) : ?>
    <p class="alert alert-danger"><?php echo $message; ?></p> <!-- Hiển thị thông báo lỗi khi có giá trị -->
    <?php endif; ?>
    <form class="mb-4" method="POST" action="/shop/controllers/UserController.php?action=update_account">
        <div class="form-group">
            <label for="name">Tên:</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo $user['Name']; ?>" required>
        </div>
        <div class="form-group">
            <label for="address">Địa chỉ:</label>
            <input type="text" class="form-control" id="address" name="address" value="<?php echo $user['Address']; ?>"
                required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['Email']; ?>"
                required>
        </div>
        <div class="form-group">
            <label for="phone">Số điện thoại:</label>
            <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo $user['Phone']; ?>"
                required>
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary">Cập nhật</button>
        </div>
    </form>


</html>