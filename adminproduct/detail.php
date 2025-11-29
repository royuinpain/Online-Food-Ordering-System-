<?php
require '../_base.php';
// -----------------------------------------------------------------------------

$id = req('prod_id');

$stm = $_db->prepare('SELECT * FROM product WHERE prod_id = ?');
$stm->execute([$id]);
$s = $stm->fetch();

if (!$s) {
    redirect('product_list.php');
}

// -----------------------------------------------------------------------------
$_title = 'Product Detail';
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
        <th>ID</th>
        <td><?= ($s->prod_id) ?></td>
    </tr>
    <tr>
        <th>Name</th>
        <td><?= ($s->prod_name) ?></td>
    </tr>
    <tr>
        <th>Price</th>
        <td><?= ($s->prod_price) ?></td>
    </tr>
    <tr>
        <th>Description</th>
        <td><?= ($s->prod_desc) ?></td>
    </tr>
    <tr>
        <th>Category</th>
        <td><?= ($s->prod_cat) ?></td>
    </tr>
    <tr>
        <th>Photo(s)</th>
        <td>
            <?php
            // Check if the prod_img is not empty
            if (!empty($s->prod_img)) {
                // Split the prod_img string by commas
                $imagePaths = explode(',', $s->prod_img);
                
                // Loop through each image path and display the image
                foreach ($imagePaths as $imagePath) {
                    $imagePath = trim($imagePath); // Trim any extra spaces around the filenames
                    if (!empty($imagePath)) {
                        echo '<img src="../product/product_images/' . htmlspecialchars($imagePath) . '" alt="Product Image" style="max-height: 150px; margin-right: 10px;">';
                    }
                }
            } else {
                echo '<em>No image available</em>';
            }
            ?>
        </td>
    </tr>
</table>

<br>

<button onclick="window.location.href='product_list.php'">Back to Product List</button>

<?php
include '../_foot.php';
?>
