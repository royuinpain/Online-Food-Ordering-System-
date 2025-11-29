<?php
include '../_base.php';

// ----------------------------------------------------------------------------

if (is_post()) {
    $email    = req('email');
    $gender   = req('gender');
    $password = req('password');
    $confirm  = req('confirm');
    $name     = req('name');
    $f = get_file('photo');

    // Validate: email
    if (!$email) {
        $_err['email'] = 'Required';
    }
    else if (strlen($email) > 100) {
        $_err['email'] = 'Maximum 100 characters';
    }
    else if (!is_email($email)) {
        $_err['email'] = 'Invalid email';
    }
    else if (!is_unique($email, 'user', 'email')) {
        $_err['email'] = 'Duplicated';
    }

    // Validate: password
    if (!$password) {
        $_err['password'] = 'Required';
    }
    else if (strlen($password) < 5 || strlen($password) > 100) {
        $_err['password'] = 'Between 5-100 characters';
    }

    // Validate: confirm
    if (!$confirm) {
        $_err['confirm'] = 'Required';
    }
    else if (strlen($confirm) < 5 || strlen($confirm) > 100) {
        $_err['confirm'] = 'Between 5-100 characters';
    }
    else if ($confirm != $password) {
        $_err['confirm'] = 'Not matched';
    }

    // Validate: name
    if (!$name) {
        $_err['name'] = 'Required';
    }
    else if (strlen($name) > 100) {
        $_err['name'] = 'Maximum 100 characters';
    }

    // Validate gender
    if ($gender == '') {
        $_err['gender'] = 'Required';
    }
    else if (!array_key_exists($gender, $_genders)) {
        $_err['name'] = 'Invalid value';
    }

    // Validate: photo (file)
    if (!$f) {
        $_err['photo'] = 'Required';
    }
    else if (!str_starts_with($f->type, 'image/')) {
        $_err['photo'] = 'Must be image';
    }
    else if ($f->size > 1 * 1024 * 1024) {
        $_err['photo'] = 'Maximum 1MB';
    }

    // DB operation
    if (!$_err) {

        // (1) Save photo
        $photo = save_photo($f, '../user_photos');
        
        // (2) Insert user (member)
        $stm = $_db->prepare('
            INSERT INTO user (email, password, name, gender, photo, role)
            VALUES (?,SHA1(?),?,?,?,"Member")
        ');
        $stm->execute([$email, $password, $name, $gender, $photo]);


        temp('info', 'Record inserted');
        redirect('/login.php');
    }
}

// ----------------------------------------------------------------------------

$_title = 'User | Register Member';
include '../_head.php';
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

    .input-field, .input-file {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        border: 1px solid #ddd;
        border-radius: 6px;
    }

    .upload {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .upload img {
        margin-top: 10px;
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
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

    .form-container .upload {
    margin-top: 10px;
    display: inline-block;
}

.form-container .upload img {
    max-width: 100px;
    max-height: 100px;
    border-radius: 6px;
    object-fit: cover;
}
</style>

<div class="content-wrapper">
    <h2 class="page-title">Register Member</h2>

    <form method="post" class="form" enctype="multipart/form-data">
        <label for="email">Email</label>
        <?= html_text('email', 'maxlength="100" class="input-field"') ?>
        <?= err('email') ?>

        <label for="password">Password</label>
        <?= html_password('password', 'maxlength="100" class="input-field"') ?>
        <?= err('password') ?>

        <label for="confirm">Confirm Password</label>
        <?= html_password('confirm', 'maxlength="100" class="input-field"') ?>
        <?= err('confirm') ?>

        <label for="name">Name</label>
        <?= html_text('name', 'maxlength="100" class="input-field"') ?>
        <?= err('name') ?>

        <label>Gender</label>
        <?= html_radios('gender', $_genders, 'class="input-field"') ?>
        <?= err('gender') ?>

        <label for="photo">Photo</label> 
        <label class="upload">
            <?= html_file('photo', 'image/*') ?>
            <img src="/user_photos/<?= $photo ?>" alt="">
        </label>
        <?= err('photo') ?>

        <section class="form-actions">
            <button type="submit">Submit</button>
            <button type="reset">Reset</button>
        </section>
    </form>
</div>

</body>

<?php
include '../_foot.php';
?>
