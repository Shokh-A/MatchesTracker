<?php
include('storage.php');
include('matchesstorage.php');

session_start();
$matchesStorage = new MatchesStorage();

$data = [];
$errors = [];
if (count($_POST) > 0) {
    if (validate($data, $errors)) {
        $matchesStorage->modifyDataById($_GET['matchId'], $data['newDate'], $data['newScoreTeam1'], $data['newScoreTeam2']);
        redirect('teamdetails.php?teamId='.$_GET['teamId']);
    }
}

function validate(&$data, &$errors) {
    if (!isset($_POST['newDate'])) {
        $errors['newDate'] = 'Date is required.';
    } else if (trim($_POST['newDate']) === '') {
        $errors['newDate'] = 'Date must not be empty.';
    } else if (!strtotime($_POST['newDate'])) {
        $errors['newDate'] = 'Date must be in yyyy-mm-dd format.';
    }

    if (!isset($_POST['newScoreTeam1'])) {
        $errors['newScoreTeam1'] = 'Score is required.';
    } else if (trim($_POST['newScoreTeam1']) !== '' && trim($_POST['newScoreTeam2']) === '') {
        $errors['newScoreTeam1'] = 'Both teams must or must not have a score.';
    } else if (trim($_POST['newScoreTeam1']) === '' && trim($_POST['newScoreTeam2']) === '') {
        $data['newScoreTeam1'] = '';
    } else if (!is_numeric($_POST['newScoreTeam1'])) {
        $errors['newScoreTeam1'] = 'Score must be number or empty.';
    }

    if (!isset($_POST['newScoreTeam2'])) {
        $errors['newScoreTeam2'] = 'Score is required.';
    } else if (trim($_POST['newScoreTeam1']) === '' && trim($_POST['newScoreTeam2']) !== '') {
        $errors['newScoreTeam1'] = 'Both teams must or must not have a score.';
    } else if (trim($_POST['newScoreTeam1']) === '' && trim($_POST['newScoreTeam2']) === '') {
        $data['newScoreTeam2'] = '';
    } else if (!is_numeric($_POST['newScoreTeam2'])) {
        $errors['newScoreTeam2'] = 'Score must be number or empty.';
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
    <title>Data Changes</title>
</head>
<body>
    <h1>ELTE Stadium</h1>
    <form action="" novalidate method="post">
        <label for="newDate">New date: </label>
        <input type="text" name="newDate">
        <?php if (isset($errors['newDate'])): ?>
            <span style="color: red"><?= $errors['newDate'] ?></span>
        <?php endif ?><br>
        <label for="newScoreTeam1">New score for team 1: </label>
        <input type="number" name="newScoreTeam1">
        <?php if (isset($errors['newScoreTeam1'])): ?>
            <span style="color: red"><?= $errors['newScoreTeam1'] ?></span>
        <?php endif ?><br>
        <label for="newScoreTeam2">New score for team 2: </label>
        <input type="number" name="newScoreTeam2">
        <?php if (isset($errors['newScoreTeam2'])): ?>
            <span style="color: red"><?= $errors['newScoreTeam2'] ?></span>
        <?php endif ?><br>
        <button type="submit">Save Changes</button>
    </form>
</body>
</html>