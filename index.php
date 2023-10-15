<?php 
include('storage.php');
include('teamsstorage.php');
include('matchesstorage.php');
include('usersstorage.php');
include('auth.php');

session_start();
$teamsStorage = new TeamsStorage();
$teams = $teamsStorage->findAll();

$matchesStorage = new MatchesStorage();
$matches = $matchesStorage->getNextFive(1);

$usersStorage = new UsersStorage();
$auth = new Auth($usersStorage);
$logged = $auth->is_authenticated();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>ELTE Stadium</title>
</head>
<body>
    <header>
        <h1>ELTE Stadium</h1>
        <nav>
            <?php if (!$logged): ?>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>
            <?php endif ?>
            <?php if ($logged): ?>
                <a href="logout.php">Log out</a>
            <?php endif ?>
        </nav>
    </header>
    <div>
        <h2>Description</h2>
        <p>In this web page you can find mathces played at ELTE Stadium and see a match results of your favourite teams.</p>
    </div>
    <div>
        <h2>Teams</h2>
        <ul>
        <?php foreach($teams as $team): ?>
            <li><a href="teamdetails.php?teamId=<?= $team['id'] ?>"><?= $team['name'] ?></a></li>
        <?php endforeach ?>
        </ul>
    </div>
    <div>
        <h3>The last 5 matches played at ELTE Stadium</h3>
        <table>
            <tr>
                <th>Home</th>
                <th>Score</th>
                <th>Away</th>
                <th>Date</th>
            </tr>
            <tbody id="matches">
            <?php foreach($matches as $match) : ?>
                <tr>
                    <td><?= $teamsStorage->getTeamById($match['home']['id'])['name'] ?></td>
                    <td><?=$match['home']['score']?> : <?= $match['away']['score']?></td>
                    <td><?= $teamsStorage->getTeamById($match['away']['id'])['name'] ?></td>
                    <td><?=$match['date']?></td>
                </tr>
            <?php endforeach?>
            </tbody>
        </table>
        <div><button id="next" type="submit">Next</button></div>
    </div>
    <script src="next.js"></script>
</body>
</html>