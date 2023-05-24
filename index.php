<?php
//session_start();


require_once 'controllers/ProductController.php';




define('BASE_URL', 'http://localhost/shop');



$productController = new ProductController();
$productController->index();
