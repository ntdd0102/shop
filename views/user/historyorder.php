<?php
// Lấy dữ liệu các order từ tham số URL
$orders = unserialize(urldecode($_GET['orders']));
?>
<!DOCTYPE html>
<html>

<head>
    <title>Order History</title>
</head>

<body>
    <h1>Lịch sử đơn hàng</h1>
    <form class="form-inline my-2 my-lg-0" method="POST" action="/shop/controllers/OrderController.php?action=searchByOrderName">
        <input class="form-control mr-sm-2" type="search" placeholder="Tìm kiếm" aria-label="Search" name="orderName">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Tìm kiếm</button>
    </form>
    <pre><?php var_dump($orders); ?></pre>

    <?php if (count($orders) > 0) : ?>
        <?php foreach ($orders as $order) : ?>
            <div>
                <p>Mã đơn hàng: <?php echo $order['Name']; ?></p>
                <p>Tên khách hàng: <?php echo $order['Customer_name']; ?></p>
                <p>Số điện thoại: <?php echo $order['Phone']; ?></p>
                <p>Tổng tiền: <?php echo $order['Total_price']; ?></p>
                <p>Trạng thái:
                    <?php
                    $status = $order['Status'];
                    if ($status == 1) {
                        echo "Chưa xác nhận";
                    } elseif ($status == 2) {
                        echo "Đã xác nhận";
                    } elseif ($status == 3) {
                        echo "Đang giao hàng";
                    } elseif ($status == 4) {
                        echo "Đã hoàn thành";
                    } else {
                        echo "Trạng thái không xác định";
                    }
                    ?>
                </p>
                <?php
                // Lấy các Order Detail dựa trên Order ID
                require_once "../../models/Order_DetailModel.php";
                $order_detail_model = new OrderDetailModel();
                $orderDetails = $order_detail_model->getOrderDetailsByOrderId($order['Id']);
                ?>
                <h3>Chi tiết đơn hàng:</h3>
                <?php if (count($orderDetails) > 0) : ?>
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
                <?php else : ?>
                    <p>Không có chi tiết đơn hàng.</p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <p>Không có đơn hàng.</p>
    <?php endif; ?>
</body>

</html>