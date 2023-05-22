<?php
session_start();
require_once "../models/CommentModel.php";
require_once "../models/ProductModel.php";

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'postCmt') {
    $cmtController = new CommentController();
    $cmtController->postCmt();
}

class CommentController
{
    public function postCmt()
    {
        if (isset($_SESSION['user'])) {
            $productId = $_POST['productId'];
            $content = $_POST['content'];

            // Kiểm tra xem sản phẩm có tồn tại trong CSDL không
            $productModel = new ProductModel();
            $product = $productModel->getProductById($productId);
            if ($product) {
                // Lấy thông tin người dùng đăng nhập
                $userId = $_SESSION['user']['Id'];

                // Gọi model để thêm bình luận
                $commentModel = new CommentModel();
                $commentId = $commentModel->addComment($productId, $userId, $content);

                // Chuyển hướng về trang chi tiết sản phẩm
                header("Location: /shop/views/user/product_detail.php?id=$productId&success=1");
                exit();
            } else {
                echo "Sản phẩm không tồn tại";
            }
        } else {
            header("Location: /shop/views/user/product_detail.php?id={$_POST['productId']}&error=1");

            exit();
        }
    }
}
