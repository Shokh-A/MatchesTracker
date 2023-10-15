<?php
include('storage.php');
include('usersstorage.php');
include('auth.php');

session_start();
$usersStorage = new UsersStorage();
$auth = new Auth($usersStorage);

$auth->logout();
if (isset($_GET['teamId'])) {
    redirect('teamdetails.php?teamId='.$_GET['teamId']);
} else {
    redirect('index.php');
}

function redirect($page) {
    header("Location: ${page}");
    exit();
}
?>