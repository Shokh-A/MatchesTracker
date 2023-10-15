<?php
include('storage.php');
include('matchesstorage.php');
include('teamsstorage.php');

session_start();
$matchesStorage = new MatchesStorage();
$teamsStorage = new TeamsStorage();
$from = $_GET['from'];
$matches = $matchesStorage->getNextFive($from);

$matchesList = [];
$container = [];
foreach($matches as $match){
    $container['date'] = $match['date'];
    $container['team1'] = $teamsStorage->getTeamById($match['home']['id'])['name'];
    $container['team2'] = $teamsStorage->getTeamById($match['away']['id'])['name'];
    $container['home']['score'] = $match['home']['score'];
    $container['away']['score'] = $match['away']['score'];
    array_push($matchesList, $container);
}

echo json_encode($matchesList);
?>