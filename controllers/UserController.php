<?php
require_once __DIR__ . '/../models/UserModel.php';
require_once "../models/OrderModel.php";

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'login') {
    $userController = new UserController();
    $userController->checklogin();
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'logout') {
    $userController = new UserController();
    $userController->logout();
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'register') {
    $userController = new UserController();
    $userController->register();
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'check_username') {
    $userController = new UserController();
    $userController->checkUsername();
}

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'update_account') {
    $userController = new UserController();
    $userController->updateAccount();
}
class UserController
{
    public function checklogin()
    {
        // Lấy dữ liệu từ form đăng nhập
        $username = $_REQUEST['username'];
        $password = $_REQUEST['password'];

        // Tạo đối tượng UserModel và gọi hàm checklogin để kiểm tra thông tin đăng nhập
        $user_model = new UserModel();
        $user = $user_model->checklogin($username, $password);

        session_start();
        if (!empty($user)) {

            $_SESSION['user'] = $user;
            if ($user['Role'] == 1) {
                header('Location: /shop/index.php');
                exit();
            } else if ($user['Role'] == 2) {
                $order_model = new OrderModel();
                $new_orders_count = $order_model->getNewOrdersCount();
                // Lưu số lượng đơn hàng mới vào session
                $_SESSION['new_orders_count'] = $new_orders_count;
                header('Location: /shop/views/admin/hello.php');
                exit();
            }
        } else {

            $_SESSION['login_message'] = 'Đăng nhập thất bại';
            header('Location: /shop/views/login.php');
            exit();
        }
    }

    public function logout()
    {
        session_start();
        unset($_SESSION['user']);
        unset($_SESSION['cart']);
        header('Location: /shop/index.php');
        exit();
    }

    public function register()
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $name = $_POST['name'];
        $address = $_POST['address'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        $User = new UserModel();
        $newUser = $User->addUser($username, $password, $name, $address, $email, $phone);

        if ($newUser) {
            // Chuyển hướng về trang đăng nhập với thông báo tạo tài khoản thành công
            header('location: /shop/views/login.php?success=1');
        } else {
            // Ở lại trang đăng ký với thông báo tạo tài khoản thất bại
            header('location: /shop/views/user/register.php?error=1');
        }
    }

    public function checkUsername()
    {
        $username = $_POST['username'];

        // Thực hiện truy vấn kiểm tra username trong cơ sở dữ liệu
        // Nếu username tồn tại, trả về kết quả là true, ngược lại là false
        $User = new UserModel();
        $exists = $User->checkUsernameExists($username);

        // Trả về kết quả dưới dạng JSON
        header('Content-Type: application/json');
        echo json_encode(['exists' => $exists]);
        die();
    }

    public function updateAccount()
    {
        session_start();
        if (!isset($_SESSION['user'])) {
            // Chưa đăng nhập, không có quyền truy cập
            header('Location: /shop/views/login.php');
            exit();
        }

        // Lấy thông tin tài khoản từ session
        $user = $_SESSION['user'];
        $id = $user['Id'];
        $name = $_POST['name'];
        $address = $_POST['address'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        // Tạo đối tượng UserModel và gọi hàm updateUser để cập nhật thông tin
        $userModel = new UserModel();
        $result = $userModel->updateUser($id, $name, $address, $email, $phone);

        if ($result) {
            // Cập nhật thành công, chuyển hướng về trang hiển thị thông tin tài khoản
            $_SESSION['user']['Name'] = $name;
            $_SESSION['user']['Address'] = $address;
            $_SESSION['user']['Email'] = $email;
            $_SESSION['user']['Phone'] = $phone;
            header('Location: /shop/views/account.php?success=1');
            exit();
        } else {
            // Cập nhật thất bại, thông báo lỗi
            header('Location: /shop/views/update_account.php?error=1');
            exit();
        }
    }
}
