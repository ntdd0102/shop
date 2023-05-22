 <?php
    require_once "../../models/ProductModel.php";
    session_start();

    if (isset($_GET['id'])) {
        $productId = $_GET['id'];
        $productModel = new ProductModel();
        $product = $productModel->getProductById($productId);
    }
    if (isset($_POST['add-to-cart'])) {
        // Kiểm tra nếu giỏ hàng đã được khởi tạo trong phiên đăng nhập
        if (isset($_SESSION['cart'])) {
            // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
            if (isset($_SESSION['cart'][$productId])) {
                // Nếu sản phẩm đã có trong giỏ hàng, cập nhật số lượng sản phẩm
                $_SESSION['cart'][$productId] += 1;
                $message = "Thêm sản phẩm vào giỏ hàng thành công!";
            } else {
                // Nếu sản phẩm chưa có trong giỏ hàng, thêm sản phẩm mới vào mảng giỏ hàng
                $_SESSION['cart'][$productId] = 1;
                $message = "Thêm sản phẩm vào giỏ hàng thành công!";
            }
        } else {
            // Nếu giỏ hàng chưa được khởi tạo trong phiên đăng nhập, tạo một mảng mới để lưu trữ sản phẩm
            $_SESSION['cart'] = array($productId => 1);
            $message = "Thêm sản phẩm vào giỏ hàng thành công!";
        }
    }
    if (isset($_GET['error']) && $_GET['error'] == 1) {
        $messageR = "Vui lòng đăng nhập để bình luận";
    } else if (isset($_GET['success']) && $_GET['success'] == 1) {
        $messageR = "Bình luận thành công";
    } else {
        $messageR = ""; // Đặt giá trị mặc định là rỗng nếu không có lỗi
    }

    ?>
 <!DOCTYPE html>
 <html>

 <head>
     <title>Product Detail</title>
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
 </head>

 <body>

     <?php if ($messageR !== "") : ?>
         <p><?php echo $messageR; ?></p> <!-- Hiển thị thông báo lỗi khi có giá trị -->
     <?php endif; ?>

     <div class="container my-5">
         <div class="row">
             <div class="col-lg-4">
                 <img src="<?php echo $product['Image']; ?>" alt="<?php echo $product['Name']; ?>" class="img-fluid">
             </div>
             <div class="col-lg-8">
                 <h1 class="mb-4"><?php echo $product['Name']; ?></h1>
                 <p class="lead"><?php echo $product['Description']; ?></p>
                 <p class="font-weight-bold mb-2">Price: <?php echo number_format($product['Price'], 0, ",", "."); ?>
                     vnđ
                 </p>
                 <form method="post">
                     <input type="hidden" name="productId" value="<?php echo $product['Id']; ?>">
                     <button type="submit" class="btn btn-primary" name="add-to-cart">Add to Cart</button>
                 </form>
                 <a href="/shop/views/user/cart.php" class="btn btn-secondary ml-2">Cart</a>
                 <?php if (isset($message)) : ?>
                     <div class="alert alert-primary mt-3" id="alert-message"><?php echo $message; ?></div>
                     <script>
                         setTimeout(function() {
                             $('#alert-message').fadeOut('fast');
                         }, 3000);
                     </script>
                 <?php endif; ?>

             </div>
         </div>
     </div>

     <div class="comments-section">
         <h2>Bình luận</h2>
         <!-- Form bình luận -->
         <form method="post" action="/shop/controllers/CommentController.php?action=postCmt">
             <input type="hidden" name="productId" value="<?php echo $product['Id']; ?>">
             <div class="form-group">
                 <textarea class="form-control" name="content" rows="3" placeholder="Nhập bình luận của bạn"></textarea>
             </div>
             <button type="submit" class="btn btn-primary">Gửi</button>
         </form>

         <!-- Danh sách các bình luận -->
         <?php
            require_once "../../models/CommentModel.php";
            $commentModel = new CommentModel();
            // Lấy số trang hiện tại
            $current_page = isset($_GET['page']) ? $_GET['page'] : 1;

            // Số lượng bình luận muốn hiển thị trên mỗi trang
            $comments_per_page = 5;

            // Tính toán vị trí bắt đầu và số lượng bình luận cho trang hiện tại
            $start_index = ($current_page - 1) * $comments_per_page;
            $end_index = $start_index + $comments_per_page;

            // Lấy danh sách bình luận từ cơ sở dữ liệu dựa trên product_id và phân trang
            $comments = $commentModel->getCommentsByProductIdWithPagination($_GET['id'], $start_index, $end_index);

            // Lấy tổng số lượng bình luận
            $total_comments = $commentModel->getTotalCommentsByProductId($_GET['id']);

            // Tính toán số trang
            $total_pages = ceil($total_comments / $comments_per_page);
            ?>

         <?php if (!empty($comments)) : ?>
             <div class="comment-list">
                 <?php foreach ($comments as $comment) : ?>
                     <?php
                        require_once "../../models/UserModel.php";
                        $userModel = new UserModel();
                        $user = $userModel->getUser($comment['User_id']);
                        ?>
                     <div class="comment">
                         <p class="user"><?php echo $user['Name']; ?></p>
                         <p class="content"><?php echo $comment['Content']; ?></p>
                         <p class="timestamp"><?php echo $comment['Date_created']; ?></p>
                     </div>
                 <?php endforeach; ?>
             </div>

             <!-- Phân trang -->
             <?php if ($total_pages > 1) : ?>
                 <div class="pagination">
                     <?php for ($page = 1; $page <= $total_pages; $page++) : ?>
                         <a href="?id=<?php echo $_GET['id']; ?>&page=<?php echo $page; ?>"><?php echo $page; ?></a>
                     <?php endfor; ?>
                 </div>
             <?php endif; ?>
         <?php endif; ?>

     </div>
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
 </body>

 </html>