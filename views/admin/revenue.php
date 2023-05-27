<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Revenue and Product Sales</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-labels@1.1.0/src/chartjs-plugin-labels.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
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

    #productSalesChart {
        max-width: 900px;
        width: auto;
        height: 500px;
        margin: 0 auto;
    }

    #revenueChart {
        max-width: 900px;
        width: auto;
        height: 500px;
        margin: 0 auto;
    }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark col-md-12">
        <a class="navbar-brand" href="#">Admin Panel</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" href="/shop/views/admin/hello.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"
                        href="/shop/controllers/ProductController.php?action=adminGetProduct">Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/shop/controllers/OrderController.php?action=adminGetOrder">Orders</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/shop/controllers/OrderController.php?action=revenue">Revenue</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"
                        href="/shop/controllers/CategoryController.php?action=adminGetCategory">Category</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link"
                        href="/shop/controllers/SupplierController.php?action=adminGetSupplier">Supplier</a>
                </li>
            </ul>
        </div>
    </nav>
    <h3>Doanh thu theo tháng</h3>
    <div>
        <canvas id="revenueChart"></canvas>
    </div>
    <h3>Các sản phẩm đã bán trong tháng</h3>
    <div>
        <canvas id="productSalesChart"></canvas>
    </div>

    <script>
    // Revenue chart
    var revenueLabels = <?php echo json_encode($revenueLabels); ?>;
    var revenueData = <?php echo json_encode($revenueData); ?>;

    var revenueCtx = document.getElementById('revenueChart').getContext('2d');
    var revenueChart = new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: revenueLabels,
            datasets: [{
                label: 'Monthly Revenue',
                data: revenueData,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value, index, values) {
                            return value.toLocaleString('en-US', {
                                style: 'currency',
                                currency: 'USD'
                            });
                        }
                    }
                }
            }
        }
    });

    // Product sales chart
    // Product sales chart
    var productSalesLabels = <?php echo json_encode($productSalesLabels); ?>;
    var productSalesData = <?php echo json_encode($productSalesData); ?>;
    var totalSales = productSalesData.reduce((a, b) => a + b, 0); // Tính tổng số sản phẩm bán được

    var productSalesCtx = document.getElementById('productSalesChart').getContext('2d');
    var productSalesChart = new Chart(productSalesCtx, {
        type: 'pie',
        data: {
            labels: productSalesLabels,
            datasets: [{
                label: 'Product Sales',
                data: productSalesData,
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#C70039',
                    '#9A12B3',
                    '#3B3F8F',
                    '#42A5F5',
                    '#FF5252',
                    '#FFB300',
                    '#4CAF50',
                    '#FF9800',
                    '#795548',
                    '#8BC34A',
                    '#607D8B',
                    '#9C27B0',
                    '#009688',
                    '#FF5722',
                    '#FFC107',
                    '#673AB7',
                    '#E91E63',
                    // Add more colors if needed
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                labels: {
                    render: 'percentage',
                    fontColor: '#fff',
                    precision: 2,
                    // Hiển thị phần trăm bên ngoài các phần tử của biểu đồ
                    outsidePadding: 4,
                    // Định dạng văn bản hiển thị phần trăm
                    textMargin: 4,
                    fontSize: 12,
                    fontStyle: 'bold',
                    // Tính phần trăm dựa trên tổng số sản phẩm bán được
                    // và hiển thị cả giá trị thực tế của sản phẩm
                    overlap: true,
                    formatter: function(value, context) {
                        var percentage = value.toFixed(2);
                        var productIndex = context.dataIndex;
                        var productSales = productSalesData[productIndex];
                        var totalPercentage = ((productSales / totalSales) * 100).toFixed(2);
                        return totalPercentage + '% (' + productSales + ')';
                    }
                }
            }
        }

    });
    </script>
</body>

</html>