<?php
require_once('ebase.php');

class AccountClass extends Ebase
{
    private $table_name = "students";

    //properties
    public $password;
    public $newPassword;
    public $email;
    public $isStaff;
    public $staffId;
    public $studentId;
    public $isActive;
    public $userId;

    public function getUserDetails($email, $isStaff)
    {
        $query = "";

        if ($isStaff == 0) {
            $query = "SELECT id  FROM
                         " . $this->table_name . " WHERE 
                     email = :uemail
                     LIMIT
                        0,1";
        } else {
            $query = "SELECT id FROM staff WHERE email = :uemail LIMIT 0,1";
        }

        $stmt = $this->getDbConnection()
            ->prepare($query);
        $stmt->execute(array(
            ':uemail' => $email
        ));

        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($stmt->rowCount() > 0) {
            extract($user_data);
            return $id;
        } else {
            return 0;
        }
    }

    public function getUserProfileDetails($id)
    {
        $query = "";
        $acc = array();
        $response= array();

        $isStaff = $this->GetUserIsStaff($id);

        if ($isStaff == 0) {
            $query = "SELECT users.id as userId, 
            users.email, 
            users.pass,
            users.is_staff as isUserStaff, 
            users.is_active as isActive,
            users.profile_pic,
            students.academic_session_id as academicSessionId, 
            students.firstnames,
            students.level_id as studentLevelId, 
            students.lastname, 
            students.reg_no, 
            programs.name as program,
            programs.id as programId,
            student_levels.name as studentsLevel, 
            academic_sessions.name as academicSession
            FROM `users` 
            JOIN students ON students.id = users.student_id
            JOIN student_levels ON student_levels.id = students.level_id
            JOIN programs On programs.id = students.program_id
            JOIN academic_sessions ON academic_sessions.id = students.academic_session_id
            WHERE users.id = :uUserId
              LIMIT
                 0,1";
        } else {
            $query = "SELECT users.email as email,
            users.id as userId, 
            staff.firstnames, 
            users.is_staff as isUserStaff, 
            users.is_active as isActive,
            staff.lastname, 
            staff.contacts,
            titles.name as title, staff_roles.name as role, depart.name as depart
            FROM `users` 
            JOIN staff ON staff.id = users.staff_id
            JOIN titles ON titles.id = staff.title_id
            JOIN staff_roles ON staff_roles.id = staff.role_id
            JOIN depart ON depart.id = staff.departId
            WHERE users.id =:uUserId LIMIT 0,1";
        }

        $stmt = $this->getDbConnection()
            ->prepare($query);
        $stmt->execute(array(
            ':uUserId' => $id
        ));

        $userData = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($stmt->rowCount() > 0) {
            extract($userData);
            
            if($isUserStaff==1){
                $response['fullname'] = $title." ".$firstnames." ".$lastname;
                $response['depart'] = $depart;
                $response['email'] = $email;
                $response['role'] = $role;
                $response['isActive'] = $isActive;
                $response['isStaff'] = $isUserStaff;
                $response['userId'] = $userId;
            }else{
                $response['fullname'] = $firstnames." ".$lastname;
                $response['depart'] = $program." - ".$studentsLevel;
                $response['email'] = $email;
                $response['role'] = "Student";
                $response['isActive'] = $isActive;
                $response['isStaff'] = $isUserStaff;
                $response['userId'] = $userId;
            }
            return $response;
        } else {
            return  $response;
        }

        return $response;
    }

