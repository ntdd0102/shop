<?php
require_once 'connection.php';

class ProductModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = getConnection();
    }

    public function getAllProducts()
    {
        $sql = "SELECT * FROM products";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $products;
    }


    public function getProductByCategoryId($category_id)
    {
        $sql = "SELECT * FROM products WHERE category_id = :category_id AND is_visible = 1";
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
        $sql = "SELECT * FROM products WHERE Name LIKE :keyword AND is_visible = 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':keyword', '%' . $keyword . '%');
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function addProduct($name, $image, $description, $categoryId, $supplierId, $price, $quantity, $isVisible)
    {
        try {
            // Chuẩn bị câu lệnh SQL để thêm sản phẩm
            $sql = "INSERT INTO products (Name, Image, Description, Category_id, Supplier_id, Price, Quantity, Is_visible) 
                VALUES (:name, :image, :description, :categoryId, :supplierId, :price, :quantity, :isVisible)";

            // Chuẩn bị và bind các giá trị cho câu lệnh SQL
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':image', $image);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':categoryId', $categoryId);
            $stmt->bindParam(':supplierId', $supplierId);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':quantity', $quantity);
            $stmt->bindParam(':isVisible', $isVisible);

            // Thực thi câu lệnh SQL
            $stmt->execute();

            // Trả về ID của sản phẩm vừa được thêm vào
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            // Xử lý lỗi nếu có
            echo "Error: " . $e->getMessage();
            return false;
        }
    }


    public function updateProduct($product_id, $product_data)
    {
        $sql = "UPDATE products SET Name = :name, Description = :description, category_id = :category_id,
            supplier_id = :supplier_id, Price = :price, quantity = :quantity, is_visible = :is_visible";

        // Kiểm tra nếu có hình ảnh mới, thì cập nhật trường Image
        if (isset($product_data['image'])) {
            $sql .= ", Image = :image";
        }

        $sql .= " WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':name', $product_data['name']);
        $stmt->bindValue(':description', $product_data['description']);
        $stmt->bindValue(':category_id', $product_data['category_id']);
        $stmt->bindValue(':supplier_id', $product_data['supplier_id']);
        $stmt->bindValue(':price', $product_data['price']);
        $stmt->bindValue(':quantity', $product_data['quantity']);
        $stmt->bindValue(':is_visible', $product_data['is_visible']);

        // Kiểm tra nếu có hình ảnh mới, thì gán giá trị cho trường Image
        if (isset($product_data['image'])) {
            $stmt->bindValue(':image', $product_data['image']);
        }

        $stmt->bindValue(':id', $product_id);
        $stmt->execute();
        return $stmt->rowCount();
    }



    public function deleteProduct($product_id)
    {
        $sql = "UPDATE products SET is_visible = 0 WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $product_id]);
    }

    public function updateProductQuantity($product_id, $quantity)
    {
        $sql = "UPDATE products SET quantity = quantity - :quantity WHERE Id = :product_id";
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

    public function checkProductNameExists($name)
    {
        $sql = "SELECT COUNT(*) as count FROM products WHERE Name = :name";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':name', $name);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Kiểm tra số lượng sản phẩm có tên trùng
        if ($result['count'] > 0) {
            return true; // Tên sản phẩm đã tồn tại
        }

        return false; // Tên sản phẩm không tồn tại
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
