<?php
include '../_base.php';

// ----------------------------------------------------------------------------

// Admin role check
auth('Admin');

// ----------------------------------------------------------------------------
$_title = 'Admin Maintenance';
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

    .maintenance-container {
        max-width: 900px;
        margin: 60px auto;
        padding: 40px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
        text-align: center;
    }

    .maintenance-container h2 {
        margin-bottom: 30px;
    }

    .maintenance-container a {
        text-decoration: none;
        display: inline-block;
        margin-top: 20px;
    }

    .maintenance-container button {
        padding: 12px 25px;
        border: none;
        background-color: #705222;
        color: white;
        border-radius: 6px;
        cursor: pointer;
        font-size: 16px;
        margin-top: 10px;
    }

    .maintenance-container button:hover {
        background-color: #5a411b;
    }

    .maintenance-container a button {
        width: 100%;
        text-align: center;
    }

</style>

<div class="maintenance-container">
    <h2>Admin Maintenance</h2>

    <!-- Admin navigation buttons -->
    <div>
        <a href="/admin/admin_list.php">
            <button>Admin List</button>
        </a>
        <br>
        <a href="/admin/insert_admin.php">
            <button>Insert Admin</button>
        </a>
    </div>
</div>

<?php
include '../_foot.php';
?>
