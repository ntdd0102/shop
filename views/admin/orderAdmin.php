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

// Lấy giá trị sort từ URL nếu có
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'all';

// Sắp xếp danh sách đơn hàng theo trạng thái (nếu đã chọn trạng thái)
if ($sort != 'all') {
    $sortedOrders = array_filter($orders, function ($order) use ($sort) {
        return $order['Status'] == $sort;
    });
} else {
    $sortedOrders = $orders;
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
                <h2>Quản lý đơn hàng</h2>
                <?php if (isset($_SESSION['activityAdmin'])) : ?>
                <div id="resultMessage" class="alert alert-success" role="alert">
                    <?php echo $_SESSION['activityAdmin']; ?>
                </div>
                <script>
                setTimeout(function() {
                    document.getElementById("resultMessage").style.display = "none";
                }, 3000);
                </script>
                <?php unset($_SESSION['activityAdmin']); ?>
                <?php endif; ?>

                <!-- Hiển thị danh sách đơn hàng -->
                <?php if (empty($orders)) : ?>
                <p>No orders found.</p>
                <?php else : ?>
                <div class="form-group">
                    <label for="orderStatus">Lọc đơn hàng:</label>
                    <select class="form-control" id="orderStatus" onchange="sortOrders()">
                        <option value="all" <?php if ($sort == 'all') echo 'selected'; ?>>All</option>
                        <option value="1" <?php if ($sort == '1') echo 'selected'; ?>>Chưa xác nhận</option>
                        <option value="2" <?php if ($sort == '2') echo 'selected'; ?>>Đã xác nhận</option>
                        <option value="3" <?php if ($sort == '3') echo 'selected'; ?>>Đang chuẩn bị hàng</option>
                        <option value="4" <?php if ($sort == '4') echo 'selected'; ?>>Đang giao hàng</option>
                        <option value="5" <?php if ($sort == '5') echo 'selected'; ?>>Hoàn thành</option>
                        <option value="6" <?php if ($sort == '6') echo 'selected'; ?>>Hủy</option>
                    </select>
                </div>
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
                            <?php foreach ($sortedOrders as $order) : ?>
                            <tr>
                                <td><?php echo $order['Id']; ?></td>
                                <td><?php echo $order['Name']; ?></td>
                                <td><?php echo $order['Phone']; ?></td>
                                <td><?php echo $order['Email']; ?></td>
                                <td><?php echo $order['Customer_name']; ?></td>
                                <td><?php echo $order['Delivery_address']; ?></td>
                                <td><?php echo date('d-m-Y H:i:s', strtotime($order['Date_created'])); ?></td>
                                <td><?php echo date('d-m-Y H:i:s', strtotime($order['Date_updated'])); ?></td>
                                <td><?php echo number_format($order['Total_price'], 0, ',', '.'); ?></td>
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
    <script>
    function sortOrders() {
        var sortValue = document.getElementById("orderStatus").value;
        window.location.href = "/shop/views/admin/orderAdmin.php?sort=" + sortValue;
    }
    </script>

</body>

</html>