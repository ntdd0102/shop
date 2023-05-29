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
    <title>Edit Order</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h2>Edit Order</h2>

        <?php if ($order) : ?>
            <form action="/shop/controllers/OrderController.php?action=saveEditOrder" method="POST">
                <input type="hidden" name="order_id" value="<?php echo $order['Id']; ?>">

                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $order['Name']; ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="phone">Phone:</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $order['Phone']; ?>" pattern="^\d+$" required>
                </div>



                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $order['Email']; ?>" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" title="Please enter a valid email address">

                </div>

                <div class="form-group">
                    <label for="customer_name">Customer Name:</label>
                    <input type="text" class="form-control" id="customer_name" name="customer_name" value="<?php echo $order['Customer_name']; ?>">
                </div>

                <div class="form-group">
                    <label for="delivery_address">Delivery Address:</label>
                    <textarea class="form-control" id="delivery_address" name="delivery_address"><?php echo $order['Delivery_address']; ?></textarea>
                </div>

                <div class="form-group">
                    <label for="date_created">Date Created:</label>
                    <input type="text" class="form-control" id="date_created" name="date_created" value="<?php echo $order['Date_created']; ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="date_updated">Date Updated:</label>
                    <input type="text" class="form-control" id="date_updated" name="date_updated" value="<?php echo $order['Date_updated']; ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="total_price">Total Price:</label>
                    <input type="number" class="form-control" id="total_price" name="total_price" value="<?php echo $order['Total_price']; ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="is_pay_onl">Is Pay Online:</label>
                    <select class="form-control" id="is_pay_onl" name="is_pay_onl" disabled>
                        <option value="1" <?php if ($order['Is_pay_onl'] == 1) echo 'selected'; ?>>Yes</option>
                        <option value="0" <?php if ($order['Is_pay_onl'] == 0) echo 'selected'; ?>>No</option>
                    </select>
                </div>


                <!-- ... -->
                <div class="form-group">
                    <label for="status">Status:</label>
                    <select class="form-control" id="status" name="status">
                        <option value="1" <?php if ($order['Status'] == 1) echo 'selected'; ?>>Chưa xác nhận</option>
                        <option value="2" <?php if ($order['Status'] == 2) echo 'selected'; ?>>Đã xác nhận</option>
                        <option value="3" <?php if ($order['Status'] == 3) echo 'selected'; ?>>Đang chuẩn bị hàng</option>
                        <option value="4" <?php if ($order['Status'] == 4) echo 'selected'; ?>>Đang giao hàng</option>
                        <option value="5" <?php if ($order['Status'] == 5) echo 'selected'; ?>>Hoàn thành</option>
                        <option value="6" <?php if ($order['Status'] == 6) echo 'selected'; ?>>Hủy</option>
                    </select>
                </div>
                <!-- ... -->


                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        <?php else : ?>
            <p>Order not found.</p>
        <?php endif; ?>

    </div>
</body>

</html>