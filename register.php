<?php
include('storage.php');
include('usersstorage.php');
include('auth.php');

$usersStorage = new UsersStorage();
$auth = new Auth($usersStorage);

$data = [];
$errors = [];
if (count($_POST) > 0) {
    if (validate($auth, $data, $errors)) {
        $auth->register($data);
        redirect('login.php');
    }
}

function validate($auth, &$data, &$errors) {
    if (!isset($_POST['username'])) {
        $errors['username'] = "Username is required.";
    } else if (trim($_POST['username']) === '') {
        $errors['username'] = "Username must not be empty.";
    } else if ($auth->user_exists($_POST['username'])) {
        $errors['username'] = "This username is already used.";
    }

    if (!isset($_POST['email'])) {
        $errors['email'] = "Email is required.";
    } else if (trim($_POST['email']) === '') {
        $errors['email'] = "Email must not be empty.";
    } else if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    }

    if (!isset($_POST['password'])) {
        $errors['password'] = "Password is required.";
    } else if (trim($_POST['password']) === '') {
        $errors['password'] = "Password must not be empty.";
    }

    if (!isset($_POST['repeat'])) {
        $errors['repeat'] = "Please confirmation is required.";
    } else if (trim($_POST['repeat']) === '') {
        $errors['repeat'] = "Password must not be empty.";
    } else if ($_POST['repeat'] !== $_POST['password']) {
        $errors['repeat'] = "Passwords do not match.";
    }

    $data = $_POST;
    return count($errors) === 0;
}

function redirect($page) {
    header("Location: ${page}");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Registration</title>
</head>
<body>
    <h1>ELTE Stadium</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="login.php">Login</a>
    </nav>
    <form action="" novalidate method="post">
        <label for="username">Username: </label>
        <input type="text" name="username" value="<?= $_POST['username'] ?? ''?>" required>
        <?php if (isset($errors['username'])): ?>
            <span style="color: red"><?= $errors['username'] ?></span>
        <?php endif ?><br>
        <label for="email">Email: </label>
        <input type="email" name="email" value="<?= $_POST['email'] ?? '' ?>" required>
        <?php if (isset($errors['email'])): ?>
            <span style="color: red"><?= $errors['email'] ?></span>
        <?php endif ?><br>
        <label for="password">Password: </label>
        <input type="password" name="password" value="<?= $_POST['password'] ?? '' ?>" required>
        <?php if (isset($errors['password'])): ?>
            <span style="color: red"><?= $errors['password'] ?></span>
        <?php endif ?><br>
        <label for="repeat">Repeat Password: </label>
        <input type="password" name="repeat" value="<?= $_POST['repeat'] ?? '' ?>" required>
        <?php if (isset($errors['repeat'])): ?>
            <span style="color: red"><?= $errors['repeat'] ?></span>
        <?php endif ?><br>
        <button type="submit">Register</button><br>
    </form>
</body>
</html>