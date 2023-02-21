
<?php
require_once('ebase.php');

class ChatClass extends Ebase
{
    private $table_name = "chats";

    //properties
    public $peerOne;
    public $peerTwo;
    public $isActive;

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
    function createNewChat($peerOne, $peerTwo, $academicSessionId)
    {
        //posted values
        $this->peerOne = $peerOne;
        $this->peerTwo = $peerTwo;
        $this->isActive = 0;

        $query = "INSERT INTO chats SET peer_one = :uPeerOne, peer_two = :uPeerTwo, is_active=:uIsActive,academic_session_id=:uAcademicSession";

        // bind values
        $stmt = $this->getDbConnection()->prepare($query);
        $stmt->bindParam(":uPeerOne", $this->peerOne);
        $stmt->bindParam(":uPeerTwo", $this->peerTwo);
        $stmt->bindParam(":uIsActive", $this->isActive);
        $stmt->bindParam(":uAcademicSession", $academicSessionId);

        if ($stmt->execute()) {
            return $this->GetChatId($this->peerOne, $this->peerTwo);
        }
        return 0;
    }

    //fuhction to get AllUserChat for dashboard
    public function GetAllChatsByUser($userId, $lastChatId)
    {
        $lastChatIndex = 0;

        $limit = 5;
        $response = array();
        $peerDetails = array();
        //creating sql query statement
        $query = "SELECT * FROM `chats` WHERE (peer_one = :uUserId or peer_two =:uUserId) and id>:uLastChatId ORDER BY created_on DESC";


        $stmt = $this->getDbConnection()->prepare($query);

        $stmt->bindParam(':uLastChatId', $lastChatId, PDO::PARAM_INT);
        $stmt->bindParam(":uUserId", $userId);

        $stmt->execute();

        //accessing getDbConnection() function from base class to get connection string

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            //extract data
            extract($row);

            $lastChatIndex = $id;
            $chatId = $id;

            if ($peer_one == $userId) {
                # get another peer details
                $peerDetails = $this->GetUserDetails($peer_two, $chatId);
            } else {
                # get another peer details
                $peerDetails = $this->GetUserDetails($peer_one, $chatId);
            }
            //add to return array

            if ($peerDetails['isStaff'] == 1) {
                //assign staff details
                $response[] = array(
                    'chatId' => $peerDetails['chatId'],
                    'userId' => $peerDetails['userId'],
                    'fullname' => $peerDetails['fullname'],
                    'email' => $peerDetails['email'],
                    'iSActive' => $peerDetails['iSActive'],
                    'isStaff' => $peerDetails['isStaff'],
                    'role' => $peerDetails['role'],
                    'department' => $peerDetails['department'],
                    'lastIndex' => $lastChatIndex
                );
            }else{
                 //assign student details
                 $response[] = array(
                    'chatId' => $peerDetails['chatId'],
                    'userId' => $peerDetails['userId'],
                    'fullname' => $peerDetails['fullname'],
                    'email' => $peerDetails['email'],
                    'isStaff' => $peerDetails['isStaff'],
                    'iSActive' => $peerDetails['iSActive'],
                    'role' => $peerDetails['role'],
                    'department' => $peerDetails['department'],
                    'studentLevel' => $peerDetails['studentLevel'],
                    'lastIndex' => $lastChatIndex
                );
            }
           
        }

        //returning result statement
        return $response;
    }

    //function to get peer details
    public function GetUserDetails($userId, $chatId)
    {
        $response = array();

        //user data query
        $user_query = "";
        //query statement
        $query = "SELECT id, is_staff FROM users WHERE id = :uUserId LIMIT 0,1";

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
                staff_roles.name as role,
                titles.name as title,
                depart.name as department, 
                users.is_active as iSActive,
                users.is_staff as isStaff
                FROM `users` 
                JOIN staff ON staff.id = users.staff_id
                JOIN depart ON depart.id = staff.departId  
                JOIN staff_roles ON staff_roles.id = staff.role_id       
                JOIN titles ON titles.id = staff.title_id
                WHERE users.id =:uUId";
            } else {
                //get student details
                $user_query = "SELECT users.id As userId, 
                students.firstnames, 
                students.lastname, 
                students.email, 
                users.is_active as iSActive,
                users.is_staff as isStaff,
                programs.name as department, 
                student_levels.name as studentLevel
                FROM `users` 
                JOIN students ON students.id = users.student_id 
                JOIN programs ON programs.id = students.program_id
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

                if ($isStaff == 1) {
                    //get staff details
                    $response['chatId'] = $chatId;
                    $response['userId'] = $userId;
                    $response['fullname'] = $title . " " . $lastname;
                    $response['email'] = $email;
                    $response['iSActive'] = $iSActive;
                    $response['role'] = $role;
                    $response['isStaff'] = $isStaff;
                    $response['department'] = $department;
                } else {
                    //get student data
                    $response['chatId'] = $chatId;
                    $response['userId'] = $userId;
                    $response['fullname'] = $firstnames." ".$lastname;
                    $response['email'] = $email;
                    $response['iSActive'] = $iSActive;
                    $response['isStaff'] = $isStaff;
                    $response['role'] = "Student";
                    $response['department'] = $department;
                    $response['studentLevel'] = $studentLevel;
                }

                return $response;
            }
            return $response;
        }
        return $response;
    }
}
