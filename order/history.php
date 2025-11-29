<?php
include '../_base.php';

// ----------------------------------------------------------------------------
// (1) Authorization (member)
auth('Member');

// (2) Retrieve orders belonging to the user (sorted in descending order)
$stm = $_db->prepare('
    SELECT * FROM `order` WHERE user_id = ?
    ORDER BY id DESC
');
$stm->execute([$_user->id]);
$orders = $stm->fetchAll(PDO::FETCH_ASSOC);

// ----------------------------------------------------------------------------

$_title = 'Order | History';
include '../_head.php';
?>

<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #fffaf5;
        color: #333;
        margin: 0;
        padding: 0;
    }

    .history-container {
        max-width: 900px;
        margin: 60px auto;
        padding: 40px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
    }

    .history-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .table th, .table td {
        border: 1px solid #ddd;
        padding: 10px;
        text-align: center;
    }

    .table th {
        background-color: #f3ede5;
        color: #705222;
    }

    .right {
        text-align: right;
    }

    button {
        margin: 5px 0;
        padding: 8px 16px;
        border: none;
        background-color: #705222;
        color: white;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
    }

    button:hover {
        background-color: #5a411b;
    }

    /* (B) Popup for product images */
    .popup {
        display: none;
        position: absolute;
        background: white;
        border: 1px solid #ccc;
        padding: 5px;
        z-index: 10;
        border-radius: 8px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    tr:hover .popup {
        display: flex !important;
        flex-wrap: wrap;
        gap: 5px;
    }

    .popup img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 5px;
        outline: 1px solid #333;
    }
</style>

<div class="history-container">
    <div class="history-header">
        <h2>Order History</h2>
    </div>
    
    <p><strong><?= count($orders) ?> record(s)</strong></p>

    <table class="table">
        <tr>
            <th>Id</th>
            <th>Datetime</th>
            <th>Count</th>
            <th>Total (RM)</th>
            <th>Product</th>
        </tr>

        <?php foreach ($orders as $order): ?>
        <tr>
            <td><?= $order['id'] ?></td>
            <td><?= $order['datetime'] ?></td>
            <td class="right"><?= $order['count'] ?></td>
            <td class="right"><?= $order['total'] ?></td>
            <td>
                <button data-get="detail.php?id=<?= $order['id'] ?>">Details</button>
                
                <!-- Fetch and Display First Image of Each Product in the Order -->
                <div class="popup">
                    <?php 
                    $stm = $_db->prepare('
                        SELECT p.prod_img
                        FROM item AS i
                        JOIN product AS p ON i.product_id = p.prod_id
                        WHERE i.order_id = ?
                    ');
                    $stm->execute([$order['id']]);
                    $prod_imgs = $stm->fetchAll(PDO::FETCH_COLUMN);
                    
                    foreach ($prod_imgs as $prod_img) {
                        $images = explode(',', $prod_img);
                        $first_image = trim($images[0]);
                        echo "<img src='/product/product_images/" . htmlspecialchars($first_image) . "'>";
                    }
                    ?>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php
include '../_foot.php';
?>
