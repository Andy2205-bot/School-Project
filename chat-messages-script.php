<?php
session_start();
include('../config/database.php');
require_once('../classes/chatMessage.php');

if (isset($_SESSION['chatId'])) {
    //response array declaration
    $response = array();
    // get database connection
    $database = new Database();
    $db = $database->getDbConnection();
    // pass connection to objects
    $chat = new ChatMessageClass($db);

    //$response  = $chat->GetAllChatsByUser($_SESSION['userId'], $lastChatId);

    // echo json_encode($response);

    if (isset($_POST['message'])) {

        $mess = ($_POST['message']);

        if ($chat->SendNewMessage($_SESSION['chatId'],$_SESSION['userId'],$_SESSION['rid'],0,0, $mess)) {
            $response['error'] = false;
        $response['message'] = "message send.";
        } else {
            $response['error'] = false;
        $response['message'] = "send failed.chatId: ".$_SESSION['chatId']." userId: (".$_SESSION['userId'].") receiverId: (".$_SESSION['rid'].") Message: ".$mess;
        }    

    } else {
        $response['error'] = true;
        $response['message'] = "Required fields missing!";
    }

    echo json_encode($response);
}
