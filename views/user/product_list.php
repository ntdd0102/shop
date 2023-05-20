<?php
require_once "../../models/connection.php";
require_once "../../models/CategoryModel.php";
require_once "../../models/ProductModel.php";

// Get category ID from query string
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;

// Load products by category ID
$productModel = new ProductModel();
$products = $productModel->getProductByCategoryId($category_id);

// Load category name by ID
$categoryModel = new CategoryModel();
$category_name = $categoryModel->getCategoryNameById($category_id);
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Products by Category: <?php echo $category_name; ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1 class="my-4">All Products by Category: <?php echo $category_name; ?></h1>
        <div class="row">
            <?php foreach ($products as $product): ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card h-100">
                        <a href="/shop/views/user/product_detail.php?id=<?php echo $product['Id']; ?>"><img class="card-img-top" src="<?php echo $product['Image']; ?>" alt="<?php echo $product['Name']; ?>"></a>
                        <div class="card-body">
                            <h4 class="card-title">
                                <a href="/shop/views/user/product_detail.php?id=<?php echo $product['Id']; ?>"><?php echo $product['Name']; ?></a>
                            </h4>
                            <p class="card-text">Price: <?php echo number_format($product['Price'], 0, ",", "."); ?> vnđ</p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
