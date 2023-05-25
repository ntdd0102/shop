<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h2>Edit Product</h2>

        <?php if ($product) : ?>
        <form action="/shop/controllers/ProductController.php?action=saveEditProduct" method="POST"
            enctype="multipart/form-data">
            <input type="hidden" name="product_id" value="<?php echo $product['Id']; ?>">

            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $product['Name']; ?>"
                    readonly>
            </div>

            <div class="form-group">
                <label for="image">Image:</label>
                <input type="file" class="form-control-file" id="image" name="image" onchange="displayImageURL(this)">

            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description"
                    name="description"><?php echo $product['Description']; ?></textarea>
            </div>

            <div class="form-group">
                <label for="category">Category:</label>
                <select class="form-control" id="category" name="category">
                    <?php foreach ($categories as $category) : ?>
                    <?php if ($product['Category_id'] == $category['Id']) : ?>
                    <option value="<?php echo $category['Id']; ?>" selected><?php echo $category['Name']; ?></option>
                    <?php else : ?>
                    <option value="<?php echo $category['Id']; ?>"><?php echo $category['Name']; ?></option>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </select>

            </div>

            <div class="form-group">
                <label for="supplier">Supplier:</label>
                <select class="form-control" id="supplier" name="supplier">
                    <?php foreach ($suppliers as $supplier) : ?>
                    <?php if ($product['Supplier_id'] == $supplier['Id']) : ?>
                    <option value="<?php echo $supplier['Id']; ?>" selected><?php echo $supplier['Name']; ?></option>
                    <?php else : ?>
                    <option value="<?php echo $supplier['Id']; ?>"><?php echo $supplier['Name']; ?></option>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </select>

            </div>

            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" class="form-control" id="price" name="price"
                    value="<?php echo $product['Price']; ?>">
            </div>

            <div class="form-group">
                <label for="quantity">Quantity:</label>
                <input type="number" class="form-control" id="quantity" name="quantity"
                    value="<?php echo $product['quantity']; ?>">
            </div>

            <div class="form-group">
                <label for="is_visible">Visibility:</label>
                <select class="form-control" id="is_visible" name="is_visible">
                    <option value="1" <?php if ($product['is_visible'] == 1) echo 'selected'; ?>>Hiện</option>
                    <option value="0" <?php if ($product['is_visible'] == 0) echo 'selected'; ?>>Ẩn</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
        </form>
        <?php else : ?>
        <p>Product not found.</p>
        <?php endif; ?>

    </div>
</body>

</html>