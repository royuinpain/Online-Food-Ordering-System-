<?php
include '../_base.php';

// ----------------------------------------------------------------------------
// Authenticated users
auth();

if (is_get()) {
    $stm = $_db->prepare('SELECT * FROM user WHERE id=?');
    $stm->execute([$_user->id]);
    $u = $stm->fetch();

    if (!$u) {
        redirect('/');
    }

    extract((array)$u);
    $_SESSION['photo'] = $u->photo;
}

if (is_post()) {
    $email = req('email');
    $name  = req('name');
    $gender = req('gender');  
    $photo = $_SESSION['photo'];
    $f = get_file('photo');

    if ($email == '') {
        $_err['email'] = 'Required';
    } else if (strlen($email) > 100) {
        $_err['email'] = 'Maximum 100 characters';
    } else if (!is_email($email)) {
        $_err['email'] = 'Invalid email';
    } else {
        $stm = $_db->prepare('SELECT COUNT(*) FROM user WHERE email = ? AND id != ?');
        $stm->execute([$email, $_user->id]);
        if ($stm->fetchColumn() > 0) {
            $_err['email'] = 'Duplicated';
        }
    }

    if ($name == '') {
        $_err['name'] = 'Required';
    } else if (strlen($name) > 100) {
        $_err['name'] = 'Maximum 100 characters';
    }

    
    // Validate gender
    if ($gender == '') {
        $_err['gender'] = 'Required';
    } else if (!array_key_exists($gender, $_genders)) {
        $_err['gender'] = 'Invalid value';
    }

    if ($f) {
        if (!str_starts_with($f->type, 'image/')) {
            $_err['photo'] = 'Must be an image';
        } else if ($f->size > 1 * 1024 * 1024) {
            $_err['photo'] = 'Maximum 1MB';
        }
    }

  

    if (!$_err) {
        if ($f) {
            unlink("../user_photos/$photo");
            $photo = save_photo($f, '../user_photos');
        }

        $stm = $_db->prepare('UPDATE user SET email = ?, name = ?, gender = ?, photo = ? WHERE id = ?');
        $stm->execute([$email, $name, $gender, $photo, $_user->id]);

        $_user->email = $email;
        $_user->name = $name;
        $_user->gender = $gender;
        $_user->photo = $photo;

        temp('info', 'Record updated');
        redirect('/');
    }
}

$_title = 'User | Profile';
include '../_head.php';
?>

<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #fffaf5;
        color: #333;
    }

    form.form {
        width: 500px; /* fixed width */
        padding: 20px;
        margin: 40px 20px; /* ONLY margin to the top and little on left */
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 0 8px rgba(0,0,0,0.1);
    }

    label {
        font-weight: bold;
        margin-top: 10px;
        display: block;
    }

    input[type="text"],
    input[type="email"],
    input[type="file"],
    select {
        width: 100%;
        padding: 8px;
        margin-top: 5px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .upload img {
        margin-top: 10px;
        width: 100px;
        height: 100px;
        object-fit: cover;
        border: 1px solid #ccc;
        border-radius: 8px;
    }

    .form section {
        margin-top: 20px;
    }

    .form section button {
        padding: 10px 20px;
        margin-right: 10px;
        background-color: #705222;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
    }

    .form section button:hover {
        background-color: #5a411b;
    }

    .button-link {
    display: inline-block;
    padding: 10px 20px;
    margin-top: 10px;
    margin-right: 10px;
    background-color: #705222;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    text-decoration: none;
    text-align: center;
}

.button-link:hover {
    background-color: #5a411b;
}

</style>

<form method="post" class="form" enctype="multipart/form-data">
    <label for="email">Email</label>
    <?= html_text('email', 'maxlength="100"') ?>
    <?= err('email') ?>

    <label for="name">Name</label>
    <?= html_text('name', 'maxlength="100"') ?>
    <?= err('name') ?>

    <label for="gender">Gender</label>
    <?= html_radios('gender', $_genders) ?>
    <?= err('gender') ?>

    <label for="photo">Photo</label>
    <label class="upload">
        <?= html_file('photo', 'image/*') ?>
        <img src="/user_photos/<?= htmlspecialchars($photo) ?>">
    </label>
    <?= err('photo') ?>

  <section>
    <button type="submit">Submit</button>
    <button type="reset">Reset</button>
    <a href="password.php" class="button-link">Reset Password</a>
</section>

</form>

<?php
include '../_foot.php';
?>
