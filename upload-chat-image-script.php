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
    if (is_array($_FILES)) {
        if (is_uploaded_file($_FILES['userImage']['tmp_name'])) {
            $nameEx = "image".$_SESSION['chatId']."".$_SESSION['userId']."".$_SESSION['rid'];

            $sourcePath = $_FILES['userImage']['tmp_name'];
            $targetPath = "C:/xampp/htdocs/projects/gzu.chat/pages/assets/chat-images/".$nameEx."". $_FILES['userImage']['name'];
            if (move_uploaded_file($sourcePath, $targetPath)) {
                $saveName = $nameEx."". $_FILES['userImage']['name'];

                if ($chat->SendNewMessage($_SESSION['chatId'],$_SESSION['userId'],$_SESSION['rid'],0,1, $saveName)) {
                    $response['error'] = false;
                $response['message'] = "message send.";
                } else {
                    $response['error'] = false;
                $response['message'] = "send failed.chatId: ".$_SESSION['chatId']." userId: (".$_SESSION['userId'].") receiverId: (".$_SESSION['rid'].") Message: ".$mess;
                } 
            }
        }
    }
    echo json_encode($response);
}
