<?php
include '../_base.php';

// ----------------------------------------------------------------------------

// Admin role check
auth('Admin');

// ----------------------------------------------------------------------------
$_title = 'Product Maintenance';
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

    .container {
        max-width: 900px;
        margin: 60px auto;
        padding: 40px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
        text-align: center;
    }

    .container h2 {
        font-size: 24px;
        margin-bottom: 30px;
    }

    .container a {
        text-decoration: none;
    }

    .container button {
        margin: 10px 0;
        padding: 10px 20px;
        background-color: #705222;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 16px;
    }

    .container button:hover {
        background-color: #5a411b;
    }

</style>

<div class="container">
    <h2>Product Maintenance</h2>

    <!-- Admin navigation buttons -->
    <div>
        <a href="/adminproduct/product_list.php">
            <button>Product List</button>
        </a>
        <br>
        <a href="/adminproduct/insert.php">
            <button>Insert Product</button>
        </a>
    </div>
</div>

<?php
include '../_foot.php';
?>
