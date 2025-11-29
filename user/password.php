<?php
include '../_base.php';

// ----------------------------------------------------------------------------

// Authenticated users
auth();

if (is_post()) {
    $password     = req('password');
    $new_password = req('new_password');
    $confirm      = req('confirm');

    // Validate: password
    if ($password == '') {
        $_err['password'] = 'Required';
    }
    else if (strlen($password) < 5 || strlen($password) > 100) {
        $_err['password'] = 'Between 5-100 characters';
    }
    else {
        $stm = $_db->prepare('
            SELECT COUNT(*) FROM user
            WHERE password = SHA1(?) AND id = ?
        ');
        $stm->execute([$password,$_user->id]);
        
        if ($stm->fetchColumn() == 0) {
            $_err['password'] = 'Not matched';
        }
    }

    // Validate: new_password
    if ($new_password == '') {
        $_err['new_password'] = 'Required';
    }
    else if (strlen($new_password) < 5 || strlen($new_password) > 100) {
        $_err['new_password'] = 'Between 5-100 characters';
    }

    // Validate: confirm
    if (!$confirm) {
        $_err['confirm'] = 'Required';
    }
    else if (strlen($confirm) < 5 || strlen($confirm) > 100) {
        $_err['confirm'] = 'Between 5-100 characters';
    }
    else if ($confirm != $new_password) {
        $_err['confirm'] = 'Not matched';
    }

    // DB operation
    if (!$_err) {

        // Update user (password)
        $stm = $_db->prepare('
            UPDATE user
            SET password = SHA1(?)
            WHERE id = ?
        ');
        $stm->execute([$new_password, $_user->id]);

        temp('info', 'Record updated');
        redirect('/');
    }
}

// ----------------------------------------------------------------------------

$_title = 'User | Reset Password';
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

    .content-wrapper {
        max-width: 500px;
        margin: 60px auto;
        padding: 40px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
    }

    .page-title {
        text-align: center;
        margin-bottom: 30px;
    }

    .form {
        margin-bottom: 30px;
    }

    .form label {
        font-weight: bold;
        display: block;
        margin-top: 15px;
    }

    .form div, .form b {
        margin-top: 5px;
        display: block;
    }

    .input-field {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        border: 1px solid #ddd;
        border-radius: 6px;
    }

    .form-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }

    .form-actions button {
        padding: 12px 25px;
        border: none;
        background-color: #705222;
        color: white;
        border-radius: 6px;
        cursor: pointer;
        font-size: 16px;
    }

    .form-actions button[type="reset"] {
        background-color: #ddd;
        color: #333;
    }


    .register-link {
        text-align: center;
        margin-top: 20px;
    }

    .register-link a {
        color: #705222;
        text-decoration: none;
    }

    .register-link a:hover {
        color: #5a411b;
    }

    .error-message {
        color: red;
        font-size: 14px;
        margin-top: 5px;
    }
</style>

<div class="content-wrapper">
<form method="post" class="form">
    <label for="password">Password</label>
    <?= html_password('password', 'maxlength="100"') ?>
    <?= err('password') ?>

    <label for="new_password">New Password</label>
    <?= html_password('new_password', 'maxlength="100"') ?>
    <?= err('new_password') ?>

    <label for="confirm">Confirm</label>
    <?= html_password('confirm', 'maxlength="100"') ?>
    <?= err('confirm') ?>
    <div class="form-actions">
    <section>
        <button>Submit</button>
        <button type="reset">Reset</button>
    </section>
    </div>
</form>
</div>
<?php
include '../_foot.php';