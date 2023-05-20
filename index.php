<?php



require_once 'controllers/ProductController.php';



session_start();
define('BASE_URL', 'http://localhost/shop');

 

$productController = new ProductController();
$productController->index();
