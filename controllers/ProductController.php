<?php
session_start();


require_once __DIR__ . '/../models/ProductModel.php';
require_once __DIR__ . '/../models/CategoryModel.php';
require_once __DIR__ . '/../models/SupplierModel.php';


if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'searchProducts') {
    $productController = new ProductController();
    $productController->searchProducts();
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'adminGetProduct') {
    $productController = new ProductController();
    $productController->getProduct();
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'adminEditProduct') {
    $productController = new ProductController();
    $productController->beforEditProduct();
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'adminDelProduct') {
    $productController = new ProductController();
    $productController->delProduct();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'saveEditProduct') {
    $productController = new ProductController();
    $productController->editProduct();
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'adminAddProduct') {
    $productController = new ProductController();
    $productController->beforAddProduct();
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'checkProductName') {
    $productController = new ProductController();
    $productController->checkProductName();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['action']) && $_GET['action'] === 'saveAddProduct') {
    $productController = new ProductController();
    $productController->addProduct();
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

    public function getProduct()
    {
        // Tạo đối tượng ProductModel
        $productModel = new ProductModel();

        // Gọi phương thức để lấy tất cả sản phẩm
        $products = $productModel->getAllProducts();

        $categoryModel = new CategoryModel();
        $categories = $categoryModel->getAll();
        // Lưu dữ liệu vào session
        $_SESSION['products'] = $products;
        $_SESSION['categories'] = $categories;

        // Chuyển hướng đến trang productAdmin.php
        header('Location: http://localhost/shop/views/admin/productAdmin.php');
        exit();
    }

    function processImageUpload($categoryId)
    {
        $imagePath = null;

        // Xử lý và lưu hình ảnh vào thư mục
        $categoryModel = new CategoryModel();

        if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $image = $_FILES['image'];

            $basePath = '/shop/public/image/';
            $categoryFolder = $categoryModel->getCategoryNameById($categoryId);
            $targetFolder = $basePath . $categoryFolder . '/';

            if (!file_exists($targetFolder)) {
                mkdir($targetFolder, 0777, true);
            }

            $imageName = $image['name'];
            $targetPath = $targetFolder . $imageName;

            if (move_uploaded_file($image['tmp_name'], $targetPath)) {
                $imagePath = $targetPath;
            } else {
                // Xử lý lỗi khi lưu hình ảnh thất bại
                echo 'Failed to upload image.';
                return null;
            }
        }

        return $imagePath;
    }
    public function addProduct()
    {
        $productModel = new ProductModel();

        // Lấy dữ liệu từ form
        $name = $_POST['name'];
        $description = $_POST['description'];
        $categoryId = $_POST['category'];
        $supplierId = $_POST['supplier'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];
        $isVisible = $_POST['is_visible'];

        // Xử lý hình ảnh (nếu có)
        $imagePath = $this->processImageUpload($categoryId);

        // Thêm sản phẩm vào cơ sở dữ liệu
        $productId = $productModel->addProduct($name, $imagePath, $description, $categoryId, $supplierId, $price, $quantity, $isVisible);

        // Kiểm tra kết quả thêm
        if ($productId !== false) {
            // Thêm thành công
            // Redirect hoặc thông báo thành công cho người dùng
            //var_dump($productId);
            $_SESSION['activityAdmin'] = "Thêm sản phẩm thành công";
            $productController = new ProductController();
            $productController->getProduct();
        } else {
            // Thêm thất bại
            // Xử lý lỗi, redirect hoặc thông báo cho người dùng
            echo 'Add product failed';
        }
    }





    public function editProduct()
    {
        $productModel = new ProductModel();

        // Lấy dữ liệu từ form
        $productId = $_POST['product_id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $categoryId = $_POST['category'];
        $supplierId = $_POST['supplier'];
        $price = $_POST['price'];
        $quantity = $_POST['quantity'];
        $isVisible = $_POST['is_visible'];

        // Xử lý hình ảnh (nếu có)
        $imagePath = null;

        if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            $image = $_FILES['image'];

            // Xử lý và lưu hình ảnh vào thư mục
            $categoryModel = new CategoryModel();
            $basePath = '/shop/public/image/';
            $categoryFolder = $categoryModel->getCategoryNameById($categoryId);
            $targetFolder = $basePath . $categoryFolder . '/';

            if (!file_exists($targetFolder)) {
                mkdir($targetFolder, 0777, true);
            }

            $imageName = $image['name'];
            $targetPath = $targetFolder . $imageName;

            if (move_uploaded_file($image['tmp_name'], $targetPath)) {
                $imagePath = $targetPath;
            } else {
                // Xử lý lỗi khi lưu hình ảnh thất bại
                echo 'Failed to upload image.';
                return;
            }
        }

        // Tạo mảng dữ liệu sản phẩm cần cập nhật
        $productData = [
            'name' => $name,
            'description' => $description,
            'category_id' => $categoryId,
            'supplier_id' => $supplierId,
            'price' => $price,
            'quantity' => $quantity,
            'is_visible' => $isVisible
        ];

        // Kiểm tra và thêm 'image' vào mảng dữ liệu nếu có hình ảnh mới
        if ($imagePath !== null) {
            $productData['image'] = $imagePath;
        } else {
            // Nếu không có hình ảnh mới, không cần cập nhật trường 'image'
            unset($productData['image']);
        }

        // Cập nhật thông tin sản phẩm trong cơ sở dữ liệu
        $rowCount = $productModel->updateProduct($productId, $productData);

        // Kiểm tra kết quả cập nhật
        if ($rowCount >= 0) {
            if ($rowCount > 0) {
                // Cập nhật thành công
                // Redirect hoặc thông báo thành công cho người dùng
                $_SESSION['activityAdmin'] = "Cập nhật sản phẩm thành công";
                $productController = new ProductController();
                $productController->getProduct();
            } else {
                // Không có sự thay đổi dữ liệu
                // Xử lý thông báo hoặc redirect người dùng
                $_SESSION['activityAdmin'] = "Cập nhật sản phẩm thành công";
                $productController = new ProductController();
                $productController->getProduct();
            }
        } else {
            // Cập nhật thất bại
            // Xử lý lỗi, redirect hoặc thông báo cho người dùng
            echo 'Update failed';
        }
    }

    public function beforEditProduct()
    {
        // Kiểm tra xem có ID sản phẩm được truyền vào hay không
        if (isset($_GET['id'])) {
            $product_id = $_GET['id'];

            // Tạo đối tượng ProductModel
            $productModel = new ProductModel();

            // Gọi phương thức getProductById để lấy thông tin sản phẩm theo ID
            $product = $productModel->getProductById($product_id);

            $categoryModel = new CategoryModel();
            $categories = $categoryModel->getAll();

            $supplierModel = new SupplierModel();
            $suppliers = $supplierModel->getAll();

            // Kiểm tra xem sản phẩm có tồn tại hay không
            //var_dump($product);
            if ($product) {
                // Chuyển hướng đến trang xem (view) và truyền thông tin sản phẩm
                require_once dirname(__FILE__) . '../../views/admin/editproduct.php';
            }
        }
    }

    public function beforAddProduct()
    {
        $categoryModel = new CategoryModel();
        $categories = $categoryModel->getAll();

        $supplierModel = new SupplierModel();
        $suppliers = $supplierModel->getAll();

        require_once dirname(__FILE__) . '../../views/admin/addproduct.php';
    }

    public function delProduct()
    {
        $product_id = $_GET['id'];
        echo $product_id;
        // Tạo đối tượng ProductModel
        $productModel = new ProductModel();

        // Gọi hàm deleteProduct trong ProductModel để chuyển thuộc tính is_visible thành 0
        $productModel->deleteProduct($product_id);
        $_SESSION['activityAdmin'] = "Xóa sản phẩm thành công";
        $productController = new ProductController();
        $productController->getProduct();
    }

    public function checkProductName()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
            $name = $_POST['name'];

            // Kiểm tra tên sản phẩm có tồn tại hay không
            $productModel = new ProductModel();
            $isExists = $productModel->checkProductNameExists($name);

            if ($isExists) {
                // Tên sản phẩm đã tồn tại, trả về thông báo lỗi cho trình duyệt
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Tên sản phẩm đã tồn tại']);
                exit();
            }

            // Tên sản phẩm không bị trùng, trả về thông báo thành công cho trình duyệt
            header('Content-Type: application/json');
            echo json_encode(['success' => 'Tên sản phẩm hợp lệ']);
            exit();
        }
    }
}
