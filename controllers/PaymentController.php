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
    $payMentController = new PaymentController();
    $payMentController->getInforOrder();
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'pay') {
    $payMentController = new PaymentController();
    $payMentController->payMentOrder();
}



class PaymentController
{

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
                'AUxcHOijcGtj8L1I5DSM2IN0ZeiWCh3r6Jmu9f6I9VmrlC1byXQFtHm7oqRjTQQ0o5ravUx2rFAdbhA7',     // replace with your client ID
                'ELji9G8W0NvA66zdzfWnrPh9eQTcGYwvYpDKV3qsEy0V_t8U8NWbHc4FuTXdUDoJTdMH-WHRKXeZmmDN'  // replace with your client secret
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