    //function to get user accounts
    public function getUserAccounts($id)
    {
        $response = array();
        $query = "SELECT id,is_staff FROM users WHERE id != :userid";

        $stmt = $this->getDbConnection()
            ->prepare($query);
        $stmt->execute(array(
            ':userid' => $id
        ));

        //$user_data = $stmt->fetch(PDO::FETCH_ASSOC);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $response[] = array(
                'id' => $id,
                'isStaff' => $is_staff
            );
        }

        return  $response;
    }
    //checkIf user is staff
    public function GetUserIsStaff($id)
    {
        $response = array();
        $query = "SELECT is_staff as isStaff FROM users WHERE id = :userid LIMIT 0,1";

        $stmt = $this->getDbConnection()
            ->prepare($query);
        $stmt->execute(array(
            ':userid' => $id
        ));

        $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($stmt->rowCount() > 0) {
            extract($user_data);
            return $isStaff;
        } else {
            return 0;
        }
    }

    //function to get user account if exist
    public function GetIsUserAccountExist($email)
    {
        $query = "SELECT id FROM users WHERE email = :uEmail LIMIT 0,1";

        $stmt = $this->getDbConnection()
            ->prepare($query);
        $stmt->execute(array(
            ':uEmail' => $email
        ));

        if ($stmt->rowCount() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    //User registration
    function registerAccount()
    {
        $isStaff = 0;
        $query = "";
        $stmt = "";
        $response = array();
        //posted values
        // posted values
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = password_hash(htmlspecialchars(strip_tags($this->password)), PASSWORD_DEFAULT);

        // make sure we've got a valid email
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            // split on @ and return last value of array (the domain)
            $var = explode('@', $this->email);
            $domain = array_pop($var);
            // output domain

            //check if user already exist
            if ($this->GetIsUserAccountExist($this->email) > 0) {

                $response['error'] = true;
                $response['message'] = "User Account Already Exist!";

                return $response;
            } else {

                if ($domain == "elearning.sis.ac.zw") {
                } else {
                    $isStaff = 1;
                }
                //check if record exist and return record Id
                if ($this->getUserDetails($this->email, $isStaff) > 0) {
                    if ($isStaff == 1) {
                        # code...
                        $this->staffId = $this->getUserDetails($this->email, $isStaff);
                        $query = "INSERT INTO users  SET is_staff=:uIsStaff, email=:uemail, staff_id=:uStaffId, is_active=:uIsActive,pass =:upass";
                        //preparing query statement
                        $stmt = $this->getDbConnection()->prepare($query);
                        // bind values
                        $stmt->bindParam(":uStaffId", $this->staffId);
                    } else {
                        # code...
                        $this->studentId = $this->getUserDetails($this->email, $isStaff);
                        $query = "INSERT INTO users  SET is_staff=:uIsStaff, email=:uemail, student_id=:uStudentId, is_active=:uIsActive,pass =:upass";
                        //preparing query statement
                        $stmt = $this->getDbConnection()->prepare($query);
                        // bind values
                        $stmt->bindParam(":uStudentId", $this->studentId);
                    }
                    //preparing query statement
                    $this->isStaff = $isStaff;
                    $this->isActive = 1;

                    // bind values
                    $stmt->bindParam(":uemail", $this->email);
                    $stmt->bindParam(":uIsStaff", $this->isStaff);
                    $stmt->bindParam(":uIsActive", $this->isActive);
                    $stmt->bindParam(":upass", $this->password);


                    if ($stmt->execute()) {

                        $response['error'] = false;
                        $response['message'] = "Account Successfully created";

                        return  $response;
                    } else {
                        //print_r("Depart Id: " . $this->departId . " - " . $stmt->errorInfo());
                        $response['error'] = true;
                        $response['message'] = "Failed to Create User Account!";

                        return $response;
                    }
                }
                $response['error'] = true;
                $response['message'] = "Email account does not exist. Please contact your division if there is a query!";

                return $response;
            }
        }
    }
    //function to get 
    public function GetAllUserAccounts($id)
    {
        $user_data=array();
        $response = array();
        //creating sql query statement
        $query = "SELECT id as userId, is_staff,staff_id, student_id
        FROM users
        WHERE id != :userid";
        //accessing getDbConnection() function from base class to get connection string
        $stmt = $this->getDbConnection()
            ->prepare($query);
        $stmt->execute(array(
            ':userid' => $id
        ));

        //loop through user accounts
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            //check if user if staff
            $user_data = $this->getUserProfileDetails($userId);

            $response[] = array(
                'fullname' => $user_data['fullname'],
                'depart' => $user_data['depart'] ,
                'email' => $user_data['email'],
                'role' => $user_data['role'],
                'isActive' => $user_data['isActive'],
                'isStaff' => $user_data['isStaff'],
                'userId' => $user_data['userId']
            );
        }

        //returning result statement
        return $response;
    }

    //function login user
    function UserLogin($email)
    {
        $query = "";
        // make sure we've got a valid email
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // split on @ and return last value of array (the domain)
            $var = explode('@', $email);
            $domain = array_pop($var);
            // output domain
            // $msg = $domain;
            // echo "<script type='text/javascript'>alert('$msg');";
            // echo "</script>";

            if ($domain == "elearning.sis.ac.zw") {
                //get student details
                $query = "SELECT users.id, 
                users.email, 
                users.pass,
                users.is_staff as isUserStaff, 
                users.is_active,
                users.profile_pic,
                students.academic_session_id as academicSessionId, 
                students.firstnames,
                students.level_id as studentLevelId, 
                students.lastname, 
                students.reg_no, 
                programs.name as program,
                programs.id as programId,
                student_levels.name as studentsLevel, 
                academic_sessions.name as academicSession
                FROM `users` 
                JOIN students ON students.id = users.student_id
                JOIN student_levels ON student_levels.id = students.level_id
                JOIN programs On programs.id = students.program_id
                JOIN academic_sessions ON academic_sessions.id = students.academic_session_id
                WHERE users.email = :uemail
                  LIMIT
                     0,1";
            } else {

                $query = "SELECT users.id, 
                users.is_staff as isUserStaff,
            users.email, 
            users.pass,
            users.is_active,
            users.profile_pic, 
            staff.firstnames, 
            titles.name as title,
            depart.name as depart,
            staff_roles.name as staffRole,
            staff.lastname
            FROM `users` 
            JOIN staff ON staff.id = users.staff_id
            JOIN depart ON depart.id = staff.departId
            JOIN titles ON titles.id = staff.title_id
            JOIN staff_roles ON staff_roles.id = staff.role_id
            WHERE users.email = :uemail
                  LIMIT
                     0,1";
            }

            $stmt = $this->getDbConnection()
                ->prepare($query);
            $stmt->execute(array(
                ':uemail' => $email
            ));
            return $stmt;
        }
        return 0;
    }

    //update user status
    function UpdateUserStatus($id,$status)
    {
            //Updating user active status
            $query = "UPDATE users SET is_active = :uIsActive WHERE id = :uUserId";

            $stmt = $this->getDbConnection()
                ->prepare($query);

            // bind parameters
            $stmt->bindParam(':uIsActive', $status);
            $stmt->bindParam(':uUserId', $id);

            // execute the query
            if ($stmt->execute()) {
                return true;
            }

            return false;
    }

       //func to change password
       function ChangePassword($userId,$pass)
       {
           // posted values  
           //Updating user active status
           $query = "UPDATE users SET pass = :upass WHERE id = :uid";
   
           $stmt = $this->getDbConnection()
               ->prepare($query);
   
            $password = password_hash($pass, PASSWORD_DEFAULT);
           // bind parameters
           $stmt->bindParam(':upass', $password);
           $stmt->bindParam(':uid', $userId);
   
           // execute the query
           if ($stmt->execute()) {
               return true;
           }
   
           return false;
       }
}
