<?php
require '../_base.php';

$_err = [];
$prod_img = '';

if (is_post()) {
    $prod_name = req('prod_name');
    $prod_price = req('prod_price');
    $prod_desc = req('prod_desc');
    $prod_cat = req('prod_cat');
    $existing_imgs = req('prod_img_list', '');
    $f = get_file('prod_img');
    $new_img = $existing_imgs;

    if ($prod_name == '') {
        $_err['prod_name'] = 'Name is required';
    } elseif (strlen($prod_name) > 100) {
        $_err['prod_name'] = 'Name should not exceed 100 characters';
    }

    if ($prod_price == '') {
        $_err['prod_price'] = 'Price is required';
    } elseif (!is_numeric($prod_price) || $prod_price <= 0) {
        $_err['prod_price'] = 'Price must be a positive number';
    }

    $valid_cats = ['Food', 'Beverage'];
    if (!in_array($prod_cat, $valid_cats)) {
        $_err['prod_cat'] = 'Invalid category';
    }

    if ($f) {
        if (!str_starts_with($f->type, 'image/')) {
            $_err['prod_img'] = 'Uploaded file must be an image';
        } elseif ($f->size > 1 * 1024 * 1024) {
            $_err['prod_img'] = 'Image must be less than 1MB';
        } else {
            $photo = save_photo($f, '../product/product_images');
            if (!$photo) {
                $_err['prod_img'] = 'Failed to save the image';
            } else {
                $new_img = $existing_imgs ? "$existing_imgs,$photo" : $photo;
            }
        }
    }

    if (!$_err) {
        $stm = $_db->prepare('
            INSERT INTO product (prod_name, prod_price, prod_desc, prod_cat, prod_img)
            VALUES (?, ?, ?, ?, ?)
        ');
        $stm->execute([$prod_name, $prod_price, $prod_desc, $prod_cat, $new_img]);

        temp('info', 'Product successfully inserted');
        redirect('product_list.php');
    }
}

$_title = 'Insert Product';
include '../_head.php';
?>


<style>
/* Same as before, keeping layout and styling */
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
}
.form button:hover {
    background-color: #5a411b;
}
.section-buttons {
    display: flex;
    justify-content: space-between;
}
.image-list {
    display: flex;
    gap: 5px;
    flex-wrap: wrap;
    margin-top: 20px;
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
form .remove-btn {
    position: absolute;
    padding: 4px 8px;
    background-color: red; 
    color: white;  
    font-size: 12px;
    border: none;
    border-radius: 50%;
    cursor: pointer;
    top: -10px;
    right: -10px;
}
</style>

<div class="container">
    <h2>Insert New Product</h2>
    <form method="post" class="form" enctype="multipart/form-data" onsubmit="updateImgList()">
        <input type="hidden" name="prod_img_list" id="prod_img_list">

        <label for="prod_name">Product Name</label>
        <?= html_text('prod_name', 'maxlength="100"') ?> 
        <?= err('prod_name') ?>

        <label for="prod_price">Product Price</label>
        <?= html_text('prod_price') ?> 
        <?= err('prod_price') ?>

        <label for="prod_desc">Product Description</label>
        <?= html_textarea('prod_desc') ?> 
        <?= err('prod_desc') ?>

        <label for="prod_cat">Product Category</label>
        <?= html_select('prod_cat', ['Food' => 'Food', 'Beverage' => 'Beverage']) ?> 
        <?= err('prod_cat') ?>

        <label for="prod_img">Add New Photo</label>
        <label class="upload">
            <?= html_file('prod_img', 'image/*', 'onchange="previewImage(this)"') ?>
        </label><br>
        <div class="image-list" id="imagePreview"> </div> 
        <?= err('prod_img') ?>
        <br>
        
        
        <div class="section-buttons">
            <button type="submit">Submit</button>  
            <button type="reset" onclick="resetPreview()">Reset</button>  
        </div>
        </div>
    </form>
</div>

<script>
function previewImage(input) {
    const file = input.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(e) {
        const imgBox = document.createElement('div');
        imgBox.className = 'image-box';
        imgBox.setAttribute('data-img', file.name);

        const img = document.createElement('img');
        img.src = e.target.result;
        img.alt = 'Uploaded photo';

        const removeBtn = document.createElement('button');
        removeBtn.className = 'remove-btn';
        removeBtn.textContent = 'X';
        removeBtn.onclick = function() {
            imgBox.remove();
        };

        imgBox.appendChild(removeBtn);
        imgBox.appendChild(img);

        document.getElementById('imagePreview').appendChild(imgBox);

        input.value = '';
    };
    reader.readAsDataURL(file);
}

function updateImgList() {
    const imgs = document.querySelectorAll('#imagePreview .image-box');
    const list = Array.from(imgs).map(img => img.getAttribute('data-img')).join(',');
    document.getElementById('prod_img_list').value = list;
}

</script>

<?php include '../_foot.php'; ?>