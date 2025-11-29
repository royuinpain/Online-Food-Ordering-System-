<?php
include '../_base.php';

// Handle form submission
if (is_post()) {
    $id = req('id');
    $unit = req('unit');
    $wishlist = req('wishlist');

    if ($id && isset($unit)) {
        update_cart($id, $unit);
    }

    if ($wishlist && $_user) {
        $user_id = $_user->id;

        // Avoid duplicate wishlist entries
        $stm = $_db->prepare("INSERT IGNORE INTO wishlist (user_id, product_id) VALUES (?, ?)");
        $stm->execute([$user_id, $id]);

        // ✅ Set popup message in session
        $_SESSION['wishlist_popup'] = true;
    }

    redirect();
}

$id  = req('id');
$stm = $_db->prepare('SELECT * FROM product WHERE prod_id = ?');
$stm->execute([$id]);
$p = $stm->fetch();
if (!$p) redirect('list.php');

$images = explode(',', $p->prod_img);
$firstImage = $images[0] ?? 'default.jpg';

$_title = 'Product | Detail';
include '../_head.php';

// ✅ Read and clear session message
$showPopup = false;
if (!empty($_SESSION['wishlist_popup'])) {
    $showPopup = true;
    unset($_SESSION['wishlist_popup']);
}
?>


<style>
    .product-container {
        display: flex;
        flex-wrap: wrap;
        gap: 40px;
        padding: 30px;
        background: #f9f9f9;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
    }

    #photo-container {
        position: relative;
        width: 460px;
        height: 460px;
        overflow: hidden;
        border-radius: 12px;
        background: #fff;
        box-shadow: inset 0 0 10px rgba(0,0,0,0.05);
    }

    #photo {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 12px;
        transition: transform 0.3s ease;
    }

    .nav-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0, 0, 0, 0.4);
        color: white;
        border: none;
        padding: 8px 12px;
        font-size: 20px;
        cursor: pointer;
        border-radius: 50%;
        transition: background 0.2s;
    }

    .nav-btn:hover {
        background: rgba(0, 0, 0, 0.6);
    }

    .prev { left: 10px; }
    .next { right: 10px; }

    #prod_details {
        flex: 1;
        min-width: 300px;
    }

    #prod_details h2 {
        font-size: 28px;
        margin-bottom: 10px;
    }

    #prod_details p {
        font-size: 16px;
        line-height: 1.5;
        margin: 10px 0;
    }

    #prod_details strong {
        font-size: 20px;
    }

    select {
        padding: 6px 12px;
        font-size: 16px;
        border-radius: 6px;
        border: 1px solid #ccc;
        margin-left: 10px;
    }

.action-buttons {
    margin-top: 30px;
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    align-items: center;
}

.action-buttons form,
.action-buttons button {
    margin: 0;
}

button {
    background-color:rgb(0, 0, 0);
    color: #fff;
    border: none;
    padding: 10px 16px;
    font-size: 16px;
    border-radius: 6px;
    cursor: pointer;
    transition: background 0.3s;
    white-space: nowrap;
}

button:hover {
    background-color: #0056b3;
}

.wishlist-button {
    background-color:rgb(0, 0, 0);
}

.wishlist-button:hover {
    background-color: #c2185b;
}

    .note {
        font-size: 14px;
        color: #666;
        margin-top: 10px;
    }



.popup {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    background-color: #705222;
    color: white;
    padding: 15px 25px;
    border-radius: 8px;
    font-size: 16px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    z-index: 9999;
    opacity: 0;
    animation: fadeInOut 3s forwards;
}

@keyframes fadeInOut {
    0% { opacity: 0; transform: translate(-50%, -20px); }
    10% { opacity: 1; transform: translate(-50%, 0); }
    90% { opacity: 1; transform: translate(-50%, 0); }
    100% { opacity: 0; transform: translate(-50%, -20px); }
}
</style>

<?php if ($showPopup): ?>
    <div class="popup">Item added to your wishlist! ❤️</div>
<?php endif; ?>

<div class="product-container">
    <div id="photo-container">
        <button class="nav-btn prev" onclick="prevImage()">❮</button>
        <img src="/product/product_images/<?= htmlspecialchars($firstImage) ?>" id="photo" alt="Product Image">
        <button class="nav-btn next" onclick="nextImage()">❯</button>
    </div>

    <div id="prod_details">
        <h2><?= htmlspecialchars($p->prod_name) ?></h2>
        <p><?= nl2br(htmlspecialchars($p->prod_desc)) ?></p>
        <p><strong>RM <?= htmlspecialchars($p->prod_price) ?></strong></p>

        <?php
        $cart = get_cart();
        $id = $p->prod_id;
        $unit = $cart[$p->prod_id] ?? 0;
        ?>
        
        <form method="post">
            <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
            <label for="unit">Unit:</label>
            <select name="unit" onchange="this.form.submit()">
                <?php foreach ($_units as $value): ?>
                    <option value="<?= $value ?>" <?= $unit == $value ? 'selected' : '' ?>>
                        <?= $value ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <?= $unit ? '✅' : '' ?>
            <noscript><button type="submit">Update</button></noscript>
        </form>

        <div class="action-buttons">
            <?php if ($_user): ?>
                <form method="post">
                    <input type="hidden" name="id" value="<?= htmlspecialchars($p->prod_id) ?>">
                    <input type="hidden" name="wishlist" value="1">
                    <button type="submit" class="wishlist-button">Add to Wishlist ❤️</button>
                </form>
            <?php else: ?>
                <p class="note">Please <a href="/login.php">login</a> to add products to your wishlist.</p>
            <?php endif; ?>

            <button data-get="list.php">Add to Cart 🛒</button>
        </div>
    </div>
</div>

<script>
    let images = [
        <?php foreach ($images as $image): ?>
            "/product/product_images/<?= trim(htmlspecialchars($image)) ?>",
        <?php endforeach; ?>
    ];

    let currentIndex = 0;
    let photo = document.getElementById('photo');

    function prevImage() {
        currentIndex = (currentIndex - 1 + images.length) % images.length;
        photo.src = images[currentIndex];
    }

    function nextImage() {
        currentIndex = (currentIndex + 1) % images.length;
        photo.src = images[currentIndex];
    }
</script>

<?php include '../_foot.php'; ?>
