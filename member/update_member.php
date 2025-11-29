<?php
require '../_base.php';
// ----------------------------------------------------------------------------

if (is_get()) {
    $id = req('id');

    $stm = $_db->prepare('SELECT * FROM user WHERE id = ?');
    $stm->execute([$id]);
    $s = $stm->fetch();

    if (!$s) {
        redirect('member_list.php');
    }

    extract((array)$s);
    $_SESSION['photo'] = $s->photo;
}

if (is_post()) {
    $id = req('id');
    $email = req('email');
    $name = req('name');
    $gender = req('gender');
    $photo = $_SESSION['photo'];
    $f = get_file('photo');

    // Fetch current user email
    $current_email = $s['email']; // Assuming $s contains the current user data

    // Validate: email
    if ($email == '') {
        $_err['email'] = 'Required';
    }
    else if (strlen($email) > 100) {
        $_err['email'] = 'Maximum 100 characters';
    }
    else if (!is_email($email)) {
        $_err['email'] = 'Invalid email';
    }
    else {
        // Only check for duplicates if the email is changed
        if ($email !== $current_email) {
            $stm = $_db->prepare('
                SELECT COUNT(*) FROM user
                WHERE email = ? AND id != ? 
            ');
            $stm->execute([$email, $id]); // Use $id, which is already set
            if ($stm->fetchColumn() > 0) {
                $_err['email'] = 'Duplicated';
            }
        }
    }

    // Validate name
    if ($name == '') {
        $_err['name'] = 'Required';
    } else if (strlen($name) > 100) {
        $_err['name'] = 'Maximum length 100';
    }

    // Validate gender
    if ($gender == '') {
        $_err['gender'] = 'Required';
    } else if (!array_key_exists($gender, $_genders)) {
        $_err['gender'] = 'Invalid value';
    }

    // Validate and handle photo
    if ($f) {
        if (!str_starts_with($f->type, 'image/')) {
            $_err['photo'] = 'Must be an image';
        } else if ($f->size > 1 * 1024 * 1024) {
            $_err['photo'] = 'Maximum 1MB';
        } else {
            if (file_exists("../user_photos/$photo")) {
                unlink("../user_photos/$photo");
            }
            $photo = save_photo($f, '../user_photos');
            $_SESSION['photo'] = $photo;
        }
    }

    // Save to DB
    if (!$_err) {
        $stm = $_db->prepare('
            UPDATE user
            SET email = ?, name = ?, gender = ?, photo = ?
            WHERE id = ?
        ');
        $stm->execute([$email, $name, $gender, $photo, $id]);

        temp('info', 'Record updated');
        redirect('member_list.php');
    }
}



// ----------------------------------------------------------------------------
$_title = 'Update';
include '../_head.php';
?>


<link rel="stylesheet" href="/css/user_maintenance.css">
<div class="container">
<form method="post" class="form" enctype="multipart/form-data">
    <label for="email">Email</label>
    <?= html_text('email', 'maxlength="100"') ?>
    <?= err('email') ?>

    <label for="name">Name</label>
    <?= html_text('name', 'maxlength="100"') ?>
    <?= err('name') ?>
    
    <label for="gender">Gender</label>
    <div class="radio-group">
    <?= html_radios('gender', $_genders) ?>
    <?= err('gender') ?>
    </div>
    <br>
    <label for="photo">Photo</label>
    <label class="upload">
        <?= html_file('photo', 'image/*') ?>
        <img src="/user_photos/<?= $photo ?>" alt="Current photo">
    </label>
    <?= err('photo') ?>

    <section>
        <button>Submit</button>
        <button type="reset">Reset</button>
    </section>
</form>
</div>
<?php
include '../_foot.php';
