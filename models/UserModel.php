<?php
require_once "connection.php";

class UserModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = getConnection();
    }

    public function checklogin($username, $password)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM user WHERE Username = :username AND Pass = :password");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user;
    }

    public function getUser($userId)
    {
        $sql = "SELECT * FROM user WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    }

    public function addUser($username, $password, $name, $address, $email, $phone)
    {
        $stmt = $this->pdo->prepare("INSERT INTO user (Username, Pass, Name, Address, Email, Phone) VALUES (:username, :password, :name, :address, :email, :phone)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->execute();

        // Trả về số bản ghi bị ảnh hưởng bởi câu lệnh SQL vừa thực thi (thường là số bản ghi được thêm mới)
        return $stmt->rowCount();
    }

    public function updateUser($id, $name, $address, $email, $phone)
    {
        $stmt = $this->pdo->prepare("UPDATE user SET Name=:name, Address=:address, Email=:email, Phone=:phone WHERE Id=:id");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }


    public function deleteUser($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM user WHERE UserID=:id");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function checkUsernameExists($username)
    {

        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM user WHERE Username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }
}
