<!-- <?php
        require_once "../../models/ProductModel.php";

        if (isset($_GET['id'])) {
            $productId = $_GET['id'];
            $productModel = new ProductModel();
            $product = $productModel->getProductById($productId);
        }
        ?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Detail</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <div class="row">
            <div class="col-lg-4">
                <img src="<?php echo $product['Image']; ?>" alt="<?php echo $product['Name']; ?>" class="img-fluid">
            </div>
            <div class="col-lg-8">
                <h1 class="mb-4"><?php echo $product['Name']; ?></h1>
                <p class="lead"><?php echo $product['Description']; ?></p>
                <p class="font-weight-bold mb-2">Price: <?php echo number_format($product['Price'], 0, ",", "."); ?> vnđ</p>
                <a href="#" class="btn btn-primary">Add to Cart</a>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html> -->

<?php
require_once "../../models/ProductModel.php";
session_start();

if (isset($_GET['id'])) {
    $productId = $_GET['id'];
    $productModel = new ProductModel();
    $product = $productModel->getProductById($productId);
}
if (isset($_POST['add-to-cart'])) {
    // Kiểm tra nếu giỏ hàng đã được khởi tạo trong phiên đăng nhập
    if (isset($_SESSION['cart'])) {
        // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
        if (isset($_SESSION['cart'][$productId])) {
            // Nếu sản phẩm đã có trong giỏ hàng, cập nhật số lượng sản phẩm
            $_SESSION['cart'][$productId] += 1;
            $message = "Thêm sản phẩm vào giỏ hàng thành công!";
        } else {
            // Nếu sản phẩm chưa có trong giỏ hàng, thêm sản phẩm mới vào mảng giỏ hàng
            $_SESSION['cart'][$productId] = 1;
            $message = "Thêm sản phẩm vào giỏ hàng thành công!";
        }
    } else {
        // Nếu giỏ hàng chưa được khởi tạo trong phiên đăng nhập, tạo một mảng mới để lưu trữ sản phẩm
        $_SESSION['cart'] = array($productId => 1);
        $message = "Thêm sản phẩm vào giỏ hàng thành công!";
    }
}


?>
<!DOCTYPE html>
<html>

<head>
    <title>Product Detail</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>

<body>
    <div class="container my-5">
        <div class="row">
            <div class="col-lg-4">
                <img src="<?php echo $product['Image']; ?>" alt="<?php echo $product['Name']; ?>" class="img-fluid">
            </div>
            <div class="col-lg-8">
                <h1 class="mb-4"><?php echo $product['Name']; ?></h1>
                <p class="lead"><?php echo $product['Description']; ?></p>
                <p class="font-weight-bold mb-2">Price: <?php echo number_format($product['Price'], 0, ",", "."); ?> vnđ
                </p>
                <form method="post">
                    <input type="hidden" name="productId" value="<?php echo $product['Id']; ?>">
                    <button type="submit" class="btn btn-primary" name="add-to-cart">Add to Cart</button>
                </form>
                <a href="/shop/views/user/cart.php" class="btn btn-secondary ml-2">Cart</a>
                <?php if (isset($message)) : ?>
                <div class="alert alert-primary mt-3" id="alert-message"><?php echo $message; ?></div>
                <script>
                setTimeout(function() {
                    $('#alert-message').fadeOut('fast');
                }, 3000);
                </script>
                <?php endif; ?>

            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>