<?php
include('storage.php');
include('commentsstorage.php');

session_start();
$commentsStorage = new CommentsStorage();

if(count($_GET) > 0){
    $comment = $commentsStorage->findOne(['id' => $_GET['commentId']]);
    $teamId = $comment['teamid'];
    $commentsStorage->deleteCommentById($_GET['commentId']);
    redirect("teamdetails.php?teamId=".$teamId);
}

function redirect($page) {
    header("Location: ${page}");
    exit();
}
?>