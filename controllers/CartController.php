<?php

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'quantity_change') {
    var_dump($_REQUEST);
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
            // $quantity = $_POST['quantity'];
            // Cập nhật số lượng trong biến session cart
            // $_SESSION['cart'][$productId] = $quantity;

            // Trả về kết quả thành công hoặc không thành công (nếu cần)
            // header('Content-Type: application/json');
            // echo json_encode(['success' => true]);
            header('Location: ' . "/shop/views/user/cart.php");
            exit();
        }
    }
}
