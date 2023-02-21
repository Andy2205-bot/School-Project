<?php
session_start();
include('../config/database.php');
require_once('../classes/chatClass.php');

if (isset($_GET['id'])) {

    // get database connection
    $database = new Database();
    $db = $database->getDbConnection();
    $chatId = 0;
    $peerTwoId = $_GET['id'];
    // pass connection to objects
    $chat = new ChatClass($db);

    $chatId  = $chat->createNewChat($_SESSION['userId'], $peerTwoId, 1);

    if ($chatId > 0) {
        echo "<script type='text/javascript'>;";
        echo "window.location.href='../pages/chat-room.php?chatId=".$chatId."&rid=".$peerTwoId."';";
        echo "</script>";
    } else {
        echo "<script type='text/javascript'>;";
        echo "window.location.href='../pages/socials.php';";
        echo "</script>";
    }


    $msg = $peerTwoId;
    echo "<script type='text/javascript'>alert('$msg');";
    echo "</script>";
}
