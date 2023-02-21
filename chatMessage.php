<?php
require_once('ebase.php');

class ChatMessageClass extends Ebase
{
    private $table_name = "chat_messages";
    //properties
    public $peerOne;
    public $chatId;
    public $senderId;
    public $receiverId;
    public $isFile;
    public $isImage;
    public $message;

    //function to get all titles
    public function GetAllTitles()
    {
        //creating sql query statement
        $query = "SELECT * From " . $this->table_name . " ORDER BY name ASC";

        //accessing getDbConnection() function from base class to get connection string
        $stmt = $this->getDbConnection()
            ->prepare($query);
        $stmt->execute();

        //returning result statement
        return $stmt;
    }

    //function to get chat id
    public function GetChatId($peerOne, $peerTwo)
    {
        $query = "SELECT id FROM chats WHERE peer_one = :uPeerOne and peer_two =:uPeerTwo LIMIT 0,1";

        $stmt = $this->getDbConnection()
            ->prepare($query);
        $stmt->execute(array(
            ':uPeerOne' => $peerOne,
            ':uPeerTwo' => $peerTwo
        ));

        $chat_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($stmt->rowCount() > 0) {
            extract($chat_data);
            return $id;
        } else {
            return 0;
        }
    }

    //function to create new chat
    function SendNewMessage($chatId, $senderId, $receiverId,$isFile,$isImage,$message)
    {
        //posted values
        $this->chatId = $chatId;
        $this->senderId = $senderId;
        $this->receiverId = $receiverId;
        $this->isFile = $isFile;
        $this->isImage = $isImage;
        $this->message = $message;

        $query = "INSERT INTO chat_messages SET chat_id = :uChatId, sender_id = :uSenderId, receiver_id=:uReceiverId,message=:uMessage,is_file=:uIsFile,is_image=:uIsImage";

        // bind values
        $stmt = $this->getDbConnection()->prepare($query);
        $stmt->bindParam(":uChatId", $this->chatId);
        $stmt->bindParam(":uSenderId", $this->senderId);
        $stmt->bindParam(":uReceiverId", $this->receiverId);
        $stmt->bindParam(":uMessage",  $this->message);
        $stmt->bindParam(":uIsFile",  $this->isFile);
        $stmt->bindParam(":uIsImage",  $this->isImage);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    //fuhction to get AllUserChat for dashboard
    public function GetAllUserMessagesByChatId($chatId,$lastMessageId,$userId)
    {
        $lastChatIndex=0;      
        $response = array();
        $peerDetails=array();
        //creating sql query statement
        $query = "SELECT chat_messages.id as messageId, chat_messages.message as message, chat_messages.is_file as isFile, chat_messages.is_image as isImage,chat_messages.is_read as isRead, chat_messages.send_time as sendTime, chat_messages.sender_id as senderId, chat_messages.receiver_id as receiverId
        FROM `chat_messages` 
        WHERE (chat_messages.chat_id = :uChatId) and chat_messages.id>:uLastMessageId ORDER BY send_time ASC";
        
        $stmt = $this->getDbConnection()->prepare($query);

        $stmt->bindParam(':uLastMessageId', $lastMessageId, PDO::PARAM_INT);
        $stmt->bindParam(":uChatId", $chatId);

        $stmt->execute();

        //accessing getDbConnection() function from base class to get connection string

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            //extract data
            extract($row);
            $isSender=0;
            $lastChatIndex= $messageId;
            $chatId = $messageId;

            //get

            if ($senderId == $userId) {
                # get 
                $isSender= 1;
            } else {
                # get 
                $isSender = 0;
               
            }
            //add to return array
            $response[] = array(
                'messageId' => $messageId,
                'message' => $message,
                'isFile' => $isFile,
                'isImage' => $isImage,
                'isRead' => $isRead,
                'sendTime' => $sendTime,
                'senderId' => $senderId,
                'recceiverId ' => $receiverId ,
                'isSender' => $isSender,
                'lastChatIndex' =>$lastChatIndex
            );
        }

        //returning result statement
        return $response;
    }

    //function to get peer details
    public function GetUserDetails($userId,$chatId)
    {
        $response = array();

        //user data query
        $user_query = "";
        //query statement
        $query = "SELECT id, is_staff  FROM users WHERE id = :uUserId LIMIT 0,1";

        $stmt = $this->getDbConnection()
            ->prepare($query);
        $stmt->execute(array(
            ':uUserId' => $userId
        ));

        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($stmt->rowCount() > 0) {
            extract($user_data);

            if ($is_staff == 1) {
                //get staff details
                $user_query =  "SELECT users.id As userId, staff.firstnames, staff.lastname, staff.email, users.is_active as iSActive
                FROM `users` 
                JOIN staff ON staff.id = users.staff_id
                WHERE users.id =:uUId";
            } else {
                //get student details
                $user_query = "SELECT users.id As userId, students.firstnames, students.lastname, students.email, users.is_active as iSActive, programs.name as department, student_levels.name as studentLevel
                FROM `users` 
                JOIN students ON students.id = users.student_id 
                JOIN programs On programs.id = students.program_id
                JOIN student_levels ON student_levels.id = students.level_id
                WHERE users.id =:uUId";
            }

            //get user details
            $user_stmt = $this->getDbConnection()
                ->prepare($user_query);
            $user_stmt->execute(array(':uUId' => $userId));

            $user_details = $user_stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user_stmt->rowCount() > 0) {
                extract($user_details);

                $response['chatId'] = $chatId;
                $response['userId'] = $userId;
                $response['firstnames'] = $firstnames;
                $response['lastname'] = $lastname;
                $response['email'] = $email;
                $response['iSActive'] = $iSActive;
                $response['department'] = $department;
                $response['studentLevel'] = $studentLevel;

                return $response;
            }
            return $response;
        }
        return $response;
    }
}
