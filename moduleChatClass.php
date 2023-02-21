<?php
require_once('ebase.php');

class ModuleChatClass extends Ebase
{
    private $table_name = "chat_messages";
    //properties
    public $peerOne;
    public $chatId;
    public $senderId;
    public $isFile;
    public $isImage;
    public $message;
    //function to get chat id
    public function GetModuleGroupChatId($moduleId, $sessionId)
    {
        $query = "SELECT id FROM group_chats WHERE module_id = :uModuleId and academic_session_id =:uSessionId LIMIT 0,1";

        $stmt = $this->getDbConnection()
            ->prepare($query);
        $stmt->execute(array(
            ':uModuleId' => $moduleId,
            ':uSessionId' => $sessionId
        ));

        $chat_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($stmt->rowCount() > 0) {
            extract($chat_data);
            return $id;
        } else {
            return 0;
        }
    }
    //function to module details
    public function GetModuleDetailsByGroupChatId($moduleId)
    {
        $response = array();
        $query = "SELECT lecturer_modules.module_id as moduleId, 
         lecturer_modules.lecturer_id as lectureId,
          modules.name as moduleName, 
          programs.name as program,
          modules.code as moduleCode, 
          student_levels.name as level,
          titles.name as title, staff.lastname as lecturerLastname,staff.email as email
         FROM `group_chats` 
         JOIN lecturer_modules ON lecturer_modules.module_id = group_chats.module_id
         JOIN program_modules ON program_modules.id = lecturer_modules.module_id
         JOIN modules ON modules.id = program_modules.module_id
         JOIN programs ON programs.id = program_modules.program_id
         JOIN student_levels ON student_levels.id = program_modules.level_id
         JOIN users ON users.id = lecturer_modules.lecturer_id
         JOIN staff ON staff.id = users.staff_id
         JOIN titles ON titles.id = staff.title_id
         WHERE group_chats.id = :uModuleChatId LIMIT 0,1";

        $stmt = $this->getDbConnection()
            ->prepare($query);
        $stmt->execute(array(
            ':uModuleChatId' => $moduleId
        ));

        $chat_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($stmt->rowCount() > 0) {
            extract($chat_data);
            $response['moduleName'] = $moduleName . " - " . $moduleCode;
            $response['level'] = $level;
            $response['email'] = $email;
            $response['program'] = $program;
            $response['lecturer'] = $title . " " . $lecturerLastname;
            return  $response;
        } else {
            return $response;
        }
    }

    //function to create new chat
    function SendNewMessage($chatId, $senderId, $isFile, $isImage, $message)
    {
        //posted values
        $this->chatId = $chatId;
        $this->senderId = $senderId;
        $this->isFile = $isFile;
        $this->isImage = $isImage;
        $this->message = $message;

        $query = "INSERT INTO group_module_chats SET group_chat_id = :uChatId, sender_id = :uSenderId,message=:uMessage,is_file=:uIsFile,is_image=:uIsImage";

        // bind values
        $stmt = $this->getDbConnection()->prepare($query);
        $stmt->bindParam(":uChatId", $this->chatId);
        $stmt->bindParam(":uSenderId", $this->senderId);
        $stmt->bindParam(":uMessage",  $this->message);
        $stmt->bindParam(":uIsFile",  $this->isFile);
        $stmt->bindParam(":uIsImage",  $this->isImage);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    //fuhction to get AllUserChat for dashboard
    public function GetAllModuleChatMessagesByModuleChatId($chatId, $lastMessageId, $userId)
    {
        $lastChatIndex = 0;
        $response = array();
        $peerDetails = array();
        //creating sql query statement
        $query = "SELECT group_module_chats.`id` as messageId, group_module_chats.`group_chat_id` as groupId, group_module_chats.`sender_id` as senderId, group_module_chats.`message`, group_module_chats.`is_file` as isFile, group_module_chats.`is_image` as isImage, group_module_chats.`is_read` as isRead, group_module_chats.`created_on`, users.email, users.is_staff as isStaff, users.is_active as isActive
        FROM `group_module_chats`
        JOIN users On users.id = group_module_chats.sender_id
        WHERE (group_module_chats.group_chat_id = :uChatId) and group_module_chats.id>:uLastMessageId ORDER BY group_module_chats.created_on ASC";

        $stmt = $this->getDbConnection()->prepare($query);

        $stmt->bindParam(':uLastMessageId', $lastMessageId, PDO::PARAM_INT);
        $stmt->bindParam(":uChatId", $chatId);

        $stmt->execute();

        //accessing getDbConnection() function from base class to get connection string

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            //extract data
            extract($row);

            $lastChatIndex = $messageId;
            $chatId = $messageId;
            $isUserSender = 0;

            //check if user is sender
            if ($userId == $senderId) {
                $isUserSender = 1;
            } else {
                $isUserSender = 0;
            }



            if ($isStaff == 0) {
                # get student data
                $student_data = array();
                $student_data = $this->GetUserDetails($senderId);

                //add to return array
                $response[] = array(
                    'messageId' => $messageId,
                    'message' => $message,
                    'isFile' => $isFile,
                    'isImage' => $isImage,
                    'isRead' => $isRead,
                    'isUserActive' => $isActive,
                    'sendTime' => $created_on,
                    'senderEmail' => $student_data['email'],
                    'senderName' => $student_data['firstnames'] . " " . $student_data['lastname'],
                    'groupId ' => $groupId,
                    'isStaff' => $isStaff,
                    'isSender' => $isUserSender,
                    'lastChatIndex' => $lastChatIndex
                );
            } else {
                # get 
                # get staff data
                $staff_data = array();
                $staff_data = $this->GetUserDetails($senderId);

                //add to return array
                $response[] = array(
                    'messageId' => $messageId,
                    'message' => $message,
                    'isFile' => $isFile,
                    'isImage' => $isImage,
                    'isRead' => $isRead,
                    'isUserActive' => $isActive,
                    'sendTime' => $created_on,
                    'senderEmail' => $staff_data['email'],
                    'senderName' => $staff_data['firstnames'] . " " . $staff_data['lastname'],
                    'groupId ' => $groupId,
                    'isSender' => $isUserSender,
                    'isStaff' => $isStaff,
                    'lastChatIndex' => $lastChatIndex
                );
            }
        }

        //returning result statement
        return $response;
    }

