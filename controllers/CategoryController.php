<?php
session_start();
require_once "../models/CategoryModel.php";

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'adminGetCategory') {

    $categoryController = new CategoryController();
    $categoryController->getCategory();
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'updateCategory') {
    $categoryModel = new CategoryModel();
    if ($categoryModel->isCategoryExists($_POST['name'])) {
        $categoryController = new CategoryController();
        $_SESSION['result'] = "Đã tồn tại loại sản phẩm này";
        $categoryController->getCategory();
    } else {
        $categoryController = new CategoryController();
        $categoryController->editCategory();
    }
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'addCategory') {
    $categoryModel = new CategoryModel();
    if ($categoryModel->isCategoryExists($_POST['name'])) {
        $categoryController = new CategoryController();
        $_SESSION['result'] = "Đã tồn tại loại sản phẩm này";
        $categoryController->getCategory();
    } else {
        $categoryController = new CategoryController();
        $categoryController->addCategory();
    }
}
class CategoryController
{
    public function getCategory()
    {
        $categoryModel = new CategoryModel();
        $categories = $categoryModel->getAll();

        $_SESSION['categories'] = $categories;

        header("Location: http://localhost/shop/views/admin/categoryAdmin.php");
        exit();
    }

    public function editCategory()
    {
        $id = $_GET['id'];
        $name = $_POST['name'];
        $categoryModel = new CategoryModel();
        $categoryModel->updateCategoryName($id, $name);
        $_SESSION['result'] = "Cập nhật tên loại sản phẩm thành công";
        $categoryController = new CategoryController();
        $categoryController->getCategory();
    }

    public function addCategory()
    {
        $name = $_POST['name'];
        $categoryModel = new CategoryModel();
        $categoryModel->addCategory($name);
        $_SESSION['result'] = "Thêm loại sản phẩm thành công";
        $categoryController = new CategoryController();
        $categoryController->getCategory();
    }
}
