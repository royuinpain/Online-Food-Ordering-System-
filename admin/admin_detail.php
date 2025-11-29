<?php
require '../_base.php';
//-----------------------------------------------------------------------------

// TODO
$id = req('id');

$stm = $_db -> prepare('SELECT * FROM user WHERE id = ?');
$stm -> execute ([$id]);
$s = $stm -> fetch();

if (!$s) {
    redirect ('admin_list.php');
}

// ----------------------------------------------------------------------------
$_title = 'Detail';
include '../_head.php';
?>

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

<table class="table detail">
    <tr>
        <th>Id</th>
        <td><?= $s -> id ?></td>
    </tr>
    <tr>
        <th>Email</th>
        <td><?= $s -> email ?></td>
    </tr>
    <tr>
        <th>Name</th>
        <td><?= $s -> name  ?></td>
    </tr>
    <tr>
        <th>Gender</th>
        <td><?= $s -> gender  ?></td>
    </tr>
    <tr>
        <th>Photo</th>
        <td>
        <img src="/user_photos/<?= htmlspecialchars($s->photo) ?>" id="photo">
        </td>
 
    </tr>
</table>

<br>

<button data-get="admin_list.php">Admin List</button>

<?php
include '../_foot.php';