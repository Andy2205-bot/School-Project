<?php
class Lbase
{
    // database connection and table name
    private $conn;
    // object properties
    public $id;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    function getConn()
    {
        return $this->conn;
    }
}

class Leave extends Lbase
{
    private $table_name = "leave_sheet";

    //properties
    public $type_id;
    public $applicant_id;
    public $status_id;
    public $start_date;
    public $end_date;
    public $comment;

    //post application
    function create()
    {

        //write query
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    applicant_id=:applicant, type_id=:type, status_id=:status, start_date=:sdate, end_date=:edate, comment=:acomment";

        $stmt = $this->getConn()
            ->prepare($query);

        // posted values
        $this->type_id = htmlspecialchars(strip_tags($this->type_id));
        $this->applicant_id = htmlspecialchars(strip_tags($this->applicant_id));
        $this->status_id = 1;
        $this->start_date = htmlspecialchars(strip_tags($this->start_date));
        $this->end_date = htmlspecialchars(strip_tags($this->end_date));
        $this->comment = htmlspecialchars(strip_tags($this->comment));

        // bind values
        $stmt->bindParam(":applicant", $this->applicant_id);
        $stmt->bindParam(":type", $this->type_id);
        $stmt->bindParam(":status", $this->status_id);
        $stmt->bindParam(":sdate", $this->start_date);
        $stmt->bindParam(":edate", $this->end_date);
        $stmt->bindParam(":acomment", $this->comment);

        // $msg=$this->type_id;
        //   echo "<script type='text/javascript'>alert('$msg');";
        // echo "</script>";
        if ($stmt->execute()) {
            return true;
        } else {
            print_r($stmt->errorInfo());
            return false;
        }
    }

    //function get applications by user_id
    function GetApplicationsByUser($id)
    {
        $query = "SELECT
                        leave_sheet.id, leave_sheet.start_date, leave_sheet.end_date, 
                        leave_sheet.comment, leave_sheet.created_on, leave_sheet.status_id, leave_sheet.type_id,employees.fullname, 
                        employees.postId,employees.contacts,leave_types.type,status.status
                     FROM
                         " . $this->table_name . "
                         JOIN employees ON employees.id = leave_sheet.applicant_id
                         JOIN leave_types ON leave_types.id = leave_sheet.type_id
                         JOIN status ON status.id = leave_sheet.status_id
                     WHERE 
                     leave_sheet.applicant_id = :aid
                     ";

        $stmt = $this->getConn()
            ->prepare($query);
        $stmt->execute(array(
            ':aid' => $id
        ));
        return $stmt;
    }

     //function get applications by user_id
     function GetAllApplications()
     {
         $query = "SELECT
                         leave_sheet.id, leave_sheet.start_date, leave_sheet.end_date, 
                         leave_sheet.comment, leave_sheet.created_on, leave_sheet.status_id, leave_sheet.type_id,employees.fullname, 
                         employees.postId,employees.contacts,leave_types.type,status.status
                      FROM
                          " . $this->table_name . "
                          JOIN employees ON employees.id = leave_sheet.applicant_id
                          JOIN leave_types ON leave_types.id = leave_sheet.type_id
                          JOIN status ON status.id = leave_sheet.status_id
                      ";
 
         $stmt = $this->getConn()
             ->prepare($query);
         $stmt->execute();
         return $stmt;
     }
     function GetAllNewApplications()
     {
         $query = "SELECT
                         leave_sheet.id, leave_sheet.start_date, leave_sheet.end_date, 
                         leave_sheet.comment, leave_sheet.created_on, leave_sheet.status_id, leave_sheet.type_id,employees.fullname, 
                         employees.postId,employees.contacts,leave_types.type,status.status
                      FROM
                          " . $this->table_name . "
                          JOIN employees ON employees.id = leave_sheet.applicant_id
                          JOIN leave_types ON leave_types.id = leave_sheet.type_id
                          JOIN status ON status.id = leave_sheet.status_id
                      WHERE 
                      leave_sheet.status_id = :sid
                      ";
 
         $stmt = $this->getConn()
             ->prepare($query);
         $stmt->execute(array(
             ':sid' => 1
         ));
         return $stmt;
     }

     function GetLeaveSheet()
     {
         $query = "SELECT
                         leave_sheet.id, leave_sheet.start_date, leave_sheet.end_date, 
                         leave_sheet.comment, leave_sheet.created_on, leave_sheet.status_id, leave_sheet.type_id,employees.fullname, 
                         employees.postId,employees.contacts,leave_types.type,status.status
                      FROM
                          " . $this->table_name . "
                          JOIN employees ON employees.id = leave_sheet.applicant_id
                          JOIN leave_types ON leave_types.id = leave_sheet.type_id
                          JOIN status ON status.id = leave_sheet.status_id
                      WHERE 
                      leave_sheet.status_id != :sid AND leave_sheet.status_id != :did
                      ";
 
         $stmt = $this->getConn()
             ->prepare($query);
         $stmt->execute(array(
             ':sid' => 1,
             ':did' => 5,
         ));
         return $stmt;
     }

         //func to approve a leave request
    function ApproveLeave($id)
    {
        // posted values
        // $this->id = htmlspecialchars(strip_tags($this->id));
        $this->id = $id;
        $status = 2;

        //Updating user active status
            $query = "UPDATE
        " . $this->table_name . "
        SET
        status_id = :uStatus  
        WHERE
        id = :uid";

            $stmt = $this->getConn()
                ->prepare($query);

            // bind parameters
            $stmt->bindParam(':uStatus',  $status);
            $stmt->bindParam(':uid', $this->id);

            // execute the query
            if ($stmt->execute()) {
                return true;
            }

            return false;
        
    }

    function DeclineLeaveApplication($id)
    {
        // posted values
        // $this->id = htmlspecialchars(strip_tags($this->id));
        $this->id = $id;
        $status = 5;

        //Updating user active status
            $query = "UPDATE
        " . $this->table_name . "
        SET
        status_id = :uStatus  
        WHERE
        id = :uid";

            $stmt = $this->getConn()
                ->prepare($query);

            // bind parameters
            $stmt->bindParam(':uStatus',  $status);
            $stmt->bindParam(':uid', $this->id);

            // execute the query
            if ($stmt->execute()) {
                return true;
            }

            return false;
        
    }

    function DeleteMyLeaveApplication($id)
    {
        // posted values
        // $this->id = htmlspecialchars(strip_tags($this->id));
        $this->id = $id;

        //Updating user active status
            $query = "Delete From 
        " . $this->table_name . "
        WHERE
        id = :uid";

            $stmt = $this->getConn()
                ->prepare($query);

            // bind parameters
            $stmt->bindParam(':uid', $this->id);

            // execute the query
            if ($stmt->execute()) {
                return true;
            }

            return false;
        
    }
}
