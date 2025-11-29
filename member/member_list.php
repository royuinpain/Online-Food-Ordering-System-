<?php
include '../_base.php';
//-----------------------------------------------------------------------------
// Get search, sort, and paging parameters
$name = req('name');
$nameParam = "%$name%";

$fields = [
    'id'     => 'Id',
    'name'   => 'Name',
    'gender' => 'Gender',
];

$sort = req('sort');
key_exists($sort, $fields) || $sort = 'id';

$dir = req('dir');
in_array($dir, ['asc', 'desc']) || $dir = 'asc';

$page = req('page', 1);

// Filtered & sorted query with paging
require_once '../lib/SimplePager.php';
$sql = "SELECT * FROM user WHERE role = 'Member' AND name LIKE ? ORDER BY $sort $dir";
$params = [$nameParam];
$p = new SimplePager($sql, $params, 10, $page);
$arr = $p->result;
// ----------------------------------------------------------------------------
$_title = 'Member List';
include '../_head.php';
?>
<link rel="stylesheet" href="/css/admin.css">

<div class="container">
    <h2>Member List</h2>

    <form>
        <?= html_search('name') ?>
        <button>Search</button>
    </form>

    <p>
        <?= $p->count ?> of <?= $p->item_count ?> record(s) |
        Page <?= $p->page ?> of <?= $p->page_count ?>
    </p>

    <table class="table">
        <tr>
            <?= table_headers($fields, $sort, $dir, "name=$name&page=$page") ?>
            <th>Actions</th>
        </tr>

        <?php foreach ($arr as $s): ?>
        <tr>
            <td><?= $s->id ?></td>
            <td><?= $s->name ?></td>
            <td><?= $s->gender ?></td>
            <td>
                <button data-get="/member/member_detail.php?id=<?= $s->id ?>">Detail</button>
                <button data-get="/member/update_member.php?id=<?= $s->id ?>">Update</button>
                <button data-post="/member/delete_member.php?id=<?= $s->id ?>" data-confirm="Delete this record?">Delete</button>
            </td>
        </tr>
        <?php endforeach ?>
    </table>

    <br>

    <!-- Pagination -->
    <div class="pagination">
        <?= $p->html("name=$name&sort=$sort&dir=$dir") ?>
    </div>

    <br>

    <a href="/member/insert_member.php">
        <button>Insert Member</button>
    </a>
    <br>
</div>

<?php include '../_foot.php'; ?>
