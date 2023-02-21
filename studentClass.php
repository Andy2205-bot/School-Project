
<?php
require_once('ebase.php');

class StudentClass extends Ebase
{
    private $table_name = "students";
    //properties
    public $regNum;
    public $firstnames;
    public $lastname;
    public $genderId;
    public $email;
    public $programId;
    public $levelId;
    public $academicSessionId;
    //function to get all students
    public function GetAllStudents()
    {
        //creating sql query statement
        $query = "SELECT students.`id` as studentId, students.`reg_no` as regNum, students.`firstnames`, students.`lastname`, gender.name as gender, students.`email`, programs.name as program,  student_levels.name as level 
        FROM `students` 
        JOIN programs ON  programs.id = students.program_id
        JOIN gender ON gender.id = students.gender_id
        JOIN student_levels ON student_levels.id = students.level_id ORDER BY students.`id` DESC";

        //accessing getDbConnection() function from base class to get connection string
        $stmt = $this->getDbConnection()
            ->prepare($query);
        $stmt->execute();

        //returning result statement
        return $stmt;
    }
    
    public function GetAllStudentsByProgramId($programId)
    {
        //creating sql query statement
        $query = "SELECT students.`id` as studentId, students.`reg_no` as regNum, students.`firstnames`, students.`lastname`, gender.name as gender, students.`email`, programs.name as program,  student_levels.name as level 
        FROM `students` 
        JOIN programs ON  programs.id = students.program_id
        JOIN gender ON gender.id = students.gender_id
        JOIN student_levels ON student_levels.id = students.level_id 
        WHERE students.program_id =:sProgramId ORDER BY students.`id` DESC";

        //accessing getDbConnection() function from base class to get connection string
        $stmt = $this->getDbConnection()
            ->prepare($query);
        $stmt->execute(array(
            'sProgramId'=>$programId
        ));

        //returning result statement
        return $stmt;
    }
    public function GetAllStudentsByProgramIdAndLevelId($programId,$levelId)
    {
        //creating sql query statement
        $query = "SELECT students.`id` as studentId, students.`reg_no` as regNum, students.`firstnames`, students.`lastname`, gender.name as gender, students.`email`, programs.name as program,  student_levels.name as level 
        FROM `students` 
        JOIN programs ON  programs.id = students.program_id
        JOIN gender ON gender.id = students.gender_id
        JOIN student_levels ON student_levels.id = students.level_id 
        WHERE students.program_id =:sProgramId AND students.level_id =:sLevelId ORDER BY students.`id` DESC";

        //accessing getDbConnection() function from base class to get connection string
        $stmt = $this->getDbConnection()
            ->prepare($query);
        $stmt->execute(array(
            'sProgramId'=>$programId,
            'sLevelId' => $levelId
        ));

        //returning result statement
        return $stmt;
    }

    //create student
    function addNewStudent()
    {
        $response = array();
        //posted values
        // posted values
        $this->regNum = htmlspecialchars(strip_tags($this->regNum));
        $this->firstnames = htmlspecialchars(strip_tags($this->firstnames));
        $this->lastname = htmlspecialchars(strip_tags($this->lastname));
        $this->genderId = htmlspecialchars(strip_tags($this->genderId));
        $this->email = htmlspecialchars(strip_tags($this->regNum))."@elearning.sis.ac.zw";
        $this->programId = htmlspecialchars(strip_tags($this->programId));
        $this->levelId = htmlspecialchars(strip_tags($this->levelId));
        $this->academicSessionId = 2;

        //check if user already exist
        if ($this->GetIsStudentExist($this->email) > 0) {

            $response['error'] = true;
            $response['message'] = "Student Already Exist!";

            return $response;
        } else {
            $query = "INSERT INTO students SET reg_no=:sRegnum,firstnames=:sFnames,lastname=:sLastnames,gender_id=:sGenderId,email=:sEmail, program_id=:sProgramId,level_id=:sLevelId,academic_session_id=:sSession";
            //preparing query statement
            $stmt = $this->getDbConnection()->prepare($query);
            // bind values
            $stmt->bindParam(":sRegnum", $this->regNum);
            $stmt->bindParam(":sFnames", $this->firstnames);
            $stmt->bindParam(":sLastnames", $this->lastname);
            $stmt->bindParam(":sGenderId", $this->genderId);
            $stmt->bindParam(":sEmail", $this->email);
            $stmt->bindParam(":sProgramId", $this->programId);
            $stmt->bindParam(":sLevelId", $this->levelId);
            $stmt->bindParam(":sSession", $this->academicSessionId);
            if ($stmt->execute()) {

                $response['error'] = false;
                $response['message'] = "Student successfully added!";

                return  $response;
            } else {
                //print_r("Depart Id: " . $this->departId . " - " . $stmt->errorInfo());
                $response['error'] = true;
                $response['message'] = "Failed to add new Student!";

                return $response;
            }
        }
    }

    //check f school exist
    public function GetIsStudentExist($email)
    {
        $query = "SELECT id FROM students WHERE email = :sEmail LIMIT 0,1";

        $stmt = $this->getDbConnection()
            ->prepare($query);
        $stmt->execute(array(
            ':sEmail' => $email
        ));

        if ($stmt->rowCount() > 0) {
            return 1;
        } else {
            return 0;
        }
    }
    //function to get user account if exist
    public function GetStudentDetailsById($id)
    {
        $query = "SELECT sname FROM schools WHERE id = :usid LIMIT 0,1";

        $stmt = $this->getDbConnection()
            ->prepare($query);
        $stmt->execute(array(
            ':usid' => $id
        ));
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($stmt->rowCount() > 0) {
            extract($data);
            return $sname;
        } else {
            return "";
        }
    }

    public function UpdateStudentDetails()
    {
        $query = "UPDATE `schools` SET sname= :sName WHERE id = :usid";

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->id = htmlspecialchars(strip_tags($this->id));

        //preparing query statement
        $stmt = $this->getDbConnection()->prepare($query);
        // bind values
        $stmt->bindParam(":sName", $this->name);
        $stmt->bindParam(":usid", $this->id);
        if ($stmt->execute()) {

            $response['error'] = false;
            $response['message'] = "School successfully updated!";

            return  $response;
        } else {
            //print_r("Depart Id: " . $this->departId . " - " . $stmt->errorInfo());
            $response['error'] = true;
            $response['message'] = "Failed to updated school!";

            return $response;
        }
    }

    // used by select drop-down list
    function read()
    {
        //select all data
        $query = "SELECT
                        id, sname
                    FROM
                        " . $this->table_name . "
                    ORDER BY
                    sname";

        $stmt = $this->getDbConnection()->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
