<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['Role'] != 2) {
    header('Location: http://localhost/shop/index.php');
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
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
        }

        .admin-content h2 {
            font-size: 28px;
            margin-bottom: 20px;
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
                        <a class="nav-link" href="/shop/controllers/ProductController.php?action=adminGetProduct">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/shop/controllers/OrderController.php?action=adminGetOrder">Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/shop/controllers/OrderController.php?action=revenue">Revenue</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/shop/controllers/CategoryController.php?action=adminGetCategory">Category</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/shop/controllers/SupplierController.php?action=adminGetSupplier">Supplier</a>
                    </li>
                </ul>
            </div>
            <div class="col-md-10 admin-content">
                <h2>Dashboard</h2>
                <?php
                if (isset($_SESSION['new_orders_count'])) {
                    $new_orders_count = $_SESSION['new_orders_count'];
                    echo '<div class="alert alert-info">';
                    echo 'Bạn có <span class="badge badge-primary">' . $new_orders_count . '</span> đơn hàng mới.';
                    echo '</div>';
                    unset($_SESSION['new_orders_count']);
                }
                ?>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>