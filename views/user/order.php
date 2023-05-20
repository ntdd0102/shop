<?php
require_once "../../models/ProductModel.php";
session_start();
// Lấy mảng sản phẩm đã lưu trong session, với key là id sản phẩm, value là số lượng sản phẩm
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
// Nếu giỏ hàng không có sản phẩm nào thì hiển thị thông báo
if (empty($cart)) {
    $cartMessage = "Your cart is empty.";
} else {
    $productModel = new ProductModel();
    $totalPrice = 0;
    $products = array();

    // Lấy thông tin chi tiết sản phẩm từ database và tính tổng giá tiền
    foreach ($cart as $productId => $quantity) {
        $product = $productModel->getProductById($productId);
        $product['Quantity'] = $quantity;
        $totalPrice += $product['Price'] * $quantity;
        array_push($products, $product);
    }
}

// Kiểm tra xem đã có dữ liệu được gửi từ form hay chưa
$paymentMethod = isset($_POST['paymentMethod']) ? $_POST['paymentMethod'] : 'cash';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css"
        integrity="sha512-y/4rIGt5s4ixEFp1C+3GyLUGQrMvEDJx4wz8+j0N/gkwBzV4i+dP1p7Hn/cw7jGOx33KzqplI4bxfRxyFHYsQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-3">
        <h1 class="mb-3">Your Cart</h1>

        <?php if (isset($cartMessage)) { ?>
        <p><?php echo $cartMessage; ?></p>
        <?php } else { ?>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Image</th>
                    <th scope="col">Name</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Price</th>
                    <th scope="col">Total Price</th>

                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product) { ?>
                <tr>
                    <td><img src="<?php echo $product['Image']; ?>" alt="<?php echo $product['Name']; ?>"
                            class="img-thumbnail" style="max-height: 100px;"></td>
                    <td><?php echo $product['Name']; ?></td>
                    <td><?php echo $product['Quantity']; ?></td>
                    <td class="price" data-price="<?php echo $product['Price']; ?>">
                        <?php echo number_format($product['Price'], 0, ",", "."); ?> vnđ</td>
                    <td id="totalPrice" class="total-price">
                        <?php echo number_format($product['Price'] * $product['Quantity'], 0, ",", "."); ?> vnđ</td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <div class="row">
            <div class="col-md-6">
                <p class="h5" data-price="<?php echo $totalPrice; ?>">Total Price:
                    <?php echo number_format($totalPrice, 0, ",", "."); ?> vnđ</p>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                <h4>Select payment method:</h4>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="paymentMethod" id="cashPayment" value="cash"
                        <?php echo $paymentMethod === 'cash' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="cashPayment">
                        Cash payment
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="paymentMethod" id="onlinePayment" value="online"
                        <?php echo $paymentMethod === 'online' ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="onlinePayment">
                        Online payment
                    </label>
                </div>
            </div>
            <div class="col-md-6 text-end">
                <?php if ($paymentMethod === 'cash') { ?>
                <a href="/shop/controllers/PaymentController.php?action=Paymentincash"
                    class="btn btn-primary">Payment</a>
                <?php } elseif ($paymentMethod === 'online') { ?>
                <a href="/shop/controllers/PaymentController.php?action=getInforOrder"
                    class="btn btn-primary">Payment</a>
                <?php } ?>
            </div>
        </div>

        <?php } ?>
    </div>
    <div class="col-md-6 text-end">
        <a href="/shop/index.php" class="btn btn-primary">Tiếp tục mua sắm</a>
    </div>

    <script>
    $(document).ready(function() {
        $('input[name="paymentMethod"]').on('change', function() {
            var paymentMethod = $('input[name="paymentMethod"]:checked').val();
            var paymentLink = '';

            if (paymentMethod === 'cash') {
                paymentLink = '/shop/controllers/PaymentController.php?action=Paymentincash';
            } else if (paymentMethod === 'online') {
                paymentLink = '/shop/controllers/PaymentController.php?action=getInforOrder';
            }

            $('.col-md-6.text-end a.btn-primary').attr('href', paymentLink);
        });
    });
    </script>
</body>

</html>