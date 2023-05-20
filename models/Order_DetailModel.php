<?php
require_once 'connection.php';

class OrderDetailModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = getConnection();
    }
}