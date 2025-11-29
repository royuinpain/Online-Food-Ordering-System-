<?php
include '../_base.php';
//-----------------------------------------------------------------------------
// (1) Get search, sort, and paging parameters
$name = req('name');
$nameParam = "%$name%";

// (2) Fields for sorting
$fields = [
    'user_id'        => 'User ID',
    'user_name'      => 'Name',
    'order_id'       => 'Order ID',
    'order_datetime' => 'Date and Time',
    'order_count'    => 'Count',
    'order_total'    => 'Total (RM)',
];

// (3) Handle sort parameters
$sort = req('sort');
if (!key_exists($sort, $fields)) {
    $sort = 'user_id';
}

$dir = req('dir');
if (!in_array($dir, ['asc', 'desc'])) {
    $dir = 'asc';
}

$page = req('page', 1);

// (4) Filtered & sorted query with paging
require_once '../lib/SimplePager.php';
$sql = "SELECT o.id AS order_id, o.datetime AS order_datetime, o.count AS order_count, o.total AS order_total, u.id AS user_id, u.name AS user_name
        FROM `user` u
        JOIN `order` o ON u.id = o.user_id
        WHERE u.name LIKE ?
        ORDER BY $sort $dir";
$params = [$nameParam];

$p = new SimplePager($sql, $params, 10, $page);
$arr = $p->result;

$_title = 'Order List';
include '../_head.php';
?>
<link rel="stylesheet" href="/css/admin.css">

<!-- Main Content -->
<div class="container">
    <h2>Order List</h2>

    <!-- Search Form -->
    <form>
        <?= html_search('name') ?>
        <button>Search</button>
    </form>

    <p>
        <?= $p->count ?> of <?= $p->item_count ?> record(s) |
        Page <?= $p->page ?> of <?= $p->page_count ?>
    </p>

    <!-- Order Table -->
    <table class="table">
        <thead>
            <tr>
                <?php foreach ($fields as $key => $label): ?>
                    <th>
                        <a href="?name=<?= htmlspecialchars($name) ?>&sort=<?= $key ?>&dir=<?= ($sort == $key && $dir == 'asc') ? 'desc' : 'asc' ?>">
                            <?= $label ?> <?= ($sort == $key) ? ($dir == 'asc' ? '↑' : '↓') : '' ?>
                        </a>
                    </th>
                <?php endforeach; ?>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($arr as $s): ?>
            <tr>
                <td><?= htmlspecialchars($s->user_id) ?></td>
                <td><?= htmlspecialchars($s->user_name) ?></td>
                <td><?= htmlspecialchars($s->order_id) ?></td>
                <td><?= htmlspecialchars($s->order_datetime) ?></td>
                <td class="right"><?= htmlspecialchars($s->order_count) ?></td>
                <td class="right"><?= htmlspecialchars($s->order_total) ?></td>
                <td>
                    <a href="/order_listing/detail.php?id=<?= $s->order_id ?>">
                        <button type="button">Details</button>
                    </a>

                    <!-- Product Image Popup -->
                    <div class="popup">
                        <?php
                        $stm = $_db->prepare('
                            SELECT p.prod_img
                            FROM item AS i
                            JOIN product AS p ON i.product_id = p.prod_id
                            WHERE i.order_id = ?
                        ');
                        $stm->execute([$s->order_id]);
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
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="pagination">
        <?= $p->html("name=$name&sort=$sort&dir=$dir") ?>
    </div>
<br>
    <!-- Top 5 Best-Selling Items Button -->
    <a href="top5.php">
        <button>Top 5 Best-Selling Item</button>
    </a>
</div>

<?php include '../_foot.php'; ?>
