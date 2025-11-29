<?php
include '../_base.php';

if (is_post()) {
    $btn = req('btn');
    if ($btn == 'clear') {
        set_cart();
        redirect('?');
    }
    $id = req('id');
    $unit = req('unit');
    update_cart($id, $unit);
    redirect();
}

$_title = 'Order | Shopping Cart';
include '../_head.php';
?>

<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: #faf7f2;
    color: #333;
}

h2 {
    text-align: center;
    color: white;
    background: linear-gradient(to right, #8b5e3c, #d7a86e);
    padding: 15px;
    border-radius: 16px;
    margin-bottom: 30px;
    text-transform: uppercase;
    letter-spacing: 1px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.search-container {
    text-align: center;
    margin: 30px 0;
}

.search-container input {
    padding: 10px 15px;
    width: 300px;
    max-width: 90%;
    border: 1px solid #ccc;
    border-radius: 25px;
    font-size: 16px;
    outline: none;
    transition: 0.3s;
}

.search-container input:focus {
    border-color: #8b5e3c;
}

table.table {
    width: 100%;
    background: white;
    border-collapse: collapse;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
}

table th, table td {
    padding: 16px;
    text-align: left;
    border-bottom: 1px solid #f0e9e0;
}

table th {
    background-color: #f5e8d2;
    color: #5f3c1e;
    font-size: 14px;
    text-transform: uppercase;
}

.right {
    text-align: right;
}

.popup {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 12px;
    margin-left: 12px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.1);
}

select {
    padding: 6px 10px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 10px;
    background: #fff;
}

.cart-actions {
    margin-top: 30px;
    text-align: center;
}

.cart-actions form,
.cart-actions button {
    display: inline-block;
    margin: 8px;
}

button {
    background-color: #8b5e3c;
    color: #fff;
    border: none;
    padding: 10px 18px;
    border-radius: 12px;
    font-size: 14px;
    cursor: pointer;
    transition: background 0.3s ease;
}

button:hover {
    background-color: #a3744f;
}

p.empty {
    text-align: center;
    font-size: 18px;
    color: #888;
    padding: 40px;
}

.table {
    position: relative;
}

.img-hover-wrapper {
    display: inline-block;
    position: relative;
}

.img-hover-wrapper .popup {
    position: absolute;
    top: 0;
    left: 110%;
    width: 100px;
    height: 100px;
    object-fit: cover;
    display: none;
    border-radius: 8px;
    box-shadow: 0px 0px 8px rgba(0, 0, 0, 0.2);
    background: white;
    padding: 5px;
    z-index: 999;
}

.img-hover-wrapper:hover .popup {
    display: block;
}
#clearSearch {
    padding: 10px 20px;
    margin-left: 10px;
    background: #ccc;
    color: #333;
    border: none;
    border-radius: 25px;
    cursor: pointer;
    font-size: 16px;
    transition: background 0.3s ease;
}

#clearSearch:hover {
    background: #bbb;
}

</style>

<?php
$cart = get_cart();

if (empty($cart)) {
    echo "<p class='empty'>Your cart is empty. Please add some products to your cart.</p>";
} else {
?>
<!-- new -->
<!-- ✅ Search Bar with Clear Button -->
<div class="search-container">
    <input type="text" id="cartSearch" placeholder="Search cart by product name...">
    <button type="button" id="clearSearch">Clear</button>
</div>

<table class="table">
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Price (RM)</th>
    <th>Unit</th>
    <th>Subtotal (RM)</th>
</tr>

<?php
    $total = 0;
    $count = 0;
    $stm = $_db->prepare('SELECT * FROM product WHERE prod_id = ?');

    foreach ($cart as $id => $unit):
        $stm->execute([$id]);
        $p = $stm->fetch();
        $subtotal = $p->prod_price * $unit;
        $count += $unit;
        $total += $subtotal;
        $images = explode(',', $p->prod_img);
        $first_image = trim($images[0]);
?>
    <tr>
        <td><?= $p->prod_id ?></td>
        <td><?= htmlspecialchars($p->prod_name) ?></td>
        <td class="right"><?= number_format($p->prod_price, 2) ?></td>
        <td>
        <form method="post">
            <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
            <select name="unit" onchange="this.form.submit()">
                <?php foreach ($_units as $value): ?>
                    <option value="<?= $value ?>" <?= $unit == $value ? 'selected' : '' ?>>
                        <?= $value ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <noscript><button type="submit">Update</button></noscript>
        </form>
        </td>
        <td class="right">
            <?= number_format($subtotal, 2) ?>
            <div class="img-hover-wrapper">
                <img src="/product/product_images/<?= htmlspecialchars($first_image) ?>" class="popup">
            </div>
        </td>
    </tr>
<?php endforeach; ?>

<tr>
    <th colspan="3"></th>
    <th class="right"><?= $count ?></th>
    <th class="right"><?= number_format($total, 2) ?></th>
</tr>
</table>

<div class="cart-actions">
    <form method="post">
        <input type="hidden" name="btn" value="clear">
        <button type="submit">Clear Cart</button>
    </form>

    <?php if ($_user?->role == 'Member'): ?>
        <button data-post="checkout.php">Checkout</button>
    <?php else: ?>
        <p>Please <a href="/login.php">login</a> as a member to checkout.</p>
    <?php endif; ?>
</div>

<?php } ?>

<script>
    // Auto-submit on unit change
    document.querySelectorAll('select').forEach(select => {
        select.addEventListener('change', () => {
            select.form.submit();
        });
    });

    // Button post redirect
    document.querySelectorAll('[data-post]').forEach(btn => {
        btn.addEventListener('click', () => {
            const form = document.createElement('form');
            form.method = 'post';
            form.action = btn.getAttribute('data-post');
            document.body.appendChild(form);
            form.submit();
        });
    });
  

    //new
    
    // ✅ Search/filter cart rows
    document.getElementById('cartSearch').addEventListener('keyup', function () {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('.table tr');

        rows.forEach((row, index) => {
            if (index === 0) return; // Skip header row
            const nameCell = row.cells[1]; // Product Name cell
            if (nameCell) {
                const name = nameCell.textContent.toLowerCase();
                row.style.display = name.includes(filter) ? '' : 'none';
            }
        });
    });

    // ✅ Clear search input and reset table rows
document.getElementById('clearSearch').addEventListener('click', function () {
    const input = document.getElementById('cartSearch');
    input.value = '';
    const rows = document.querySelectorAll('.table tr');
    rows.forEach((row, index) => {
        if (index > 0) row.style.display = ''; // Skip header
    });
    input.focus();
});

</script>

<?php
include '../_foot.php';
?>
