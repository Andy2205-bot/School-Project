<?php
session_start();
include('../config/database.php');
require_once('../classes/chatClass.php');

if (isset($_GET['id'])) {
    //response array declaration
    $response= array();
    // get database connection
    $database = new Database();
    $db = $database->getDbConnection();

    //get user id from session
    //$userId = $_SESSION['userId'];
    $lastChatId = $_GET['id'];
    // pass connection to objects
    $chat = new ChatClass($db);

    $response  = $chat->GetAllChatsByUser($_SESSION['userId'], $lastChatId);
    
    echo json_encode($response);
}
