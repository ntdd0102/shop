<?php
require "../models/OrderModel.php";
require_once "../models/Order_DetailModel.php";
require_once "../models/ProductModel.php";
session_start();

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'viewHistory') {
    $orderController = new OrderController();
    $orderController->historyOrder();
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'searchByOrderName') {
    $orderController = new OrderController();
    $orderController->getOrderHistory();
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'adminGetOrder') {
    $orderController = new OrderController();
    $orderController->getOrder();
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'cancelOrder') {
    $orderController = new OrderController();
    $orderController->cancelOrder();
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'viewOrderDetail') {
    $orderController = new OrderController();
    $orderController->viewDetailOrder();
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'editOrder') {
    $orderController = new OrderController();
    $orderController->beforeEditOrder();
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'saveEditOrder') {
    $orderController = new OrderController();
    $orderController->editOrder();
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'revenue') {
    $orderController = new OrderController();
    $orderController->showMonthlyRevenue();
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

    public function getOrder()
    {
        // Tạo đối tượng OrderModel
        $orderModel = new OrderModel();

        // Gọi phương thức để lấy tất cả đơn hàng
        $orders = $orderModel->getAllOrders();

        $_SESSION['order'] = $orders;

        header('Location: http://localhost/shop/views/admin/orderAdmin.php');
        exit();
    }

    public function cancelOrder()
    {
        $orderId = $_GET['id'];

        $orderModel = new OrderModel();

        $orderModel->updateOrderStatus($orderId, 6);

        $orderController = new OrderController();
        $orderController->getOrder();
    }

    public function viewDetailOrder()
    {
        $orderId = $_GET['id'];

        $orderDetailModel = new OrderDetailModel();

        $orderDetail = $orderDetailModel->getOrderDetailsByOrderId($orderId);

        $_SESSION['orderDetail'] = $orderDetail;

        header('Location: http://localhost/shop/views/admin/orderDetailAdmin.php');
        exit();
    }

    public function beforeEditOrder()
    {
        if (isset($_GET['id'])) {
            $orderId = $_GET['id'];

            $orderModel = new OrderModel();

            $order = $orderModel->getOrderById($orderId);

            require_once dirname(__FILE__) . '../../views/admin/editorder.php';
        }
    }

    public function editOrder()
    {
        $orderId = $_POST['order_id'];

        $orderModel = new OrderModel();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $email = $_POST['email'];
            $customerName = $_POST['customer_name'];
            $deliveryAddress = $_POST['delivery_address'];
            $dateCreated = $_POST['date_created'];
            $totalPrice = $_POST['total_price'];
            $isPayOnline = $_POST['is_pay_onl'];
            $status = $_POST['status'];

            $orderModel->updateOrder($orderId, $name, $phone, $email, $customerName, $deliveryAddress, $dateCreated, $totalPrice, $isPayOnline, $status);


            $orderController = new OrderController();
            $orderController->getOrder();
        }
    }

    public function showMonthlyRevenue()
    {
        // Get monthly revenue data from the model
        $orderModel = new OrderModel();
        $revenueData = $orderModel->getMonthlyRevenue();

        // Get monthly product sales data from the model
        $productSalesData = $orderModel->getMonthlyProductSales();

        // Pass the data to the view
        // You can choose your preferred way to pass data to the view (e.g., using a template engine)
        // In this example, I'll simply include the view file and pass the data as variables
        $revenueLabels = $revenueData['labels'];
        $revenueData = $revenueData['data'];
        $productSalesLabels = array_column($productSalesData, 'Name');
        $productSalesData = array_column($productSalesData, 'TotalSales');
        require_once dirname(__FILE__) . '../../views/admin/revenue.php';
    }
}
