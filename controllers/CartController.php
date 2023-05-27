<?php

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'quantity_change') {

    $cartController = new CartController();
    $cartController->updateQuantityCart();
}
class CartController
{
    public function updateQuantityCart()
    {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = $_REQUEST['productId'];
            $type = $_REQUEST["type"];
            if ($type == "plus") {
                $_SESSION["cart"][$productId] += 1;
            } else {
                if ($_SESSION["cart"][$productId] == 1) {
                    unset($_SESSION["cart"][$productId]);
                } else {
                    $_SESSION["cart"][$productId] -= 1;
                }
            }

            header('Location: ' . "/shop/views/user/cart.php");
            exit();
        }
    }
}
