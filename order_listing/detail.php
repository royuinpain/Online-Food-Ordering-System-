<?php
include '../_base.php';

// ----------------------------------------------------------------------------
// (1) Get Order ID
$id = req('id');

// (2) Return order based on user role
if ($_user->role == 'Admin') {
    $stm = $_db->prepare('SELECT * FROM `order` WHERE id = ?');
    $stm->execute([$id]);
} else {
    $stm = $_db->prepare('SELECT * FROM `order` WHERE id = ? AND user_id = ?');
    $stm->execute([$id, $_user->id]);
}
$o = $stm->fetch();

if (!$o) redirect('history.php');

// (3) Return items (and products) belonging to the order
$stm = $_db->prepare('
    SELECT i.*, p.prod_name, p.prod_img
    FROM item AS i, product AS p
    WHERE i.product_id = p.prod_id
    AND i.order_id = ?
');
$stm->execute([$id]);
$arr = $stm->fetchAll();

// ----------------------------------------------------------------------------
$_title = 'Order | Detail';
include '../_head.php';
?>
<body>
<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #fffaf5;
        color: #333;
        margin: 0;
        padding: 0;
    }

    .order-container {
        max-width: 900px;
        margin: 60px auto;
        padding: 40px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
    }

    .order-header {
        text-align: center;
        margin-bottom: 30px;
    }

    .form {
        margin-bottom: 30px;
    }

    .form label {
        font-weight: bold;
        display: block;
        margin-top: 15px;
    }

    .form div, .form b {
        margin-top: 5px;
        display: block;
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

    .popup {
        width: 80px;
        height: 80px;
        object-fit: cover;
        margin-top: 5px;
        border-radius: 8px;
    }

    button {
        margin-top: 30px;
        padding: 12px 25px;
        border: none;
        background-color: #705222;
        color: white;
        border-radius: 6px;
        cursor: pointer;
        font-size: 16px;
    }

    button:hover {
        background-color: #5a411b;
    }
</style>
<div class="order-container">
    <div class="order-header">
        <h2>Order Detail</h2>
    </div>

    <form class="form">
        <label>Order Id</label>
        <b><?= $o->id ?></b>
    <br>
        <label>Datetime</label>
        <div><?= $o->datetime ?></div>
        <br>
        <label>Count</label>
        <div><?= $o->count ?></div>
        <br>
        <label>Total</label>
        <div>RM <?= $o->total ?></div>
    </form>

    <p><strong><?= count($arr) ?> item(s)</strong></p>

    <table class="table">
        <tr>
            <th>Product Id</th>
            <th>Product Name</th>
            <th>Price (RM)</th>
            <th>Unit</th>
            <th>Subtotal (RM)</th>
        </tr>

        <?php foreach ($arr as $i): ?>
        <tr>
            <td><?= $i->product_id ?></td>
            <td><?= $i->prod_name ?></td>
            <td class="right"><?= $i->price ?></td>
            <td class="right"><?= $i->unit ?></td>
            <td class="right">
                <?= $i->subtotal ?>
                <?php 
                $images = explode(',', $i->prod_img);
                $first_image = trim($images[0]);
                ?>
                <br>
                <img src="/product/product_images/<?= $first_image ?>" class="popup">
            </td>
        </tr>
        <?php endforeach; ?>

        <tr>
            <th colspan="3"></th>
            <th class="right"><?= $o->count ?></th>
            <th class="right"><?= $o->total ?></th>
        </tr>
    </table>

    <p style="text-align: center;">
        <button onclick="location.href='order_listing.php'">Back to Listing</button>
    </p>
</div>

</body>

<?php include '../_foot.php'; ?>
