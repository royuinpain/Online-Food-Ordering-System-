<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $_title ?? 'Untitled' ?></title>
    <link rel="shortcut icon" href="/images/estatecoffeehouse.png">
    <link rel="stylesheet" href="/css/app.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="/js/app.js"></script>
</head>
<body>
    <!-- Flash message -->
    <div id="info"><?= temp('info') ?></div>

    <header>
        <h1><a href="/">Estate Coffeehouse</a></h1>

        <?php if ($_user): ?>
            <div>
                <?= $_user->name ?><br>
                <?= $_user->role ?>
            </div>
            <img src="/user_photos/<?= $_user->photo ?>">
        <?php endif ?>
    </header>

    <nav>
  
    <!-- Show Product List and Cart for all users except Admin -->
    <?php if ($_user?->role != 'Admin'): ?>
        <a href="/">Welcome</a>
        <a href="/product/list.php">Product List</a>
        <a href="/order/cart.php">Shopping Cart</a>
    <?php endif ?>

    <!-- Admin Links -->
    <?php if ($_user?->role == 'Admin'): ?>
        <a href="/member/member_maintenance.php">Member Maintenance</a>
        <a href="/admin/admin_maintenance.php">Admin Maintenance</a>
        <a href="/adminproduct/product_maintenance.php">Product Maintenance</a>
        <a href="/order_listing/order_listing.php">Order Listing</a>

    <?php endif ?>

    <!-- Member Links -->
    <?php if ($_user?->role == 'Member'): ?>
        <a href="/order/history.php">Order History</a>
        <a href="/product/wishlist.php">Wishlist</a>
    <?php endif ?>

    <div></div>
    <!-- User Profile and Logout if Logged In -->
    <?php if ($_user): ?>
        <a href="/user/profile.php">Profile</a>
        <a href="/logout.php">Logout</a>
    <?php else: ?>
        <!-- Register and Login for Guests -->
        <a href="/user/register.php">Register</a>
        <a href="/login.php">Login</a>
    <?php endif ?>
</nav>


    <main>
        <h1><?= $_title ?? 'Untitled' ?></h1>