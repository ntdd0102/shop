<?php
require_once 'connection.php';

class SupplierModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = getConnection();
    }


    public function getAll()
    {
        $sql = "SELECT * FROM supplier";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSupplierNameById($id)
    {
        $sql = "SELECT Name FROM supplier WHERE Id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $supplier = $stmt->fetch(PDO::FETCH_ASSOC);
        return $supplier['Name'];
    }
    public function updateSupplierName($id, $newName)
    {
        $query = "UPDATE supplier SET Name = :newName WHERE Id = :id";

        // Chuẩn bị và thực thi câu truy vấn
        $statement = $this->pdo->prepare($query);
        $statement->bindParam(':newName', $newName);
        $statement->bindParam(':id', $id);
        return $statement->execute();
    }
    public function isSupplierExists($name)
    {
        $query = "SELECT COUNT(*) FROM supplier WHERE Name = :name";

        // Chuẩn bị và thực thi câu truy vấn
        $statement = $this->pdo->prepare($query);
        $statement->bindParam(':name', $name);
        $statement->execute();

        $count = $statement->fetchColumn();

        // Trả về true nếu tên danh mục đã tồn tại, ngược lại trả về false
        return ($count > 0);
    }

    public function addSupplier($name)
    {
        $query = "INSERT INTO supplier (Name) VALUES (:name)";

        // Chuẩn bị và thực thi câu truy vấn
        $statement = $this->pdo->prepare($query);
        $statement->bindParam(':name', $name);
        return $statement->execute();
    }
}
