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
}
