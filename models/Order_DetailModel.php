<?php
require_once 'connection.php';

class OrderDetailModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = getConnection();
    }


    public function addOrderDetail($order_id, $product_id, $quantity, $price_product)
    {
        $sql = "INSERT INTO order_detail (order_id, product_id, quantity, price_product) 
                VALUES (:order_id, :product_id, :quantity, :price_product)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['order_id' => $order_id, 'product_id' => $product_id, 'quantity' => $quantity, 'price_product' => $price_product]);
        $order_id = $this->pdo->lastInsertId();
        return $order_id;
    }
}