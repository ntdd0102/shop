<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['Role'] != 2) {
    header('Location: http://localhost/shop/index.php');
    exit();
}

if (isset($_SESSION['products'])) {
    $products = $_SESSION['products'];
} else {
    $products = array(); // Nếu không có sản phẩm, khởi tạo một mảng rỗng
}

// Số lượng sản phẩm trên mỗi trang
$productsPerPage = 5;

// Tổng số trang
$totalPages = ceil(count($products) / $productsPerPage);

// Trang hiện tại (mặc định là trang đầu tiên)
$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;

// Xác định vị trí bắt đầu và kết thúc của mảng sản phẩm để hiển thị trên trang hiện tại
$startIndex = ($currentPage - 1) * $productsPerPage;
$endIndex = $startIndex + $productsPerPage - 1;

// Giới hạn chỉ số để đảm bảo không vượt quá kích thước của mảng
$startIndex = min($startIndex, count($products) - 1);
$endIndex = min($endIndex, count($products) - 1);

// Mảng sản phẩm để hiển thị trên trang hiện tại
$displayProducts = array_slice($products, $startIndex, $endIndex - $startIndex + 1);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        .admin-sidebar {
            background-color: #f8f9fa;
            height: 100vh;
            /* Đặt chiều cao cho thanh nav bằng chiều cao của viewport */
        }

        .admin-sidebar h3 {
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .admin-content {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 admin-sidebar">
                <h3>Admin Panel</h3>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="/shop/views/admin/hello.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/shop/controllers/ProductController.php?action=adminGetProduct">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/shop/controllers/OrderController.php?action=adminGetOrder">Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Settings</a>
                    </li>
                </ul>
            </div>

            <div class="col-md-9 admin-content">
                <h2>Welcome to Admin Panel</h2>
                <div class="mb-3">
                    <a href="/shop/controllers/ProductController.php?action=adminAddProduct" class="btn btn-primary">Add
                        Product</a>
                </div>
                <!-- Hiển thị danh sách sản phẩm -->
                <?php if (empty($displayProducts)) : ?>
                    <p>No products found.</p>
                <?php else : ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Image</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($displayProducts as $product) : ?>
                                <tr>
                                    <td><?php echo $product['Id']; ?></td>
                                    <td><?php echo $product['Name']; ?></td>
                                    <td>
                                        <img src="<?php echo $product['Image']; ?>" alt="<?php echo $product['Name']; ?>" class="img-thumbnail" style="max-height: 100px;">
                                    </td>
                                    <td style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="<?php echo $product['Description']; ?>">
                                        <?php echo $product['Description']; ?>
                                    </td>
                                    <td><?php echo $product['Price']; ?></td>
                                    <td>
                                        <?php if ($product['quantity'] > 0) {
                                            echo $product['quantity'];
                                        } else {
                                            echo "Hết hàng";
                                        } ?>
                                    </td>
                                    <td>
                                        <?php if ($product['is_visible'] == 1) {
                                            echo "Hiện";
                                        } else {
                                            echo "Ẩn";
                                        } ?>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <a href="/shop/controllers/ProductController.php?action=adminEditProduct&id=<?php echo $product['Id']; ?>" class="btn btn-primary mr-2">Edit</a>
                                            <a href="/shop/controllers/ProductController.php?action=adminDelProduct&id=<?php echo $product['Id']; ?>" class="btn btn-danger">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <!-- Hiển thị phân trang -->
                    <nav aria-label="Product Pagination">
                        <ul class="pagination">
                            <?php if ($currentPage > 1) : ?>
                                <li class="page-item">
                                    <a class="page-link" href="/shop/views/admin/productAdmin.php?page=<?php echo $currentPage - 1; ?>">Previous</a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                                <li class="page-item <?php echo ($i == $currentPage) ? 'active' : ''; ?>">
                                    <a class="page-link" href="/shop/views/admin/productAdmin.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($currentPage < $totalPages) : ?>
                                <li class="page-item">
                                    <a class="page-link" href="/shop/views/admin/productAdmin.php?page=<?php echo $currentPage + 1; ?>">Next</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>

                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>