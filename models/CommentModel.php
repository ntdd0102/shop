<?php
require_once 'connection.php';

class CommentModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = getConnection();
    }

    public function getCommentsByProductId($product_id)
    {
        $sql = "SELECT * FROM comments WHERE product_id = :product_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['product_id' => $product_id]);
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $comments;
    }

    public function addComment($product_id, $user_id, $content)
    {
        $sql = "INSERT INTO comments (Product_id, User_id, Content, Date_created) VALUES (:Product_id, :User_id, :Content, NOW())";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['Product_id' => $product_id, 'User_id' => $user_id, 'Content' => $content]);
        $comment_id = $this->pdo->lastInsertId();
        return $comment_id;
    }

    public function getCommentsByProductIdWithPagination($product_id, $start_index, $end_index)
    {
        $sql = "SELECT * FROM comments WHERE product_id = :product_id ORDER BY Date_created DESC LIMIT :start_index, :end_index";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->bindParam(':start_index', $start_index, PDO::PARAM_INT);
        $stmt->bindParam(':end_index', $end_index, PDO::PARAM_INT);
        $stmt->execute();
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $comments;
    }

    public function getTotalCommentsByProductId($product_id)
    {
        $sql = "SELECT COUNT(*) as total_comments FROM comments WHERE product_id = :product_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['product_id' => $product_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_comments'];
    }



    // public function addComment($product_id, $user_id, $content) {
    //     $sql = "INSERT INTO comments (product_id, user_id, content, date_created) VALUES (:product_id, :user_id, :content, NOW())";
    //     $stmt = $this->pdo->prepare($sql);
    //     $stmt->execute(['product_id' => $product_id, 'user_id' => $user_id, 'content' => $content]);
    // }


    public function updateComment($comment_id, $content)
    {
        $sql = "UPDATE comments SET content = :content, date_updated = NOW() WHERE id = :comment_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['content' => $content, 'comment_id' => $comment_id]);
    }

    public function deleteComment($comment_id)
    {
        $sql = "DELETE FROM comments WHERE id = :comment_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['comment_id' => $comment_id]);
    }
}
