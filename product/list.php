<?php
include '../_base.php';

// ----------------------------------------------------------------------------
// Handle add-to-cart request
if (is_post()) {    //check request method is post
    $id = req('id');     // get 
    $unit = req('unit');  // get
    update_cart($id, $unit);  //update
    redirect(); // avoid resubmit form when refresh
}  

// Get search input (default to empty if none)
$search = req('search', '');

// get sort input
$sort = req('sort', '');
$sortSQL = '';

if ($sort === 'asc') {
    $sortSQL = ' ORDER BY prod_price ASC';
} elseif ($sort === 'desc') {
    $sortSQL = ' ORDER BY prod_price DESC';
}


//search bar 
if ($search) {
    $stm = $_db->prepare("SELECT * FROM product WHERE prod_name LIKE ? OR prod_cat LIKE ? $sortSQL");
    $searchQuery = '%' . $search . '%';
    $stm->execute([$searchQuery, $searchQuery]);
} else {
    $stm = $_db->query("SELECT * FROM product $sortSQL");
}

$products = $stm->fetchAll(); // fetch all matching products

// Separate products into Food and Beverage categories zx
$categorizedProducts = [
    'Food' => [],
    'Beverage' => []
];

foreach ($products as $p) {
    if (strtolower($p->prod_cat) === 'food') {
        $categorizedProducts['Food'][] = $p;
    } elseif (strtolower($p->prod_cat) === 'beverage') {
        $categorizedProducts['Beverage'][] = $p;
    }
}

// ----------------------------------------------------------------------------

$_title = 'Product | List';
include '../_head.php';
?>
<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #fffaf5;
    color: #333;
    margin: 0;
    padding: 0;
}

/* Search Bar Styling */
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
    border-color: #705222;
}

.search-container button {
    padding: 10px 20px;
    background: #705222;
    color: white;
    border: none;
    border-radius: 25px;
    cursor: pointer;
    font-size: 16px;
    margin-left: 10px;
    transition: background 0.3s ease;
}

.search-container button:hover {
    background: #8a6b32;
}

/* Category Styles */
.category {
    margin: 40px auto;
    max-width: 1200px;
    padding: 0 20px;
}

.category h2 {
    background: linear-gradient(135deg, #705222, #d1a35d);
    color: white;
    padding: 15px;
    font-size: 24px;
    text-align: center;
    border-radius: 10px;
    margin-bottom: 20px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

/* Product Grid Styles */
.products {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
}

.product {
    background-color: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    width: 220px;
    position: relative;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.product:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 16px rgba(0,0,0,0.15);
}

.product img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    cursor: pointer;
}

.product div {
    padding: 10px;
    text-align: center;
    font-weight: bold;
    color: #444;
    background-color: #fff;
}

.product form {
    position: absolute;
    top: 10px;
    right: 10px;
}

/* Dropdown Selection */
select {
    padding: 5px 10px;
    border-radius: 20px;
    border: 1px solid #ccc;
    background-color: #fff;
    font-size: 14px;
    outline: none;
    cursor: pointer;
    transition: 0.3s ease;
}

select:hover {
    border-color: #705222;
}

/* message if no results */
.no-results {
    text-align: center;
    font-size: 18px;
    color: #999;
    margin: 40px 0;
}
</style>

<!-- Search Bar + Sort Dropdown -->
<div class="search-container">
    <form method="get">   <!-- back to the top search js -->
        <input type="text" name="search" placeholder="Search by name or category..." value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Search</button>
        <select name="sort">
            <option value="">Sort by</option>
            <option value="asc" <?= req('sort') === 'asc' ? 'selected' : '' ?>>Low to High</option>
            <option value="desc" <?= req('sort') === 'desc' ? 'selected' : '' ?>>High to Low</option>
        </select>
    </form>
</div>

<?php
// Check if there are results after filtering
$hasResults = false;

//category zx

foreach ($categorizedProducts as $category => $items) {
    // Loop through each category and its associated products
    if (!empty($items)) {
        // Only continue if the category has at least one product
        $hasResults = true; // Flag to indicate products were found
        ?>
        <div class="category">
            <h2><?= htmlspecialchars($category) ?></h2> <!-- Display category name safely -->
            <div class="products">
                <?php foreach ($items as $p): ?> <!-- Loop through each product -->
                    <?php
                    $cart = get_cart(); // Get current cart data (likely from session)
                    $id = $p->prod_id; // Product ID
                    $unit = $cart[$p->prod_id] ?? 0; // Quantity in cart, default to 0
                    $firstImage = explode(',', $p->prod_img)[0] ?? 'default.jpg'; // Get the first image or fallback
                    ?>
                    <div class="product">
                        <form method="post"> <!-- Form to update cart quantity -->
                            <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>"> <!-- Hidden input with product ID -->
                            <select name="unit" onchange="this.form.submit()"> <!-- Dropdown to select quantity -->
                                <?php foreach ($_units as $value): ?> <!-- Loop through available units -->
                                    <option value="<?= $value ?>" <?= $unit == $value ? 'selected' : '' ?>>
                                        <?= $value ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?= $unit ? '✅' : '' ?> <!-- Show checkmark if item is in cart -->
                            <noscript><button type="submit">Update</button></noscript> <!-- Fallback for browsers without JS -->
                        </form>

                        <img src="/product/product_images/<?= htmlspecialchars($firstImage) ?>" 
                             data-get="/product/detail.php?id=<?= $p->prod_id ?>"> <!-- Product image with detail link -->
                        <div>
                            <?= htmlspecialchars($p->prod_name) ?> <br> <!-- Product name -->
                            RM <?= htmlspecialchars($p->prod_price) ?> <!-- Product price -->
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }
}

// If no results are found, show a message
if (!$hasResults) {
    echo '<p class="no-results">No products found.</p>';
}
?>

<script>
    document.querySelectorAll('select').forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });
</script>

<?php
include '../_foot.php';
?>
