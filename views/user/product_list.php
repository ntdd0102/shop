<?php
require_once "../../models/connection.php";
require_once "../../models/CategoryModel.php";
require_once "../../models/ProductModel.php";
require_once "../../models/SupplierModel.php";

// Get category ID from query string
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;

$selected_supplier = isset($_GET['supplier']) ? $_GET['supplier'] : '';


// Load products by category ID
$productModel = new ProductModel();
$products = $productModel->getProductByCategoryIdAndSupplier($category_id, $selected_supplier);

// Sort products
$sort = isset($_GET['sort']) ? $_GET['sort'] : ''; // Lấy giá trị "sort" từ URL
if ($sort === 'asc') {
    usort($products, function ($a, $b) {
        return $a['Price'] <=> $b['Price']; // Sắp xếp giá tăng dần
    });
} elseif ($sort === 'desc') {
    usort($products, function ($a, $b) {
        return $b['Price'] <=> $a['Price']; // Sắp xếp giá giảm dần
    });
}

$supplierModel = new SupplierModel();
$suppliers = $supplierModel->getAll();

// Pagination
$items_per_page = 4; // Số lượng sản phẩm hiển thị trên mỗi trang
$total_items = count($products);
$total_pages = ceil($total_items / $items_per_page); // Tổng số trang

$current_page = isset($_GET['page']) ? $_GET['page'] : 1; // Trang hiện tại
$current_page = max(1, min($current_page, $total_pages)); // Đảm bảo giá trị trang hợp lệ

$offset = ($current_page - 1) * $items_per_page; // Offset để lấy dữ liệu sản phẩm cho trang hiện tại
$paginated_products = array_slice($products, $offset, $items_per_page);

// Load category name by ID
$categoryModel = new CategoryModel();
$category_name = $categoryModel->getCategoryNameById($category_id);
?>


<!DOCTYPE html>
<html>

<head>
    <title>All Products by Category: <?php echo $category_name; ?></title>
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

        .product-card {
            height: 100%;
        }

        .product-card .card-img-top {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .product-card .card-body {
            padding: 10px;
        }

        .product-card .card-title {
            font-size: 16px;
            margin-bottom: 5px;
        }

        .product-card .card-text {
            font-size: 14px;
            color: #555;
        }

        .sort-section {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
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
        <h1 class="my-4"><?php echo $category_name; ?></h1>
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="sort-section">
                    <p>Sắp xếp theo:</p>
                    <a href="?category_id=<?php echo $category_id; ?>&sort=asc" class="btn btn-primary">Giá (Cao đến
                        thấp)</a>
                    <a href="?category_id=<?php echo $category_id; ?>&sort=desc" class="btn btn-primary">Giá (Thấp đến
                        cao)</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="filter-section">
                    <label for="supplier">Lọc theo nhà sản xuất:</label>
                    <select id="supplier" name="supplier" class="form-select">
                        <option value="">Tất cả</option>
                        <?php foreach ($suppliers as $supplier) : ?>
                            <option value="<?php echo $supplier['Id']; ?>">
                                <?php echo $supplier['Name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button id="filter-btn" type="button" class="btn btn-primary">Lọc</button>
                </div>
            </div>
        </div>
        <div class="row">
            <?php foreach ($paginated_products as $product) : ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card h-100 product-card">
                        <a href="/shop/views/user/product_detail.php?id=<?php echo $product['Id']; ?>">
                            <img class="card-img-top" src="<?php echo $product['Image']; ?>" alt="<?php echo $product['Name']; ?>">
                        </a>
                        <div class="card-body">
                            <h4 class="card-title">
                                <a href="/shop/views/user/product_detail.php?id=<?php echo $product['Id']; ?>">
                                    <?php echo $product['Name']; ?>
                                </a>
                            </h4>
                            <p class="card-text">Price: <?php echo number_format($product['Price'], 0, ",", "."); ?> vnđ</p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="text-center">
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <?php if ($total_pages > 1) : ?>
                    <?php if ($current_page > 1) : ?>
                        <li class="page-item">
                            <a class="page-link" href="?category_id=<?php echo $category_id; ?>&sort=<?php echo $sort; ?>&page=<?php echo ($current_page - 1); ?>">Previous</a>
                        </li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                        <li class="page-item <?php echo ($i == $current_page) ? 'active' : ''; ?>">
                            <a class="page-link" href="?category_id=<?php echo $category_id; ?>&sort=<?php echo $sort; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <?php if ($current_page < $total_pages) : ?>
                        <li class="page-item">
                            <a class="page-link" href="?category_id=<?php echo $category_id; ?>&sort=<?php echo $sort; ?>&page=<?php echo ($current_page + 1); ?>">Next</a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
    </div>
    <script>
        // Lắng nghe sự kiện khi nhấn nút lọc
        document.getElementById('filter-btn').addEventListener('click', function() {
            var selectedSupplier = document.getElementById('supplier').value; // Lấy giá trị nhà sản xuất được chọn
            var currentUrl = window.location.href; // Lấy URL hiện tại
            var newUrl = updateQueryStringParameter(currentUrl, 'supplier',
                selectedSupplier); // Cập nhật giá trị nhà sản xuất vào URL
            window.location.href = newUrl; // Chuyển hướng đến URL mới với giá trị nhà sản xuất đã lọc
        });

        // Hàm cập nhật giá trị tham số trong URL
        function updateQueryStringParameter(uri, key, value) {
            var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
            var separator = uri.indexOf('?') !== -1 ? "&" : "?";
            if (uri.match(re)) {
                return uri.replace(re, '$1' + key + "=" + value + '$2');
            } else {
                return uri + separator + key + "=" + value;
            }
        }
    </script>


    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>