<?php
require "../models/OrderModel.php";
session_start();

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'viewHistory') {
    $orderController = new OrderController();
    $orderController->historyOrder();
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'searchByOrderName') {
    $orderController = new OrderController();
    $orderController->getOrderHistory();
}

class OrderController
{
    public function historyOrder()
    {
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
            $customerName = $user['Name'];
            $phone = $user['Phone'];
        } else {
            $customerName = '';
            $phone = '';
        }

        // Tạo đối tượng OrderModel
        $orderModel = new OrderModel();

        // Gọi phương thức để lấy các order dựa vào thông tin khách hàng
        $orders = $orderModel->getOrdersByCustomerInfo($customerName, $phone);

        // Chuyển hướng sang trang historyorder.php và truyền dữ liệu
        header('Location: http://localhost/shop/views/user/historyorder.php?orders=' . urlencode(serialize($orders)));
        exit();
    }

    public function getOrderHistory()
    {
        // Tạo đối tượng OrderModel
        $orderModel = new OrderModel();
        $orderName = $_POST['orderName'];

        // Gọi phương thức để lấy các order dựa vào tên đơn hàng
        $orders = $orderModel->getOrdersByOrderName($orderName);

        // Chuyển hướng sang trang historyorder.php và truyền dữ liệu
        header('Location: http://localhost/shop/views/user/historyorder.php?orders=' . urlencode(serialize($orders)));
        exit();
    }
}
