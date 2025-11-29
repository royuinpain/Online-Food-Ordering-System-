<?php
include '../_base.php';
// -----------------------------------------------------------------------------
// Get search, sort, and paging parameters
$name = req('name');
$nameParam = "%$name%";

$fields = [
    'prod_id'    => 'ID',
    'prod_name'  => 'Name',
    'prod_price' => 'Price',
    'prod_cat'   => 'Category',
];

$sort = req('sort');
key_exists($sort, $fields) || $sort = 'prod_id';

$dir = req('dir');
in_array($dir, ['asc', 'desc']) || $dir = 'asc';

$page = req('page', 1);

// Filtered & sorted query with paging
require_once '../lib/SimplePager.php';
$sql = "SELECT * FROM product WHERE prod_name LIKE ? ORDER BY $sort $dir";
$params = [$nameParam];
$p = new SimplePager($sql, $params, 10, $page);
$arr = $p->result;

// -----------------------------------------------------------------------------
// Page title and header
$_title = 'Product List';
include '../_head.php';
?>
    <link rel="stylesheet" href="/css/admin.css">

<!-- Main Content -->
<div class="container">
    <h2>Product List</h2>

    <!-- Search Form -->
    <form>
        <?= html_search('name', 'placeholder="Search by name"') ?>
        <button>Search</button>
    </form>

    <p>
        <?= $p->count ?> of <?= $p->item_count ?> record(s) |
        Page <?= $p->page ?> of <?= $p->page_count ?>
    </p>

    <!-- Product Table -->
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
                <td><?= htmlspecialchars($s->prod_id) ?></td>
                <td><?= htmlspecialchars($s->prod_name) ?></td>
                <td><?= htmlspecialchars($s->prod_price) ?></td>
                <td><?= htmlspecialchars($s->prod_cat) ?></td>
                <td>
                    <button data-get="/adminproduct/detail.php?prod_id=<?= ($s->prod_id) ?>">Detail</button>
                    <button data-get="/adminproduct/update.php?prod_id=<?= ($s->prod_id) ?>">Update</button>
                    <button data-post="/adminproduct/delete.php?prod_id=<?= ($s->prod_id) ?>" data-confirm="Delete this record?">Delete</button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="pagination">
        <?= $p->html("name=$name&sort=$sort&dir=$dir") ?>
    </div>

    <!-- Insert Product Button -->
    <div style="margin-top: 1rem;">
        <a href="/adminproduct/insert.php">
            <button>Insert Product</button>
        </a>
        <br>
    </div>
</div>

<?php include '../_foot.php'; ?>
