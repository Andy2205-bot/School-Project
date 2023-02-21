<?php
require_once('ebase.php');

class NotificationClass extends Ebase
{
    private $table_name = "notifications";

    //properties
    public $title;
    public $msg;
    public $due;
    public $created_by;
    public $userId;
    public $depart;

    public function GetAllUserNotifications($userId)
    {
        //creating sql query statement
        $query = "SELECT `id`, `title`, `msg`, `due`, `created_by`, `userId`, `depart`, `created_on` 
        FROM `notifications` 
        WHERE userId =:uUserId";

        //accessing getDbConnection() function from base class to get connection string
        $stmt = $this->getDbConnection()
            ->prepare($query);
        $stmt->execute(array(
            ':uUserId' => $userId 
        ));

        //returning result statement
        return $stmt;
    }
    public function GetAllNotifications()
    {
        //creating sql query statement
        $query = "SELECT `id`, `title`, `msg`, `due`, `created_by`, `userId`, `depart`, `created_on` 
        FROM notifications ORDER BY id DESC";

        //accessing getDbConnection() function from base class to get connection string
        $stmt = $this->getDbConnection()
            ->prepare($query);
        $stmt->execute();

        //returning result statement
        return $stmt;
    }
    public function DeleteNotification($id)
    {
        //creating sql query statement
        $query = "SELECT `id`, `title`, `msg`, `due`, `created_by`, `userId`, `depart`, `created_on` 
        FROM notifications ORDER BY id DESC";

        //accessing getDbConnection() function from base class to get connection string
        $stmt = $this->getDbConnection()
            ->prepare($query);
        $stmt->execute();

        //returning result statement
        return $stmt;
    }

    //check f school exist
    public function GetIsProgramExist($name)
    {
        $query = "SELECT id FROM programs WHERE name = :sName LIMIT 0,1";

        $stmt = $this->getDbConnection()
            ->prepare($query);
        $stmt->execute(array(
            ':sName' => $name
        ));

        if ($stmt->rowCount() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    //create program
    function addNotification($userId, $depart, $createdBy)
    {
        $response = array();
        //posted values
        // posted values
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->msg = htmlspecialchars(strip_tags($this->msg));
        $this->due = htmlspecialchars(strip_tags($this->due));

        $query = "INSERT INTO notifications SET title=:sTitle, msg=:sMsg, due=:sDue, created_by=:sBy,userId=:sUid,depart=:sDepart  ";

        //preparing query statement
        $stmt = $this->getDbConnection()->prepare($query);
        // bind values
        $stmt->bindParam(":sTitle",  $this->title);
        $stmt->bindParam(":sMsg",  $this->msg);
        $stmt->bindParam(":sDue",  $this->due);
        $stmt->bindParam(":sBy",  $createdBy);
        $stmt->bindParam(":sUid",  $userId);
        $stmt->bindParam(":sDepart", $depart);

        if ($stmt->execute()) {

            $response['error'] = false;
            $response['message'] = "Notice successfully added!";

            return  $response;
        } else {
            $response['error'] = true;
            $response['message'] = "Failed to add new notice!";

            return $response;
        }
    }

    //for drop down
    function read()
    {
        //select all data
        $query = "SELECT
                        id, name
                    FROM
                        " . $this->table_name . "
                    ORDER BY
                    name";

        $stmt = $this->getDbConnection()->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
