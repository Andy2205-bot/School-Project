<?php
require_once('ebase.php');

class LecturerModulesClass extends Ebase
{
    private $table_name = "program_modules";

    //properties
    public $moduleId;
    public $lecturerId;

    //function to get all titles
    public function GetAllLecturerModulesByUserId($lectureId)
    {
        //creating sql query statement
        $query = "SELECT program_modules.id as moduleId, 
        programs.name as program,
         modules.name as moduleName,
         program_modules.program_id as programId,
          modules.code as moduleCode, 
          student_levels.name as level 
          FROM lecturer_modules 
          JOIN program_modules ON program_modules.id = lecturer_modules.module_id 
          JOIN programs ON programs.id = program_modules.program_id 
          JOIN modules ON modules.id = program_modules.module_id 
          JOIN student_levels ON student_levels.id = program_modules.level_id 
          WHERE lecturer_modules.lecturer_id =:uLecturerId";

        //accessing getDbConnection() function from base class to get connection string
        $stmt = $this->getDbConnection()
            ->prepare($query);
        $stmt->execute(array(
            ':uLecturerId' => $lectureId
        ));

        //returning result statement
        return $stmt;
    }

    //create lecture module
    function addNewLectureModule($sessionId)
    {
        $response = array();
        //posted values
        // posted values
        $this->moduleId = htmlspecialchars(strip_tags($this->moduleId));

        //check if user already exist
        if ($this->GetIsLecturerModuleExist($this->moduleId, $this->lecturerId) > 0) {

            $response['error'] = false;
            $response['message'] = "Module Successfully Updated!";

            return $response;
        } else {
            $msg = $this->lecturerId;
            echo "<script type='text/javascript'>alert('$msg');";
            echo "</script>";

            $query = "INSERT INTO lecturer_modules SET module_id=:sModId, lecturer_id=:sLecturerId";

            //preparing query statement
            $stmt = $this->getDbConnection()->prepare($query);
            // bind values
            $stmt->bindParam(":sModId", $this->moduleId);
            $stmt->bindParam(":sLecturerId",  $this->lecturerId);

            if ($stmt->execute()) {

                //create module chat
                $moduleQuery = "INSERT INTO group_chats SET module_id=:sModId, academic_session_id=:sSId";
                $sid = 2;
                //preparing query statement
                $moduleStmt = $this->getDbConnection()->prepare($moduleQuery);
                // bind values
                $moduleStmt->bindParam(":sModId", $this->moduleId);
                $moduleStmt->bindParam(":sSId",  $sid);

                if ($moduleStmt->execute()) {

                    $response['error'] = false;
                    $response['message'] = "Module successfully added!";

                    return  $response;
                }
                return  $response;
            } else {
                $response['error'] = true;
                $response['message'] = "Failed to add module!";

                return $response;
            }
        }
    }
    //check if module exist
    public function GetIsLecturerModuleExist($moduleId, $lectureId)
    {
        $query = "SELECT id FROM lecturer_modules WHERE module_id = :sModId LIMIT 0,1";

        $stmt = $this->getDbConnection()
            ->prepare($query);
        $stmt->execute(array(
            ':sModId' => $moduleId
        ));

        if ($stmt->rowCount() > 0) {
            //updating 
            $query = "UPDATE `lecturer_modules` SET lecturer_id= :sLecturerId WHERE module_id = :uModId";

            //preparing query statement
            $stmt = $this->getDbConnection()->prepare($query);
            // bind values
            $stmt->bindParam(":sLecturerId", $lectureId);
            $stmt->bindParam(":uModId", $moduleId);
            if ($stmt->execute()) {

                return 1;
            } else {

                return -1;
            }
        } else {
            return 0;
        }
    }
}
