<?php
require '../_base.php';
// ----------------------------------------------------------------------------

if (is_post()) {
$id = req('prod_id');
$stm = $_db -> prepare ('DELETE FROM product WHERE prod_id = ?');
$stm -> execute ([$id]);

temp ('info', 'Record deleted');
}
redirect('product_list.php');

