<?php
session_start();
require_once "../models/SupplierModel.php";

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'adminGetSupplier') {

    $supplierController = new SupplierController();
    $supplierController->getSupplier();
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'updateSupplier') {
    $supplierModel = new SupplierModel();
    if ($supplierModel->isSupplierExists($_POST['name'])) {
        $supplierController = new SupplierController();
        $_SESSION['result'] = "Đã tồn tại loại sản phẩm này";
        $supplierController->getSupplier();
    } else {
        $supplierController = new SupplierController();
        $supplierController->editSupplier();
    }
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'addSupplier') {
    $supplierModel = new SupplierModel();
    if ($supplierModel->isSupplierExists($_POST['name'])) {
        $supplierController = new SupplierController();
        $_SESSION['result'] = "Đã tồn tại loại sản phẩm này";
        $supplierController->getSupplier();
    } else {
        $supplierController = new SupplierController();
        $supplierController->addSupplier();
    }
}

class SupplierController
{
    public function getSupplier()
    {
        $supplierModel = new SupplierModel();
        $supplier = $supplierModel->getAll();

        $_SESSION['supplier'] = $supplier;

        header("Location: http://localhost/shop/views/admin/supplierAdmin.php");
        exit();
    }

    public function editSupplier()
    {
        $id = $_GET['id'];
        $name = $_POST['name'];
        $supplierModel = new SupplierModel();
        $supplierModel->updateSupplierName($id, $name);
        $_SESSION['result'] = "Cập nhật tên nhà sản xuất thành công";
        $supplierController = new SupplierController();
        $supplierController->getSupplier();
    }

    public function addSupplier()
    {
        $name = $_POST['name'];
        $supplierModel = new SupplierModel();
        $supplierModel->addSupplier($name);
        $_SESSION['result'] = "Thêm loại sản phẩm thành công";
        $supplierController = new SupplierController();
        $supplierController->getSupplier();
    }
}
