<?php
require_once 'connection.php';

class CategoryModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = getConnection();
    }


    public function getAll()
    {
        $sql = "SELECT * FROM category";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCategoryNameById($id)
    {
        $sql = "SELECT Name FROM category WHERE Id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $category = $stmt->fetch(PDO::FETCH_ASSOC);
        return $category['Name'];
    }
}

class Category
{
    private $id;
    private $name;


    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
}
