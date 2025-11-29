<?php
include '../_base.php';

$_title = 'Wishlist';
include '../_head.php';

if (!$_user) {
    redirect('/login.php'); // force login
}

$user_id = $_user->id;

$stm = $_db->prepare("SELECT product.* FROM wishlist 
                      JOIN product ON wishlist.product_id = product.prod_id 
                      WHERE wishlist.user_id = ?");
$stm->execute([$user_id]);
$products = $stm->fetchAll();
?>

<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #fffaf5;
        color: #333;
        margin: 0;
        padding: 0;
    }

    .wishlist-container {
        max-width: 900px;
        margin: 60px auto;
        padding: 40px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
        text-align: center;
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

    img {
        border-radius: 8px;
        width: 50px;
        height: 50px;
        object-fit: cover;
    }

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

    button:hover {
        background-color: #5a411b;
    }

    form {
        display: inline-block;
    }

    .empty-message {
        font-size: 18px;
        margin-bottom: 20px;
    }
</style>

<div class="wishlist-container">
    <h2>My Wishlist</h2>

    <?php if (empty($products)): ?>
        <p class="empty-message">Your wishlist is empty.</p>
    <?php else: ?>
        <table class="table">
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Price (RM)</th>
                <th>Action</th>
            </tr>
            <?php foreach ($products as $p): 
                $images = explode(',', $p->prod_img);
                $firstImage = $images[0] ?? 'default.jpg';
            ?>
            <tr>
                <td><img src="/product/product_images/<?= htmlspecialchars($firstImage) ?>" alt="Product Image"></td>
                <td><?= htmlspecialchars($p->prod_name) ?></td>
                <td>RM <?= htmlspecialchars($p->prod_price) ?></td>
                <td>
                    <form method="post" action="remove_wishlist.php">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($p->prod_id) ?>">
                        <button type="submit">Remove ❌</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <a href="list.php">
        <button style="margin-top: 30px;">Back to Products</button>
    </a>
</div>

<?php include '../_foot.php'; ?>
