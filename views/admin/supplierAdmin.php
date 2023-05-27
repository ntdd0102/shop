<?php
session_start();
if (isset($_SESSION['supplier'])) {
    $suppliers = $_SESSION['supplier'];
}

// Định nghĩa số nhà cung cấp hiển thị trên mỗi trang
$perPage = 5;

// Tính toán số trang dựa trên tổng số nhà cung cấp và số nhà cung cấp trên mỗi trang
$totalSuppliers = count($suppliers);
$totalPages = ceil($totalSuppliers / $perPage);

// Xác định trang hiện tại (mặc định là trang đầu tiên nếu không được chỉ định)
$currentpage = isset($_GET['page']) ? $_GET['page'] : 1;

// Xác định vị trí bắt đầu và kết thúc của nhà cung cấp trong dữ liệu tổng cộng
$start = ($currentpage - 1) * $perPage;
$end = $start + $perPage;

// Giới hạn mảng nhà cung cấp dựa trên vị trí bắt đầu và kết thúc
$paginatedSuppliers = array_slice($suppliers, $start, $perPage);
?>

<!doctype html>
<html lang="en">

<head>
    <title>Admin Panel</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .admin-sidebar {
            background-color: #343a40;
            color: #fff;
            min-height: 100vh;
            padding-top: 20px;
        }

        .admin-sidebar h3 {
            font-size: 24px;
            text-align: center;
            margin-bottom: 40px;
        }

        .admin-sidebar .nav-link {
            color: #fff;
            padding: 10px;
        }

        .admin-sidebar .nav-link:hover {
            background-color: #555;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 admin-sidebar">
                <h3>Admin Panel</h3>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="/shop/views/admin/hello.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/shop/controllers/ProductController.php?action=adminGetProduct">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/shop/controllers/OrderController.php?action=adminGetOrder">Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/shop/controllers/OrderController.php?action=revenue">Revenue</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/shop/controllers/CategoryController.php?action=adminGetCategory">Category</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/shop/controllers/SupplierController.php?action=adminGetSupplier">Supplier</a>
                    </li>
                </ul>
            </div>
            <div class="col-md-10">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($paginatedSuppliers as $supplier) : ?>
                                <tr>
                                    <td><?php echo $supplier['Id']; ?></td>
                                    <td>
                                        <form action="/shop/controllers/SupplierController.php?action=updateSupplier&id=<?php echo $supplier['Id']; ?>" method="POST">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="name" value="<?php echo $supplier['Name']; ?>">
                                                <div class="input-group-append">
                                                    <button type="submit" class="btn btn-primary">Edit</button>
                                                </div>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mb-3">
                    <form action="/shop/controllers/SupplierController.php?action=addSupplier" method="POST">
                        <div class="form-group">
                            <label for="name">Supplier Name</label>
                            <input type="text" class="form-control" id="name" name="name" required pattern="[^\d]+" title="Vui lòng không nhập số">

                        </div>
                        <button type="submit" class="btn btn-primary">Add Supplier</button>
                    </form>
                </div>

                <!-- Phân trang -->
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <?php for ($page = 1; $page <= $totalPages; $page++) : ?>
                            <li class="page-item <?php echo ($page == $currentpage) ? 'active' : ''; ?>">
                                <a class="page-link" href="/shop/views/admin/supplierAdmin.php?page=<?php echo $page; ?>"><?php echo $page; ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>

                <?php if (isset($_SESSION['result'])) : ?>
                    <div id="resultMessage" class="alert alert-success" role="alert">
                        <?php echo $_SESSION['result']; ?>
                    </div>
                    <script>
                        setTimeout(function() {
                            document.getElementById("resultMessage").style.display = "none";
                        }, 10000);
                    </script>
                    <?php unset($_SESSION['result']); ?>
                <?php endif; ?>

            </div>

        </div>

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
        </script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
        </script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