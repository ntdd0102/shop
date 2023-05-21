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

    public function getOrderDetailsByOrderId($orderId)
    {
        // Truy vấn cơ sở dữ liệu để lấy thông tin Order Detail dựa trên Order ID
        // Ví dụ:
        $query = "SELECT * FROM order_detail WHERE Order_id = :orderId";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':orderId', $orderId);
        $stmt->execute();
        $orderDetails = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $orderDetails;
    }
}
