<?php
include '_base.php';

// ----------------------------------------------------------------------------

if (is_post()) {
    $email = req('email');
    $password = req('password');

    // Validate: email
    if ($email == '') {
        $_err['email'] = 'Required';
    }
    else if (!is_email($email)) {
        $_err['email'] = 'Invalid email';
    }

    // Validate: password
    if ($password == '') {
        $_err['password'] = 'Required';
    }

    // Login user
    if (!$_err) {
        $stm = $_db->prepare('
            SELECT * FROM user
            WHERE email = ? AND password = SHA1(?)
        ');
        $stm->execute([$email, $password]);
        $u = $stm->fetch();

        if ($u) {
            temp('info', 'Login successfully');
            login($u);
        } else {
            $_err['password'] = 'Not matched';
        }
    }
}

// ----------------------------------------------------------------------------

$_title = 'Login';
include '_head.php';
?>

<body>
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

    .form-actions button:hover {
        background-color: #5a411b;
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
    <h2 class="page-title">Login</h2>

    <form method="post" class="form">
        <label for="email">Email</label>
        <?= html_text('email', 'maxlength="100" class="input-field"') ?>
        <?= err('email') ?>

        <label for="password">Password</label>
        <?= html_password('password', 'maxlength="100" class="input-field"') ?>
        <?= err('password') ?>

        <div class="form-actions">
            <br>
            <button>Login</button>
            <button type="reset">Reset</button>
        </div>
    </form>

    <p class="register-link">Don't have an account? <a href="/user/register.php">Register</a></p>
</div>

</body>

<?php
include '_foot.php';
?>
