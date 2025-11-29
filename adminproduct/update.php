<?php
require '../_base.php';
// ----------------------------------------------------------------------------

$_err = [];

if (is_get()) {
    $prod_id = req('prod_id');

    $stm = $_db->prepare('SELECT * FROM product WHERE prod_id = ?');
    $stm->execute([$prod_id]);
    $s = $stm->fetch();

    if (!$s) {
        redirect('product_list.php');
    }

    extract((array)$s);
    $_SESSION['prod_img'] = $s->prod_img;
}

if (is_post()) {
    $prod_id   = req('prod_id');
    $prod_name = req('prod_name');
    $prod_price = req('prod_price');
    $prod_desc = req('prod_desc');
    $prod_cat  = req('prod_cat');
    $existing_imgs = req('prod_img_list', '');
    $f         = get_file('prod_img');
    $new_img   = $existing_imgs;

    // Validation
    if ($prod_name == '') {
        $_err['prod_name'] = 'Required';
    } elseif (strlen($prod_name) > 100) {
        $_err['prod_name'] = 'Maximum length 100';
    }

    if ($prod_price == '') {
        $_err['prod_price'] = 'Required';
    } elseif (!is_numeric($prod_price) || $prod_price <= 0) {
        $_err['prod_price'] = 'Must be a positive number';
    }

    $valid_cats = ['Food', 'Beverage'];
    if (!in_array($prod_cat, $valid_cats)) {
        $_err['prod_cat'] = 'Invalid category';
    }

    // Handle new photo
    if ($f) {
        if (!str_starts_with($f->type, 'image/')) {
            $_err['prod_img'] = 'Must be an image';
        } elseif ($f->size > 1 * 1024 * 1024) {
            $_err['prod_img'] = 'Maximum 1MB';
        } else {
            $photo = save_photo($f, '../product/product_images');
            if (!$photo) {
                $_err['prod_img'] = 'Failed to save photo';
            } else {
                $new_img = $existing_imgs ? "$existing_imgs,$photo" : $photo;
            }
        }
    }

    $_SESSION['prod_img'] = $new_img;

    if (!$_err) {
        $stm = $_db->prepare('
            UPDATE product
            SET prod_name = ?, prod_price = ?, prod_img = ?, prod_desc = ?, prod_cat = ?
            WHERE prod_id = ?
        ');
        $stm->execute([$prod_name, $prod_price, $new_img, $prod_desc, $prod_cat, $prod_id]);

        temp('info', 'Record updated');
        redirect('product_list.php');
    }
}

// ----------------------------------------------------------------------------
$_title = 'Update Product';
include '../_head.php';
?>

<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #fffaf5;
    margin: 0;
    padding: 0;
    color: #333;
}

.container {
    max-width: 900px;
    margin: 40px auto;
    padding: 30px;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.form label {
    font-weight: bold;
    margin-bottom: 10px;
    display: block;
}

.form input, .form select, .form textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-sizing: border-box;
}

.form input[type="file"] {
    padding: 5px;
}

.form button {
    padding: 12px 20px;
    background-color: #705222;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    margin-right: 10px;
    transition: background-color 0.3s;
}

.form button:hover {
    background-color: #5a411b;
}

.form button[type="reset"] {
    background-color: #705222;
}

.form button[type="reset"]:hover {
    background-color: #5a411b;
}

.form .upload img {
    max-height: 150px;
    margin-top: 10px;
}

.form .error {
    color: #d9534f;
    font-size: 12px;
    margin-top: -10px;
    margin-bottom: 15px;
}

.section-buttons {
    display: flex;
    justify-content: space-between;
}

    .image-list {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 10px;
    }

    .image-box {
        position: relative;
        display: inline-block;
    }

    .image-box img {
        max-height: 100px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .form .remove-btn {
    position: absolute;
    padding: 12px 20px;
    background-color:red;
    color: #fff;
    border: none;
    border-radius: 20px;
    cursor: pointer;
    font-size: 14px;
    top: -10px;
    right: -10px;
    transition: background-color 0.3s;
    }
    
</style>
<div class="container">
<form method="post" class="form" enctype="multipart/form-data" onsubmit="updateImgList()">
    <input type="hidden" name="prod_id" value="<?= ($prod_id) ?>">
    <input type="hidden" name="prod_img_list" id="prod_img_list">

    <label for="prod_name">Name</label>
    <?= html_text('prod_name', 'maxlength="100"') ?>
    <?= err('prod_name') ?>

    <label for="prod_price">Price</label>
    <?= html_text('prod_price') ?>
    <?= err('prod_price') ?>

    <label for="prod_desc">Description</label>
    <?= html_textarea('prod_desc') ?>  <!-- Display the product description textarea -->
    <?= err('prod_desc') ?>  <!-- Show any error related to product description -->


    <label for="prod_cat">Category</label>
    <?= html_select('prod_cat', ['Food' => 'Food', 'Beverage' => 'Beverage']) ?>
    <?= err('prod_cat') ?>

    <label for="prod_img">Add New Photo</label>
    <label class="upload">
        <?= html_file('prod_img', 'image/*') ?>
    </label>
    <?= err('prod_img') ?>

    <div class="image-list" id="imagePreview">
        <?php
        $imgArray = explode(',', $_SESSION['prod_img'] ?? '');
        foreach ($imgArray as $img):
            $img = trim($img);
            if ($img):
        ?>
        <div class="image-box" data-img="<?= htmlspecialchars($img) ?>">
            <button type="button" class="remove-btn" onclick="removeImage(this)">X</button>
            <img src="/product/product_images/<?= htmlspecialchars($img) ?>" alt="Product photo">
        </div>
        <?php endif; endforeach; ?>
    </div>

    <section> 
        <button type="submit">Update</button>
        <button type="reset">Reset</button>
    </section>
</form>
            </div>
    
<script>
function removeImage(button) {
    const box = button.parentElement;
    box.remove();
}

function updateImgList() {
    const imgs = document.querySelectorAll('#imagePreview .image-box');
    const list = Array.from(imgs).map(img => img.getAttribute('data-img')).join(',');
    document.getElementById('prod_img_list').value = list;
}
</script>

<?php include '../_foot.php'; ?>
