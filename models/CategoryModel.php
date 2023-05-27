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
    public function updateCategoryName($id, $newName)
    {
        $query = "UPDATE category SET Name = :newName WHERE Id = :id";

        // Chuẩn bị và thực thi câu truy vấn
        $statement = $this->pdo->prepare($query);
        $statement->bindParam(':newName', $newName);
        $statement->bindParam(':id', $id);
        return $statement->execute();
    }
    public function isCategoryExists($name)
    {
        $query = "SELECT COUNT(*) FROM category WHERE Name = :name";

        // Chuẩn bị và thực thi câu truy vấn
        $statement = $this->pdo->prepare($query);
        $statement->bindParam(':name', $name);
        $statement->execute();

        $count = $statement->fetchColumn();

        // Trả về true nếu tên danh mục đã tồn tại, ngược lại trả về false
        return ($count > 0);
    }

    public function addCategory($name)
    {
        $query = "INSERT INTO category (Name) VALUES (:name)";

        // Chuẩn bị và thực thi câu truy vấn
        $statement = $this->pdo->prepare($query);
        $statement->bindParam(':name', $name);
        return $statement->execute();
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
