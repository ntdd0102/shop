<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['Role'] != 2) {
    header('Location: http://localhost/shop/index.php');
    exit();
}

if (isset($_SESSION['products'])) {
    $products = $_SESSION['products'];
} else {
    $products = array();
}

$productsPerPage = 5;
$totalPages = ceil(count($products) / $productsPerPage);
$currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;
$startIndex = ($currentPage - 1) * $productsPerPage;
$endIndex = $startIndex + $productsPerPage - 1;
$startIndex = min($startIndex, count($products) - 1);
$endIndex = min($endIndex, count($products) - 1);
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
    body {
        font-family: Arial, sans-serif;
        background-color: #f8f9fa;
    }

    .admin-sidebar {
        background-color: #343a40;
        color: #fff;
        min-height: 100vh;
        padding-top: 20px;
    }

    .admin-sidebar h3 {
        font-size: 24px;
        text-align: center;
        margin-bottom: 40px;
    }

    .admin-sidebar .nav-link {
        color: #fff;
        padding: 10px;
    }

    .admin-sidebar .nav-link:hover {
        background-color: #555;
    }

    .admin-content {
        margin-top: 20px;
    }

    .product-table td {
        vertical-align: middle;
    }

    .product-image {
        max-height: 100px;
    }

    .pagination {
        margin-top: 20px;
    }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 admin-sidebar">
                <h3>Admin Panel</h3>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="/shop/views/admin/hello.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                            href="/shop/controllers/ProductController.php?action=adminGetProduct">Products</a>
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

                <?php if (empty($displayProducts)) : ?>
                <p>No products found.</p>
                <?php else : ?>
                <table class="table product-table">
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
                            <td><img src="<?php echo $product['Image']; ?>" alt="<?php echo $product['Name']; ?>"
                                    class="product-image img-thumbnail"></td>
                            <td style="max-width: 300px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"
                                title="<?php echo $product['Description']; ?>"><?php echo $product['Description']; ?>
                            </td>
                            <td><?php echo $product['Price']; ?></td>
                            <td>
                                <?php if ($product['quantity'] > 0) {
                                            echo $product['quantity'];
                                        } else {
                                            echo "Out of stock";
                                        } ?>
                            </td>
                            <td>
                                <?php if ($product['is_visible'] == 1) {
                                            echo "Visible";
                                        } else {
                                            echo "Hidden";
                                        } ?>
                            </td>
                            <td>
                                <div class="d-flex">
                                    <a href="/shop/controllers/ProductController.php?action=adminEditProduct&id=<?php echo $product['Id']; ?>"
                                        class="btn btn-primary mr-2">Edit</a>
                                    <a href="/shop/controllers/ProductController.php?action=adminDelProduct&id=<?php echo $product['Id']; ?>"
                                        class="btn btn-danger">Delete</a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <nav aria-label="Product Pagination">
                    <ul class="pagination">
                        <?php if ($currentPage > 1) : ?>
                        <li class="page-item">
                            <a class="page-link"
                                href="/shop/views/admin/productAdmin.php?page=<?php echo $currentPage - 1; ?>">Previous</a>
                        </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                        <li class="page-item <?php echo ($i == $currentPage) ? 'active' : ''; ?>">
                            <a class="page-link"
                                href="/shop/views/admin/productAdmin.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                        <?php endfor; ?>

                        <?php if ($currentPage < $totalPages) : ?>
                        <li class="page-item">
                            <a class="page-link"
                                href="/shop/views/admin/productAdmin.php?page=<?php echo $currentPage + 1; ?>">Next</a>
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