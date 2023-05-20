<?php
require_once 'connection.php';

class ProductModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = getConnection();
    }

    public function getProductByCategoryId($category_id)
    {
        $sql = "SELECT * FROM products WHERE category_id = :category_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['category_id' => $category_id]);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $products;
    }

    public function getProductById($product_id)
    {
        $sql = "SELECT * FROM products WHERE id = :product_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['product_id' => $product_id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        return $product;
    }

    public function search($keyword)
    {
        $sql = "SELECT * FROM products WHERE Name LIKE :keyword";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':keyword', '%' . $keyword . '%');
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function addProduct(Product $product)
    {
        $sql = "INSERT INTO products (Name, Image, Description, category_id, supplier_id, Price, Quantity, Is_visible)
            VALUES (:name, :image, :description, :category_id, :supplier_id, :price, :quantity, :is_visible)";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':name', $product->getName());
        $stmt->bindParam(':image', $product->getImage());
        $stmt->bindParam(':description', $product->getDescription());
        $stmt->bindParam(':category_id', $product->getCategoryId());
        $stmt->bindParam(':supplier_id', $product->getSupplierId());
        $stmt->bindParam(':price', $product->getPrice());
        $stmt->bindParam(':quantity', $product->getQuantity());
        $stmt->bindParam(':is_visible', $product->getIsVisible());

        return $stmt->execute();
    }

    public function updateProduct(Product $product)
    {
        $sql = "UPDATE products SET Name=:name, Image=:image, Description=:description, category_id=:category_id,
            supplier_id=:supplier_id, Price=:price, Quantity=:quantity, Is_visible=:is_visible WHERE id=:id";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':name', $product->getName());
        $stmt->bindParam(':image', $product->getImage());
        $stmt->bindParam(':description', $product->getDescription());
        $stmt->bindParam(':category_id', $product->getCategoryId());
        $stmt->bindParam(':supplier_id', $product->getSupplierId());
        $stmt->bindParam(':price', $product->getPrice());
        $stmt->bindParam(':quantity', $product->getQuantity());
        $stmt->bindParam(':is_visible', $product->getIsVisible());
        $stmt->bindParam(':id', $product->getId());

        return $stmt->execute();
    }

    public function deleteProduct($product_id)
    {
        $sql = "DELETE FROM products WHERE id = :product_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['product_id' => $product_id]);

        return $stmt->rowCount();
    }

    public function updateProductQuantity($product_id, $quantity)
    {
        $sql = "UPDATE products SET quantity = quantity + :quantity WHERE Id = :product_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);

        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            // Handle the exception, e.g., log the error or show an error message
            return false;
        }
    }
}



class Product
{
    private $id;
    private $name;
    private $image;
    private $description;
    private $category_id;
    private $supplier_id;
    private $price;
    private $quantity;
    private $is_visible;

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

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getCategoryId()
    {
        return $this->category_id;
    }

    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;
    }

    public function getSupplierId()
    {
        return $this->supplier_id;
    }

    public function setSupplierId($supplier_id)
    {
        $this->supplier_id = $supplier_id;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price)
    {
        $this->price = $price;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    public function getIsVisible()
    {
        return $this->is_visible;
    }

    public function setIsVisible($is_visible)
    {
        $this->is_visible = $is_visible;
    }
}
