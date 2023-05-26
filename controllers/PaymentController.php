<?php
session_start();
require  '../vendor/autoload.php';
require "../models/ProductModel.php";

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Rest\ApiContext;

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'getInforOrder') {
    if (isset($_GET['order']) && $_GET['order'] == 'cash') {
        $_SESSION['onl'] = 0;
    }
    if (isset($_GET['order']) && $_GET['order'] == 'onl') {
        $_SESSION['onl'] = 1;
    }
    $payMentController = new PaymentController();
    $payMentController->getInforOrder();
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'pay') {
    // Kiểm tra số lượng sản phẩm trong cơ sở dữ liệu
    $productModel = new ProductModel();
    $isQuantityAvailable = true;

    foreach ($_SESSION['cart'] as $productId => $quantity) {
        $product = $productModel->getProductById($productId);
        if ($product && $product['quantity'] < $quantity) {
            $isQuantityAvailable = false;
            break;
        }
    }

    if ($isQuantityAvailable) {
        // Số lượng sản phẩm đủ để đặt hàng, tiếp tục xử lý thanh toán
        $paymentController = new PaymentController();
        if (isset($_SESSION['onl']) && $_SESSION['onl'] == 0) {
            $paymentController->payByCash();
        } else if (isset($_SESSION['onl']) && $_SESSION['onl'] == 1) {
            $paymentController->payMentOrder();
        }
    } else {
        // Số lượng sản phẩm không đủ, chuyển hướng về trang order với thông báo lỗi
        header("Location: /shop/views/user/order.php?error=1");
        exit;
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'infor') {
    // Lấy thông tin từ POST request
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Lưu thông tin vào cookie
    setcookie('name', $name, time() + 3600, '/'); // Lưu tên vào cookie, có thời hạn 1 giờ
    setcookie('phone', $phone, time() + 3600, '/'); // Lưu số điện thoại vào cookie, có thời hạn 1 giờ
    setcookie('address', $address, time() + 3600, '/'); // Lưu địa chỉ vào cookie, có thời hạn 1 giờ

    // Kiểm tra số lượng sản phẩm trong cơ sở dữ liệu
    // Kiểm tra số lượng sản phẩm trong cơ sở dữ liệu
    $productModel = new ProductModel();
    $isQuantityAvailable = true;

    // echo $_COOKIE['name'];
    // echo $_COOKIE['phone'];
    // echo $_COOKIE['address'];
    foreach ($_SESSION['cart'] as $productId => $quantity) {
        $product = $productModel->getProductById($productId);
        if ($product && $product['quantity'] < $quantity) {
            $isQuantityAvailable = false;
            break;
        }
    }

    if ($isQuantityAvailable) {
        // Số lượng sản phẩm đủ để đặt hàng, tiếp tục xử lý thanh toán
        $paymentController = new PaymentController();
        if (isset($_SESSION['onl']) && $_SESSION['onl'] == 0) {
            $paymentController->payByCash();
        } else if (isset($_SESSION['onl']) && $_SESSION['onl'] == 1) {
            $paymentController->payMentOrder();
        }
    } else {
        // Số lượng sản phẩm không đủ, chuyển hướng về trang order với thông báo lỗi
        header("Location: /shop/views/user/order.php?error=1");
        exit;
    }
}

class PaymentController
{
    public function payByCash()
    {
        header('Location: ' . "/shop/controllers/OrderSuccessController.php");
        exit();
    }

    public function getInforOrder()
    {
        if (isset($_SESSION['user'])) {
            header('Location: ' . "/shop/controllers/PaymentController.php?action=pay");
            exit();
        } else {
            header('Location: ' . "/shop/views/user/inforder.php");
            exit();
        }
    }


    public function payMentOrder()
    {

        $paypal = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                'ASmq69O3yCJwIIzsEIP9v1q6HooV_lJtNLOQbIKaIdiIS-U8m0D1A7WDTOFTmIvP0GmfkNa-i233ts_X',     // replace with your client ID
                'EHgzi7A8kgq03_33c3pUawcq6ygk-RS6HP_BaCYJ4lF_IIgOPmfBnOSEWshk410upJuqShm59hP0Rdi7'  // replace with your client secret
            )
        );
        $paypal->setConfig([
            'mode' => 'sandbox',    // or 'live' for production mode
            'http.ConnectionTimeOut' => 30,
            'log.LogEnabled' => true,
            'log.FileName' => 'paypal.log',
            'log.LogLevel' => 'FINE'
        ]);

        $cart = $_SESSION["cart"];

        $itemList = new ItemList();
        $arrayData = [];
        $sum = 0;
        var_dump($cart);

        foreach ($cart as $itemId => $quantity) {
            $product = new ProductModel();
            $itemProduct = $product->getProductById($itemId);
            $item1 = new Item();
            $item1->setName($itemProduct["Name"])
                ->setCurrency('USD')
                ->setQuantity($quantity)
                ->setPrice($itemProduct["Price"]);
            array_push($arrayData, $item1);
            $sum += ($itemProduct["Price"] * $quantity);
        }

        echo "</br>";
        var_dump($arrayData);

        $itemList->setItems($arrayData);

        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal($sum);        // replace with the payment amount

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription('Payment description of computer shop');  // replace with the payment description

        $redirectUrls = new RedirectUrls();
        // $redirectUrls->setReturnUrl('http://localhost/shop/views/payment/success.php')
        //     ->setCancelUrl('http://localhost/shop/views/payment/cancel.php');
        $redirectUrls->setReturnUrl('http://localhost/shop/controllers/OrderSuccessController.php')
            ->setCancelUrl('http://localhost/shop/views/payment/cancel.php');

        $payment = new Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setTransactions([$transaction])
            ->setRedirectUrls($redirectUrls);

        try {
            $payment->create($paypal);
            header('Location: ' . $payment->getApprovalLink());
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            echo $ex->getCode();     // prints the HTTP status code
            echo $ex->getData();     // prints the response data
            die($ex);
        } catch (\Exception $ex) {
            die($ex);
        }
    }
}
