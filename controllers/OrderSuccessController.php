<?php
require "../models/ProductModel.php";
require "../models/OrderModel.php";
require "../models/Order_DetailModel.php";
session_start();

$cart = $_SESSION["cart"];

$sum = 0;
foreach ($cart as $productId => $quantity) {
    $product = new ProductModel();
    $itemProduct = $product->getProductById($productId);

    if ($itemProduct) {
        $sum += ($itemProduct["Price"] * $quantity);
    }
}

$nameC = $_POST['name'];
$phoneC = $_POST['phone'];
$addressC = $_POST['address'];

// Lưu thông tin vào cookie
setcookie('name', $nameC, time() + 3600, '/'); // Lưu tên vào cookie, có thời hạn 1 giờ
setcookie('phone', $phoneC, time() + 3600, '/'); // Lưu số điện thoại vào cookie, có thời hạn 1 giờ
setcookie('address', $addressC, time() + 3600, '/'); // Lưu địa chỉ vào cookie, có thời hạn 1 giờ



if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $customer_name = $user['Name'];
    $phone = $user['Phone'];
    $email = $user['Email'];
    $delivery_address = $user['Address'];
} else {
    $customer_name = isset($_COOKIE['name']) ? $_COOKIE['name'] : $_COOKIE['nameC'];
    $phone = isset($_COOKIE['phone']) ? $_COOKIE['phone'] : $_COOKIE['phoneC'];
    $email = '';
    $delivery_address = isset($_COOKIE['address']) ? $_COOKIE['address'] : $_COOKIE['addressC'];
}
$is_pay_onl = 0; // Khởi tạo giá trị mặc định là 0
if (isset($_SESSION['onl']) && $_SESSION['onl'] == 1) {
    $is_pay_onl = 1;
}

// Tạo order
$order = new OrderModel();
$orderName = generateOrderName();

// Kiểm tra sự tồn tại của tên order
while ($order->isOrderNameExists($orderName)) {
    $orderName = generateOrderName();
}

$orderId = $order->addOrder($orderName, $customer_name, $phone, $email, $delivery_address, $sum, $is_pay_onl);


// Tạo order detail
foreach ($cart as $productId => $quantity) {
    $orderDetail = new OrderDetailModel();
    $productModel = new ProductModel();
    $product = $productModel->getProductById($productId);

    if ($product) {
        $orderDetail->addOrderDetail($orderId, $productId, $quantity, $product["Price"]);

        // Cập nhật số lượng sản phẩm trong cơ sở dữ liệu sau khi tạo order detail thành công
        $productModel->updateProductQuantity($productId, $quantity);
    }
}


unset($_SESSION['cart']);
unset($_SESSION['onl']);

// Tạo cookie lưu trữ tên đơn hàng trong 10 phút
setcookie('order_name', $orderName, time() + 600, '/'); // 600 giây = 10 phút



header('Location: http://localhost/shop/views/payment/success.php');

/**
 * Hàm tạo tên đơn hàng ngẫu nhiên
 * @return string
 */
function generateOrderName()
{
    $prefix = 'HD';
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $randomString = '';
    $length = 8;

    $characterCount = strlen($characters);
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $characterCount - 1)];
    }

    return $prefix . $randomString;
}
