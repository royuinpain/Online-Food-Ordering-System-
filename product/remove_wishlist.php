<?php
include '../_base.php';

if (!$_user) {
    redirect('/login.php');
}

$user_id = $_user->id;
$product_id = req('id');

if ($product_id) {
    $stm = $_db->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
    $stm->execute([$user_id, $product_id]);
}

redirect('wishlist.php');
