<?php
require_once "../../models/ProductModel.php";
session_start();
// Lấy mảng sản phẩm đã lưu trong session, với key là id sản phẩm, value là số lượng sản phẩm
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
// Nếu giỏ hàng không có sản phẩm nào thì hiển thị thông báo
if (empty($cart)) {
    $cartMessage = "Your cart is empty.";
} else {
    $productModel = new ProductModel();
    $totalPrice = 0;
    $products = array();

    // Lấy thông tin chi tiết sản phẩm từ database và tính tổng giá tiền
    foreach ($cart as $productId => $quantity) {
        $product = $productModel->getProductById($productId);
        $product['Quantity'] = $quantity;
        $totalPrice += $product['Price'] * $quantity;
        array_push($products, $product);
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css"
        integrity="sha512-y/4rIGt5s4ixEFp1C+3GyLUGQrMvEDJx4wz8+j0N/gkwBzV4i+dP1p7Hn/cw7jGOx33KzqplI4bxfRxyFHYsQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
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
    <div class="container mt-3">
        <h1 class="mb-3">Your Cart</h1>

        <?php if (isset($cartMessage)) { ?>
        <p><?php echo $cartMessage; ?></p>
        <?php } else { ?>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Image</th>
                    <th scope="col">Name</th>

                    <th scope="col">Increase</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Decrease</th>
                    <th scope="col">Price</th>
                    <th scope="col">Total Price</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product) { ?>
                <tr>
                    <td>
                        <img src="<?php echo $product['Image']; ?>" alt="<?php echo $product['Name']; ?>"
                            class="img-thumbnail" style="max-height: 100px;">
                    </td>
                    <td><?php echo $product['Name']; ?></td>

                    <td>
                        <form method="POST"
                            action="/shop/controllers/CartController.php?action=quantity_change&productId=<?php echo $product['Id']; ?>&type=plus">
                            <button type="submit" class="quantity-input">+</button>
                        </form>
                    </td>
                    <td><?php echo $product['Quantity']; ?></td>
                    <td>
                        <form method="POST"
                            action="/shop/controllers/CartController.php?action=quantity_change&productId=<?php echo $product['Id']; ?>&type=minus">
                            <button type="submit" class="quantity-input">-</button>
                        </form>
                    </td>
                    <td class="price" data-price="<?php echo $product['Price']; ?>">
                        <?php echo number_format($product['Price'], 0, ",", "."); ?> vnđ
                    </td>
                    <td id="totalPrice" class="total-price">
                        <?php echo number_format($product['Price'] * $product['Quantity'], 0, ",", "."); ?> vnđ
                    </td>
                    <td>
                        <a href="/shop/helper/remove_from_cart.php?id=<?php echo $product['Id']; ?>"
                            class="btn btn-danger">Remove</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <div class="row">
            <div class="col-md-6">
                <p class="h5" data-price="<?php echo $totalPrice; ?>">Total Price:
                    <?php echo number_format($totalPrice, 0, ",", "."); ?> vnđ</p>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <a href="/shop/views/user/order.php" class="btn btn-primary">Proceed to Checkout</a>
                    </div>
                    <div class="col-md-6 text-end">
                        <a href="/shop/index.php" class="btn btn-primary">Tiếp tục mua sắm</a>
                    </div>

                </div>
            </div>
        </div>
        <?php } ?>
    </div>




    <script src="/shop/public/js/cart.js"></script>
</body>

</html>