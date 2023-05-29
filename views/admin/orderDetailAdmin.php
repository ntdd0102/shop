<?php
session_start();
require_once "../../models/ProductModel.php";
require_once "../../models/OrderModel.php";
if (!isset($_SESSION['user']) || $_SESSION['user']['Role'] != 2) {
    header('Location: http://localhost/shop/index.php');
}
$productModel = new ProductModel();
$orderModel = new OrderModel();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Detail</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h2>Order Detail</h2>

        <?php if (!empty($_SESSION['orderDetail'])) : ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>Order Name</th>
                        <th>Product Name</th>
                        <th>Image</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['orderDetail'] as $orderDetail) : ?>
                        <?php
                        $product = $productModel->getProductById($orderDetail['Product_id']);
                        $order = $orderModel->getOrderById($orderDetail['Order_id']);
                        ?>
                        <tr>
                            <td><?php echo $order['Name']; ?></td>
                            <td><?php echo $product['Name']; ?></td>
                            <td>
                                <img src="<?php echo $product['Image']; ?>" alt="<?php echo $product['Name']; ?>" class="img-thumbnail" style="max-height: 100px;">
                            </td>
                            <td><?php echo $orderDetail['Quantity']; ?></td>
                            <td><?php echo number_format($orderDetail['Price_product'], 0, ',', '.'); ?></td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>No order details found.</p>
        <?php endif; ?>

        <a href="/shop/controllers/OrderController.php?action=adminGetOrder" class="btn btn-primary">Quay lại</a>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>