    //function to get peer details
    public function GetUserDetails($userId)
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
                $user_query =  "SELECT users.id As userId, 
                staff.firstnames, 
                staff.lastname, 
                staff.email, 
                depart.name as department,
                users.is_active as iSActive
                FROM `users` 
                JOIN staff ON staff.id = users.staff_id
                JOIN depart ON depart.id = staff.departId
                WHERE users.id =:uUId";

                //get staff details
                $user_stmt = $this->getDbConnection()
                    ->prepare($user_query);
                $user_stmt->execute(array(':uUId' => $userId));

                $user_details = $user_stmt->fetch(PDO::FETCH_ASSOC);

                if ($user_stmt->rowCount() > 0) {
                    extract($user_details);

                    $response['userId'] = $userId;
                    $response['firstnames'] = $firstnames;
                    $response['lastname'] = $lastname;
                    $response['email'] = $email;
                    $response['iSActive'] = $iSActive;
                    $response['department'] = $department;

                    return $response;
                }
                return $response;
            } else {
                //get student details
                $user_query = "SELECT users.id As userId, 
                students.firstnames, 
                students.lastname, 
                students.email, 
                users.is_active as iSActive, 
                programs.name as department, 
                student_levels.name as studentLevel
                FROM `users` 
                JOIN students ON students.id = users.student_id 
                JOIN programs On programs.id = students.program_id
                JOIN student_levels ON student_levels.id = students.level_id
                WHERE users.id =:uUId";

                //get student details
                $user_stmt = $this->getDbConnection()
                    ->prepare($user_query);
                $user_stmt->execute(array(':uUId' => $userId));

                $user_details = $user_stmt->fetch(PDO::FETCH_ASSOC);

                if ($user_stmt->rowCount() > 0) {
                    extract($user_details);

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
        }
        return $response;
    }

    public function GetLecturerModulesGroupsByLecturerId($lecturerId)
    {
        $result = array();
        $response=array();
        $query = "SELECT group_chats.id as groupChatId, group_chats.module_id as moduleId
        FROM lecturer_modules
        JOIN group_chats ON group_chats.module_id = lecturer_modules.module_id
        WHERE lecturer_modules.lecturer_id =:uLecturerId";

        $stmt = $this->getDbConnection()
            ->prepare($query);
        $stmt->execute(array(
            ':uLecturerId' => $lecturerId
        ));

        while ($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
            //extract role data
            extract($row);
            //get module details
            $result = $this->GetModuleDetailsByGroupChatId($groupChatId);
            $response[]=array(
                'moduleId' => $moduleId,
                'moduleName'=>$result['moduleName'],
                'level'=>$result['level'],
                'email'=>$result['email'],
                'program'=>$result['program'],
                'lecturer'=>$result['lecturer']
            );
        }

        //return result
        return $response;
    }
}
