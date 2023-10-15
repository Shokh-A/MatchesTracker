<?php
include('storage.php');
include('teamsstorage.php');
include('matchesstorage.php');
include('commentsstorage.php');
include('usersstorage.php');
include('auth.php');

session_start();
$teamsStorage = new TeamsStorage();
$matchesStorage = new MatchesStorage();
$commentsStorage = new CommentsStorage();

$usersStorage = new UsersStorage();
$auth = new Auth($usersStorage);
$logged = $auth->is_authenticated();
$user = $auth->authenticated_user();

$teamId = $_GET['teamId'];
$matches = $matchesStorage->getTeamMatchesById($teamId);
$comments = $commentsStorage->getTeamCommentsById($teamId);

$data = [];
$errors = [];
if (count($_POST) > 0) {
    if (validate($data, $errors)) {
        $commentsStorage->addComment($user['id'], $data['newComment'], $teamId);
        $comments = $commentsStorage->getTeamCommentsById($teamId);
    }
}

function validate(&$data, &$errors) {
    if (!isset($_POST['newComment'])) {
        $errors['newComment'] = "Comment is required.";
    } else if (trim($_POST['newComment']) === '') {
        $errors['newComment'] = "Comment must not be empty.";
    }

    $data = $_POST;
    return count($errors) === 0;
}

function isWinner($match, $teamId) {
    if ($match['home']['id'] === $teamId) {
        if ($match['home']['score'] > $match['away']['score']) return 'green';
        else if (!is_numeric($match['home']['score'])) return 'white';
        else if ($match['home']['score'] === $match['away']['score']) return 'yellow';
        else if ($match['home']['score'] < $match['away']['score']) return 'red';
    } else if ($match['away']['id'] === $teamId) {
        if ($match['home']['score'] > $match['away']['score']) return 'red';
        else if (!is_numeric($match['away']['score'])) return 'white';
        else if ($match['home']['score'] === $match['away']['score']) return 'yellow';
        else if ($match['home']['score'] < $match['away']['score']) return 'green';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Team Details - <?= $teamsStorage->getTeamById($teamId)['name'] ?></title>
</head>
<body>
    <h1>Team - <?= $teamsStorage->getTeamById($teamId)['name'] ?></h1>
    <nav>
        <a href="index.php">Home</a>
        <?php if (!$logged): ?>
            <a href="login.php?teamId=<?= $teamId ?>">Login</a>
            <a href="register.php">Register</a>
        <?php endif ?>
        <?php if ($logged): ?>
            <a href="logout.php?teamId=<?= $teamId ?>">Log out</a>
        <?php endif ?>
    </nav>
    <div>
        <h3>Matches</h3>
        <table>
            <tr>
                <th>Home</th>
                <th>Score</th>
                <th>Away</th>
                <th>Date</th>
            </tr>
            <?php foreach($matches as $match): ?>
            <tr>
                <td><?= $teamsStorage->getTeamById($match['home']['id'])['name'] ?></td>
                <td style="background-color: <?= isWinner($match, $teamId) ?>"><?= $match['home']['score'] ?> : <?= $match['away']['score'] ?></td>
                <td><?= $teamsStorage->getTeamById($match['away']['id'])['name'] ?></td>
                <td><?= $match['date'] ?></td>
                <?php if ($user['username'] === 'admin'): ?>
                <td><a href="modifydata.php?matchId=<?= $match['id'] ?>&teamId=<?= $teamId ?>">Modify Data</a></td>
                <?php endif ?>
            </tr>
            <?php endforeach ?>
        </table>
    </div>
    <div>
        <h3>Comments</h3>
        <ul>
        <?php foreach($comments as $comment): ?>
            <li class="comment"><strong>User:</strong> <?= $usersStorage->getUserNameById($comment['author'])['username'] ?><br><strong>Comment:</strong> <?= $comment['text'] ?> <?php if ($user['username'] === 'admin'): ?><a href="deletecomment.php?commentId=<?= $comment['id'] ?>">Delete comment</a><?php endif ?></li>
        <?php endforeach ?>
        </ul>
    </div>
    <div>
        <?php if (!$logged): ?>
            <p>Please <a href="login.php?teamId=<?= $teamId ?>">Login</a> to leave a comment.</p>
        <?php endif ?>
        <?php if ($logged && $user['username'] !== 'admin'): ?>
            <form action="" novalidate method="post">
                <h4>Leave a comment: </h4>
                <textarea name="newComment" cols="30" rows="10" style="resize: none"></textarea>
                <?php if (isset($errors['newComment'])): ?>
                    <span style="color: red"><?= $errors['newComment'] ?></span>
                <?php endif ?><br>
                <button type="submit">Save comment</button>
            </form>
        <?php endif ?>
    </div>
</body>
</html>