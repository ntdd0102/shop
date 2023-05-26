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

    public function getOrderById($orderId)
    {
        $sql = "SELECT * FROM orders WHERE Id = :orderId";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['orderId' => $orderId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);
        return $order;
    }


    public function addOrder($name, $customer_name, $phone, $email, $delivery_address, $total_price, $onl)
    {
        $sql = "INSERT INTO orders (Name, Customer_name, Phone, Email, Delivery_address, Date_created, Date_updated, Total_price, Is_pay_onl) 
            VALUES (:Name, :Customer_name, :Phone, :Email, :Delivery_address, NOW(), NOW(), :Total_price, :Is_pay_onl)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'Name' => $name,
            'Customer_name' => $customer_name,
            'Phone' => $phone,
            'Email' => $email,
            'Delivery_address' => $delivery_address,
            'Total_price' => $total_price,
            'Is_pay_onl' => $onl // Giá trị mặc định cho trường 'Is_pay_onl'
        ]);
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

    public function isOrderNameExists($name)
    {
        $sql = "SELECT COUNT(*) AS count FROM orders WHERE Name = :name";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['name' => $name]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return ($result['count'] > 0);
    }

    public function getOrdersByCustomerInfo($customerName, $phone)
    {
        // Chuẩn bị câu truy vấn SQL
        $sql = "SELECT * FROM orders WHERE Customer_name = :customerName AND Phone = :phone";
        // Chuẩn bị các giá trị tham số cho câu truy vấn
        $params = array(':customerName' => $customerName, ':phone' => $phone);

        try {
            // Thực thi câu truy vấn
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            // Lấy tất cả các order từ kết quả truy vấn
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $orders;
        } catch (PDOException $e) {
            // Xử lý ngoại lệ nếu có lỗi xảy ra
            echo 'Lỗi truy vấn: ' . $e->getMessage();
            return false;
        }
    }

    public function getOrdersByOrderName($orderName)
    {
        // Chuẩn bị câu truy vấn SQL
        $sql = "SELECT * FROM orders WHERE Name = :orderName";
        // Chuẩn bị giá trị tham số cho câu truy vấn
        $params = array(':orderName' => $orderName);

        try {
            // Thực thi câu truy vấn
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            // Lấy tất cả các order từ kết quả truy vấn
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $orders;
        } catch (PDOException $e) {
            // Xử lý ngoại lệ nếu có lỗi xảy ra
            echo 'Lỗi truy vấn: ' . $e->getMessage();
            return false;
        }
    }

    public function updateOrder($orderId, $name, $phone, $email, $customerName, $deliveryAddress, $dateCreated, $totalPrice, $isPayOnline, $status)
    {
        $stmt = $this->pdo->prepare("UPDATE `orders` SET 
                                        Name = :name, 
                                        Phone = :phone, 
                                        Email = :email, 
                                        Customer_name = :customerName, 
                                        Delivery_address = :deliveryAddress, 
                                        Date_created = :dateCreated, 
                                        Date_updated = NOW(), 
                                        Total_price = :totalPrice, 
                                        Is_pay_onl = :isPayOnline, 
                                        `Status` = :status 
                                    WHERE Id = :orderId");

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':customerName', $customerName);
        $stmt->bindParam(':deliveryAddress', $deliveryAddress);
        $stmt->bindParam(':dateCreated', $dateCreated);
        $stmt->bindParam(':totalPrice', $totalPrice);
        $stmt->bindParam(':isPayOnline', $isPayOnline);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':orderId', $orderId);

        return $stmt->execute();
    }


    public function getMonthlyRevenue()
    {
        // Truy vấn dữ liệu doanh thu theo tháng từ bảng "orders"
        $sql = "SELECT MONTH(Date_created) AS Month, SUM(Total_price) AS Revenue
            FROM orders
            WHERE Status = 5
            GROUP BY MONTH(Date_created)";
        $stmt = $this->pdo->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Xử lý kết quả truy vấn
        $revenueData = array(
            'labels' => array(),
            'data' => array()
        );

        foreach ($result as $row) {
            // Thêm tháng vào mảng nhãn (labels)
            $revenueData['labels'][] = date("F", mktime(0, 0, 0, $row['Month'], 1));

            // Thêm doanh thu vào mảng dữ liệu (data)
            $revenueData['data'][] = (int) $row['Revenue'];
        }

        return $revenueData;
    }

    public function getMonthlyProductSales()
    {
        $sql = "SELECT p.Id AS Product_id, p.Name, SUM(od.Quantity) AS TotalSales
        FROM orders AS o
        INNER JOIN order_detail AS od ON o.Id = od.Order_id
        INNER JOIN products AS p ON od.Product_id = p.Id
        WHERE o.Status = 5 AND MONTH(o.Date_created) = MONTH(CURRENT_DATE())
        GROUP BY p.Id, p.Name";
        $stmt = $this->pdo->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }
}
