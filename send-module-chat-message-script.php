<?php
session_start();
include('../config/database.php');
require_once('../classes/moduleChatClass.php');

if (isset($_SESSION['moduleGroupChatId'])) {
    //response array declaration
    $response = array();
    // get database connection
    $database = new Database();
    $db = $database->getDbConnection();
    // pass connection to objects
    $moduleChat = new ModuleChatClass($db);
    // echo json_encode($response);

    if (isset($_POST['message'])) {

        $mess = ($_POST['message']);

        if ($moduleChat->SendNewMessage($_SESSION['moduleGroupChatId'],$_SESSION['userId'],0,0, $mess)) {
            $response['error'] = false;
            $response['message'] = "message send.";
        } else {
            $response['error'] = true;
            $response['message'] = "send failed.chatId: ".$_SESSION['chatId']." userId: (".$_SESSION['userId'].") Message: ".$mess;
        }    

    } else {
        $response['error'] = true;
        $response['message'] = "Required fields missing!";
    }

    echo json_encode($response);
}
