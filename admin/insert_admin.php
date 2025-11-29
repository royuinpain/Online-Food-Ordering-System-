<?php
require '../_base.php';
// ----------------------------------------------------------------------------

$_err = [];

if (is_post()) {
    $email    = req('email');
    $gender   = req('gender');
    $password = req('password');
    $name     = req('name');
    $f = get_file('photo');

    // Validate: email
    if ($email == '') {
        $_err['email'] = 'Required';
    } elseif (strlen($email) > 100) {
        $_err['email'] = 'Maximum 100 characters';
    } elseif (!is_email($email)) {
        $_err['email'] = 'Invalid email';   
    } else {
        $stm = $_db->prepare('SELECT COUNT(*) FROM user WHERE email = ?');
        $stm->execute([$email]);
        if ($stm->fetchColumn() > 0) {
            $_err['email'] = 'Duplicated';
        }
    }

    // Validate: password
    if (!$password) {
        $_err['password'] = 'Required';
    }
    else if (strlen($password) < 5 || strlen($password) > 100) {
        $_err['password'] = 'Between 5-100 characters';
    }


    // Validate name
    if ($name == '') {
        $_err['name'] = 'Required';
    } elseif (strlen($name) > 100) {
        $_err['name'] = 'Maximum length 100';
    }

    // Validate gender
    if ($gender == '') {
        $_err['gender'] = 'Required';
    } elseif (!array_key_exists($gender, $_genders)) {
        $_err['gender'] = 'Invalid value';
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
                VALUES (?,SHA1(?),?,?,?,"Admin")
            ');
            $stm->execute([$email, $password, $name, $gender, $photo]);
    
            temp('info', 'Record inserted');
            redirect('/admin/admin_list.php');
        }
    }

// ----------------------------------------------------------------------------
$_title = 'Insert Admin';
include '../_head.php';
?>

<link rel="stylesheet" href="/css/user_maintenance.css">

<div class="container">
    <h2>Insert Admin</h2>

    
    <form method="post" class="form" enctype="multipart/form-data">
        <label for="email">Email</label>
        <?= html_text('email', 'maxlength="100" class="input-field"') ?>
        <?= err('email') ?>

        <label for="password">Password</label>
        <?= html_password('password', 'maxlength="100" class="input-field"') ?>
        <?= err('password') ?>

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

        <section>
            <button type="submit">Submit</button>
            <button type="reset">Reset</button>
        </section>
    </form>
</div>

<?php include '../_foot.php'; ?>
