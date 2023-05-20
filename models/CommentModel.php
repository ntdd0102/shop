<?php
require_once 'connection.php';

class CommentModel
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = getConnection();
    }

    public function getCommentsByProductId($product_id) {
        $sql = "SELECT * FROM comments WHERE product_id = :product_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['product_id' => $product_id]);
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $comments;
    }

    public function addComment($product_id, $user_id, $content) {
        $sql = "INSERT INTO comments (product_id, user_id, content, date_created) VALUES (:product_id, :user_id, :content, NOW())";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['product_id' => $product_id, 'user_id' => $user_id, 'content' => $content]);
        $comment_id = $this->pdo->lastInsertId();
        return $comment_id;
    }

    // public function addComment($product_id, $user_id, $content) {
    //     $sql = "INSERT INTO comments (product_id, user_id, content, date_created) VALUES (:product_id, :user_id, :content, NOW())";
    //     $stmt = $this->pdo->prepare($sql);
    //     $stmt->execute(['product_id' => $product_id, 'user_id' => $user_id, 'content' => $content]);
    // }
    
    
    public function updateComment($comment_id, $content) {
        $sql = "UPDATE comments SET content = :content, date_updated = NOW() WHERE id = :comment_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['content' => $content, 'comment_id' => $comment_id]);
    }

    public function deleteComment($comment_id) {
        $sql = "DELETE FROM comments WHERE id = :comment_id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['comment_id' => $comment_id]);
    }
    

}