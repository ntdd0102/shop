<?php

 

require_once __DIR__ . '/../models/ProductModel.php';
require_once __DIR__ . '/../models/CategoryModel.php';
 

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'searchProducts') {
    $productController = new ProductController();
    $productController->searchProducts();
}



class ProductController
{

    public function index()
    {
        // Lấy danh sách các danh mục
        $categoryModel = new CategoryModel(getConnection());
        $categories = $categoryModel->getAll();

        // Lấy danh sách sản phẩm theo từng danh mục
        $productModel = new ProductModel();
        $productsByCategory = [];
        foreach ($categories as $category) {
            $productsByCategory[$category['Id']] = $productModel->getProductByCategoryId($category['Id']);
        }

        // Hiển thị view với dữ liệu sản phẩm theo từng danh mục
        require_once dirname(__FILE__) . '../../views/user/index.php';    
    }

    public function searchProducts()
    {
        if (isset($_REQUEST['search'])) {
            $searchTerm = $_POST['search'];

            // Tìm kiếm sản phẩm theo từ khóa $searchTerm
            $productModel = new ProductModel();
            $searchResults = $productModel->search($searchTerm);


            // Hiển thị view với kết quả tìm kiếm
            require_once dirname(__FILE__) . '../../views/user/search.php';    
             
        }  
    }

    public function test()
    {
        require_once 'views/user/test.php';
    }
}
