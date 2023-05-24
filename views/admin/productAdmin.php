<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['Role'] != 2) {
    header('Location: http://localhost/shop/index.php');
    exit();
}

if (isset($_SESSION['products'])) {
    $products = $_SESSION['products'];
    // Xử lý dữ liệu sản phẩm
    // ...
    //unset($_SESSION['products']); // Xóa dữ liệu trong session sau khi sử dụng
}
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
                        <a class="nav-link" href="#">Orders</a>
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

                <!-- Hiển thị danh sách sản phẩm -->
                <?php if (empty($products)) : ?>
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
                            <?php foreach ($products as $product) : ?>
                                <tr>
                                    <td><?php echo $product['Id']; ?></td>
                                    <td><?php echo $product['Name']; ?></td>
                                    <td>
                                        <img src="<?php echo $product['Image']; ?>" alt="<?php echo $product['Name']; ?>" class="img-thumbnail" style="max-height: 100px;">
                                    </td>
                                    <td style="max-width: 300px; overflow: hidden; text-overflow: ellipsis;">
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
                                        <!-- Các nút thực hiện thao tác với sản phẩm -->
                                        <a href="/shop/controllers/ProductController.php?action=adminEditProduct&id=<?php echo $product['Id']; ?>" class="btn btn-primary">Edit</a>
                                        <a href="/shop/controllers/ProductController.php?action=adminDelProduct&id=<?php echo $product['Id']; ?>" class="btn btn-danger">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>