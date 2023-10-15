<?php 
include('storage.php');
include('usersstorage.php');
include('auth.php');

session_start();
$usersStorage = new UsersStorage();
$auth = new Auth($usersStorage);

$data = [];
$errors = [];
if (count($_POST) > 0) {
    if (validate($data, $errors)) {
        $auth_user = $auth->authenticate($data['username'], $data['password']);
        if (!$auth_user) {
            $errors['global'] = "Username or password is wrong.";
        } else {
            $auth->login($auth_user);
            if (isset($_GET['teamId'])) {
                redirect('teamdetails.php?teamId='.$_GET['teamId']);
            } else {
                redirect('index.php');
            }
        }
    }
}

function validate(&$data, &$errors) {
    if (!isset($_POST['username'])) {
        $errors['username'] = "Username is required.";
    } else if (trim($_POST['username']) === '') {
        $errors['username'] = "Username must not be empty.";
    }

    if (!isset($_POST['password'])) {
        $errors['password'] = "Password is required.";
    } else if (trim($_POST['password']) === '') {
        $errors['password'] = "Password must not be empty.";
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
    <title>Login</title>
</head>
<body>
    <h1>ELTE Stadium</h1>
    <nav>
        <a href="index.php">Home</a>
        <a href="register.php">Register</a>
    </nav>
    <form action="" novalidate method="post">
        <?php if (isset($errors['global'])): ?>
            <span style="color: red"><?= $errors['global'] ?></span>
        <?php endif ?><br>
        <label for="username">Username: </label>
        <input type="text" name="username" required>
        <?php if (isset($errors['username'])): ?>
            <span style="color: red"><?= $errors['username'] ?></span>
        <?php endif ?><br>
        <label for="password">Password: </label>
        <input type="password" name="password" required>
        <?php if (isset($errors['password'])): ?>
            <span style="color: red"><?= $errors['password'] ?></span>
        <?php endif ?><br>
        <button type="submit">Login</button><br>
    </form>
</body>
</html>