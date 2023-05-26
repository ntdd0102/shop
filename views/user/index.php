 <?php
    require_once "models/CategoryModel.php";
    //session_start();
    ?>

 <!DOCTYPE html>
 <html>

 <head>
     <title>Products by Category</title>
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
     <style>
     .navbar {
         font-family: Arial, sans-serif;
         font-size: 14px;
         /* Thay đổi kiểu chữ và kích thước */
     }

     .navbar-brand,
     .navbar-nav .nav-link {
         color: #fff;
         /* Màu chữ */
     }

     .navbar-brand:hover,
     .navbar-nav .nav-link:hover {
         color: #ffd700;
         /* Màu chữ khi hover */
     }

     .product-title {
         font-size: 18px;
         font-weight: bold;
         margin-bottom: 10px;
     }

     .product-card {
         height: 100%;
     }

     .product-card .card-img-top {
         width: 100%;
         height: 200px;
         object-fit: cover;
     }

     .product-card .card-body {
         padding: 10px;
     }

     .product-card .card-title {
         font-size: 16px;
         margin-bottom: 5px;
     }

     .product-card .card-price {
         font-weight: bold;
     }

     .product-card .card-link {
         color: #007bff;
         text-decoration: none;
     }

     .product-card .card-link:hover {
         text-decoration: underline;
     }
     </style>
 </head>

 <body>
     <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
         <a class="navbar-brand" href="#">Shop</a>
         <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
             aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
             <span class="navbar-toggler-icon"></span>
         </button>
         <div class="collapse navbar-collapse" id="navbarNav">
             <ul class="navbar-nav mr-auto">
                 <li class="nav-item active">
                     <a class="nav-link" href="/shop/index.php">Trang chủ</a>
                 </li>
                 <li class="nav-item">
                     <a class="nav-link" href="/shop/controllers/OrderController.php?action=viewHistory">History</a>
                 </li>
                 <li class="nav-item">
                     <a class="nav-link" href="/shop/views/user/order.php">Order</a>
                 </li>
             </ul>
             <form class="form-inline my-2 my-lg-0" method="POST"
                 action="/shop/controllers/ProductController.php?action=searchProducts">
                 <input class="form-control mr-sm-2" type="search" placeholder="Tìm kiếm" aria-label="Search"
                     name="search">
                 <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Tìm kiếm</button>
             </form>
             <ul class="navbar-nav ml-auto">
                 <?php if (isset($_SESSION['user'])) : ?>
                 <li class="nav-item">
                     <a class="nav-link"
                         href="/shop/views/account.php"><?php echo "Xin chào, " . $_SESSION['user']['Name']; ?></a>
                 </li>
                 <li class="nav-item">
                     <a class="nav-link" href="/shop/controllers/UserController.php?action=logout">Đăng xuất</a>
                 </li>
                 <?php else : ?>
                 <li class="nav-item">
                     <a class="nav-link" href="/shop/views/login.php">Đăng nhập</a>
                 </li>
                 <?php endif; ?>
                 <li class="nav-item">
                     <a class="nav-link" href="/shop/views/user/cart.php">Giỏ hàng</a>
                 </li>
             </ul>
         </div>
     </nav>


     <div class="container mt-4">
         <?php foreach ($productsByCategory as $categoryId => $products) : ?>
         <h1 class="my-4">Sản phẩm: <?php echo $categoryModel->getCategoryNameById($categoryId); ?></h1>
         <div class="row">
             <?php $count = 0; ?>
             <?php foreach ($products as $product) : ?>
             <?php if ($count >= 4) break; ?>
             <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                 <div class="card h-100 product-card">
                     <a href="/shop/views/user/product_detail.php?id=<?php echo $product['Id']; ?>">
                         <img class="card-img-top" src="<?php echo $product['Image']; ?>"
                             alt="<?php echo $product['Name']; ?>">
                     </a>
                     <div class="card-body">
                         <h4 class="card-title">
                             <a href="/shop/views/user/product_detail.php?id=<?php echo $product['Id']; ?>">
                                 <?php echo $product['Name']; ?>
                             </a>
                         </h4>
                         <p class="card-price">Giá: <?php echo number_format($product['Price'], 0, ",", "."); ?> vnđ</p>
                     </div>
                 </div>
             </div>
             <?php $count++; ?>
             <?php endforeach; ?>

             <?php if (count($products) > 4) : ?>
             <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                 <div class="card h-100 product-card">
                     <a href="/shop/views/user/product_list.php?category_id=<?php echo $categoryId; ?>"
                         class="card-link">
                         <div class="card-body">Xem thêm</div>
                     </a>
                 </div>
             </div>
             <?php endif; ?>
         </div>
         <?php endforeach; ?>
     </div>

     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

     <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
     <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
 </body>

 </html>