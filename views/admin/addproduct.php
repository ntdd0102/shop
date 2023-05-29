<?php

if (!isset($_SESSION['user']) || $_SESSION['user']['Role'] != 2) {
    header('Location: http://localhost/shop/index.php');
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

</head>

<body>
    <div class="container">
        <h2>Add Product</h2>

        <form action="/shop/controllers/ProductController.php?action=saveAddProduct" method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name">
                <small id="nameError" class="text-danger"></small>
            </div>

            <div class="form-group">
                <label for="image">Image:</label>
                <input type="file" class="form-control-file" id="image" name="image" onchange="displayImageURL(this)">
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description"></textarea>
            </div>

            <div class="form-group">
                <label for="category">Category:</label>
                <select class="form-control" id="category" name="category">
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?php echo $category['Id']; ?>"><?php echo $category['Name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="supplier">Supplier:</label>
                <select class="form-control" id="supplier" name="supplier">
                    <?php foreach ($suppliers as $supplier) : ?>
                        <option value="<?php echo $supplier['Id']; ?>"><?php echo $supplier['Name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" class="form-control" id="price" name="price">
            </div>

            <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="number" class="form-control" id="quantity" name="quantity">
            </div>

            <div class="form-group">
                <label for="is_visible">Visibility:</label>
                <select class="form-control" id="is_visible" name="is_visible">
                    <option value="1">Hiện</option>
                    <option value="0">Ẩn</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
    <script src="/shop/public/js/check_productname.js"></script>
</body>

</html>