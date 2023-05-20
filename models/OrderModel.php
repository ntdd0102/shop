<?php
require_once 'connection.php';

class OrderModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = getConnection();
    }

    public function getAllOrders() {
        $sql = "SELECT * FROM orders";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $orders;
    }
    
    public function getOrdersByStatus($status) {
        $sql = "SELECT * FROM orders WHERE status = :status";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['status' => $status]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $orders;
    }
    
    public function addOrder($name, $customer_id, $delivery_address) {
        $sql = "INSERT INTO orders (name, customer_id, delivery_address, date_created, date_updated, total_price, status) 
                VALUES (:name, :customer_id, :delivery_address, NOW(), NOW(), 0, 1)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['name' => $name, 'customer_id' => $customer_id, 'delivery_address' => $delivery_address]);
        $order_id = $this->pdo->lastInsertId();
        return $order_id;
    }
    
    public function updateOrderStatus($order_id, $status) {
        $sql = "UPDATE orders SET status = :status, date_updated = NOW() WHERE id = :order_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['order_id' => $order_id, 'status' => $status]);
        return $stmt->rowCount() > 0;
    }
    
    public function deleteOrder($order_id) {
        $sql = "UPDATE orders SET status = 5, date_updated = NOW() WHERE id = :order_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['order_id' => $order_id]);
        return $stmt->rowCount() > 0;
    }
    
}