<?php
require "../models/ProductModel.php";
require "../models/OrderModel.php";
require "../models/Order_DetailModel.php";
session_start();

$cart = $_SESSION["cart"];

$sum = 0;
foreach ($cart as $item) {
    $product = new ProductModel();
    $itemProduct = $product->getProductById($item);
    $sum += ($itemProduct["Price"] * $cart[$itemProduct["Id"]]);
}
// tao order 
$order = new OrderModel();
$orderId = $order->addOrder("testlan2", "", "467/56/9 le duc tho", $sum);

//tao order detail
foreach ($cart as $item) {
    $orderDetail = new OrderDetailModel();
    $itemProduct = $product->getProductById($item);
    $orderDetail->addOrderDetail($orderId, $item, $cart[$itemProduct["Id"]], $itemProduct["Price"]);
}

header('Location: ' . "http://localhost/shop/views/payment/success.php");
