<?php
require_once 'connection.php';

class OrderModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = getConnection();
    }

    public function getAllOrders()
    {
        $sql = "SELECT * FROM orders";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $orders;
    }

    public function getOrdersByStatus($status)
    {
        $sql = "SELECT * FROM orders WHERE status = :status";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['status' => $status]);
        $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $orders;
    }

    public function addOrder($name, $customer_id, $delivery_address, $total_price)
    {
        $sql = "INSERT INTO orders (Name, Customer_id, Delivery_address, Date_created, Date_updated, Total_price, Status) 
                VALUES (:Name, :Customer_id, :Delivery_address, NOW(), NOW(), :Total_price, 1)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['Name' => $name, 'Customer_id' => $customer_id, 'Delivery_address' => $delivery_address, 'Total_price' => $total_price]);
        $order_id = $this->pdo->lastInsertId();
        return $order_id;
    }

    public function updateOrderStatus($order_id, $status)
    {
        $sql = "UPDATE orders SET status = :status, date_updated = NOW() WHERE id = :order_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['order_id' => $order_id, 'status' => $status]);
        return $stmt->rowCount() > 0;
    }

    public function deleteOrder($order_id)
    {
        $sql = "UPDATE orders SET status = 5, date_updated = NOW() WHERE id = :order_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['order_id' => $order_id]);
        return $stmt->rowCount() > 0;
    }
}
