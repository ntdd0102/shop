<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Shop</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="/shop/index.php">Trang chủ</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <form class="form-inline my-2 my-lg-0" method="POST" action="/shop/controllers/ProductController.php?action=searchProducts">
                        <input class="form-control mr-sm-2" type="search" placeholder="Tìm kiếm" aria-label="Search" name="search">
                        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Tìm kiếm</button>
                    </form>
                </li>
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
    
