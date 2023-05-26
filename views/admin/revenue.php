<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Revenue and Product Sales</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <div>
        <canvas id="revenueChart"></canvas>
    </div>

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
        var productSalesLabels = <?php echo json_encode($productSalesLabels); ?>;
        var productSalesData = <?php echo json_encode($productSalesData); ?>;

        var productSalesCtx = document.getElementById('productSalesChart').getContext('2d');
        var productSalesChart = new Chart(productSalesCtx, {
            type: 'bar',
            data: {
                labels: productSalesLabels,
                datasets: [{
                    label: 'Product Sales',
                    data: productSalesData,
                    backgroundColor: 'rgba(255, 99, 132, 0.5)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>

</html>