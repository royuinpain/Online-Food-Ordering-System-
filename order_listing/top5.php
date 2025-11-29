<?php
include '../_base.php'; // or correct relative path to where $db9 is defined
$_title = 'Top 5 Best-Selling Products';
include '../_head.php';


// Query for Top 5 Best-Selling Products using db9
$sqlBestSelling = "
    SELECT p.prod_name, SUM(i.unit) AS total_sold
    FROM item i
    JOIN product p ON i.product_id = p.prod_id
    GROUP BY p.prod_name
    ORDER BY total_sold DESC
    LIMIT 5
";
$stm = $_db->query($sqlBestSelling);
$bestSellingProducts = $stm->fetchAll(PDO::FETCH_ASSOC);

$labels = [];
$data = [];

foreach ($bestSellingProducts as $product) {
    $labels[] = $product['prod_name'];
    $data[] = (int)$product['total_sold'];
}
?>

<!-- Chart.js for Best Selling Products -->
<div style="width: 80%; margin: 0 auto;">

    <canvas id="bestSellingChart"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('bestSellingChart').getContext('2d');
    var bestSellingChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [{
                label: 'Total Sold',
                data: <?= json_encode($data) ?>,
                backgroundColor: 'rgba(119, 80, 13, 0.2)',
                borderColor: 'rgb(123, 74, 14)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Units Sold'
                    }
                }
            }
        }
    });
</script>

<a href="order_listing.php">
<style>
button {
    margin-top: 10px;
    padding: 8px 16px;
    border: none;
    background-color: #705222;
    color: white;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
}
</style>
    <button>Back</button>
</a>

<?php include '../_foot.php'; ?>

