<?php
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['Role'] != 2) {
    header('Location: http://localhost/shop/index.php');
}
if (isset($_SESSION['categories'])) {
    $categories = $_SESSION['categories'];
}
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
                            <?php foreach ($categories as $category) : ?>
                                <tr>
                                    <td><?php echo $category['Id']; ?></td>
                                    <td>
                                        <form action="/shop/controllers/CategoryController.php?action=updateCategory&id=<?php echo $category['Id']; ?>" method="POST">
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="name" value="<?php echo $category['Name']; ?>">
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
                    <form action="/shop/controllers/CategoryController.php?action=addCategory" method="POST">
                        <div class="form-group">
                            <label for="name">Category Name</label>
                            <input type="text" class="form-control" id="name" name="name" required pattern="[^\d]+" title="Vui lòng không nhập số">

                        </div>
                        <button type="submit" class="btn btn-primary">Add Category</button>
                    </form>
                </div>

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