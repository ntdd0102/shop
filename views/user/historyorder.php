<?php
// Lấy dữ liệu các order từ tham số URL
$orders = unserialize(urldecode($_GET['orders']));
?>
<!DOCTYPE html>
<html>

<head>
    <title>Order History</title>
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
    <h1>Lịch sử đơn hàng</h1>
    <form class="form-inline my-2 my-lg-0" method="POST" action="/shop/controllers/OrderController.php?action=searchByOrderName">
        <input class="form-control mr-sm-2" type="search" placeholder="Tìm kiếm" aria-label="Search" name="orderName">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Tìm kiếm</button>
    </form>

    <?php if (count($orders) > 0) : ?>
        <?php foreach ($orders as $order) : ?>
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Mã đơn hàng: <?php echo $order['Name']; ?></h5>
                    <p class="card-text">Tên khách hàng: <?php echo $order['Customer_name']; ?></p>
                    <p class="card-text">Số điện thoại: <?php echo $order['Phone']; ?></p>
                    <p class="card-text">Tổng tiền: <?php echo $order['Total_price']; ?></p>
                    <p class="card-text">Trạng thái:
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
                            echo 'Trạng thái không xác định';
                        }
                        ?>
                    </p>

                    <?php
                    // Lấy các Order Detail dựa trên Order ID
                    require_once "../../models/Order_DetailModel.php";
                    $order_detail_model = new OrderDetailModel();
                    $orderDetails = $order_detail_model->getOrderDetailsByOrderId($order['Id']);
                    ?>
                    <h5 class="card-title mt-4">Chi tiết đơn hàng:</h5>
                    <?php if (count($orderDetails) > 0) : ?>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Mã sản phẩm</th>
                                        <th>Số lượng</th>
                                        <th>Giá</th>
                                        <th>Ảnh sản phẩm</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orderDetails as $orderDetail) : ?>
                                        <tr>
                                            <td><?php echo $orderDetail['Product_id']; ?></td>
                                            <td><?php echo $orderDetail['Quantity']; ?></td>
                                            <td><?php echo $orderDetail['Price_product']; ?></td>
                                            <?php
                                            // Lấy thông tin sản phẩm từ ID sản phẩm (ProductID)
                                            require_once "../../models/ProductModel.php";
                                            $productModel = new ProductModel();
                                            $product = $productModel->getProductById($orderDetail['Product_id']);
                                            ?>
                                            <td>
                                                <?php if ($product) : ?>
                                                    <img src="<?php echo $product['Image']; ?>" alt="Hình ảnh sản phẩm" class="img-thumbnail" style="max-height: 100px;">
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else : ?>
                        <p class="mt-2">Không có chi tiết đơn hàng.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <p>Không có đơn hàng.</p>
    <?php endif; ?>

</body>

</html>