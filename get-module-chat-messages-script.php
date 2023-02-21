<?php
session_start();
include('../config/database.php');
require_once('../classes/moduleChatClass.php');

if (isset($_GET['id'])) {
    //response array declaration
    $response= array();
    // get database connection
    $database = new Database();
    $db = $database->getDbConnection();

    //get user id from session
    //$userId = $_SESSION['userId'];
    $lastMessageId = $_GET['id'];
    // pass connection to objects
    $chat = new ModuleChatClass($db);

    $response  = $chat->GetAllModuleChatMessagesByModuleChatId($_SESSION['moduleGroupChatId'],$lastMessageId,$_SESSION['userId']);
    
    echo json_encode($response);
}