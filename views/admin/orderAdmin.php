<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['Role'] != 2) {
    header('Location: http://localhost/shop/index.php');
    exit();
}

if (isset($_SESSION['order'])) {
    $orders = $_SESSION['order'];
} else {
    $orders = array(); // Nếu không có đơn hàng, khởi tạo một mảng rỗng
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
    }

    .table-responsive {
        margin-top: 20px;
    }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <nav class="navbar navbar-expand-md navbar-dark bg-dark col-md-12">
                <a class="navbar-brand" href="#">Admin Panel</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link active" href="/shop/views/admin/hello.php">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                                href="/shop/controllers/ProductController.php?action=adminGetProduct">Products</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                                href="/shop/controllers/OrderController.php?action=adminGetOrder">Orders</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/shop/controllers/OrderController.php?action=revenue">Revenue</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                                href="/shop/controllers/CategoryController.php?action=adminGetCategory">Category</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                                href="/shop/controllers/SupplierController.php?action=adminGetSupplier">Supplier</a>
                        </li>
                    </ul>
                </div>
            </nav>

            <div class="col-md-12 admin-content">
                <h2>Welcome to Admin Panel</h2>

                <!-- Hiển thị danh sách đơn hàng -->
                <?php if (empty($orders)) : ?>
                <p>No orders found.</p>
                <?php else : ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Customer Name</th>
                                <th>Delivery Address</th>
                                <th>Date Created</th>
                                <th>Date Updated</th>
                                <th>Total Price</th>
                                <th>Is Pay Online</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order) : ?>
                            <tr>
                                <td><?php echo $order['Id']; ?></td>
                                <td><?php echo $order['Name']; ?></td>
                                <td><?php echo $order['Phone']; ?></td>
                                <td><?php echo $order['Email']; ?></td>
                                <td><?php echo $order['Customer_name']; ?></td>
                                <td><?php echo $order['Delivery_address']; ?></td>
                                <td><?php echo $order['Date_created']; ?></td>
                                <td><?php echo $order['Date_updated']; ?></td>
                                <td><?php echo $order['Total_price']; ?></td>
                                <td><?php echo $order['Is_pay_onl'] == 1 ? 'Yes' : 'No'; ?></td>
                                <td>
                                    <?php
                                            $status = $order['Status'];
                                            if ($status == 1) {
                                                echo 'Chưa xác nhận';
                                            } elseif ($status == 2) {
                                                echo 'Đã xác nhận';
                                            } elseif ($status == 3) {
                                                echo 'Đang chuẩn bị hàng';
                                            } elseif ($status == 4) {
                                                echo 'Đang giao hàng';
                                            } elseif ($status == 5) {
                                                echo 'Hoàn thành';
                                            } elseif ($status == 6) {
                                                echo 'Hủy';
                                            } else {
                                                echo 'Trạng thái không hợp lệ';
                                            }
                                            ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group" aria-label="Order Actions">
                                        <a href="/shop/controllers/OrderController.php?action=editOrder&id=<?php echo $order['Id']; ?>"
                                            class="btn btn-primary btn-sm">Edit</a>
                                        <a href="/shop/controllers/OrderController.php?action=cancelOrder&id=<?php echo $order['Id']; ?>"
                                            class="btn btn-danger btn-sm">Cancel</a>
                                        <a href="/shop/controllers/OrderController.php?action=viewOrderDetail&id=<?php echo $order['Id']; ?>"
                                            class="btn btn-info btn-sm">View Details</a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>