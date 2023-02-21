
<?php
require_once('ebase.php');

class ProgramModulesClass extends Ebase
{
    private $table_name = "program_modules";
    //properties
    public $moduleName;
    public $moduleCode;
    public $moduleId;
    public $programId;
    public $levelId;
    //function to get all titles
    public function GetAllProgramsModules()
    {
        //creating sql query statement
        $query = "SELECT program_modules.`id` as programModuleId, modules.name as moduleName, modules.code as moduleCode,programs.name as program, student_levels.name as level 
        FROM `program_modules`
        JOIN modules ON modules.id = program_modules.module_id
        JOIN programs ON programs.id = program_modules.program_id
        JOIN student_levels ON student_levels.id = program_modules.level_id ORDER BY programs.name ASC";

        //accessing getDbConnection() function from base class to get connection string
        $stmt = $this->getDbConnection()
            ->prepare($query);
        $stmt->execute();

        //returning result statement
        return $stmt;
    }

    //create school
    function addNewProgramModule()
    {
        $response = array();
        //posted values
        // posted values
        $this->moduleName = htmlspecialchars(strip_tags($this->moduleName));
        $this->moduleCode = htmlspecialchars(strip_tags($this->moduleCode));
        $this->programId = htmlspecialchars(strip_tags($this->programId));
        $this->levelId = htmlspecialchars(strip_tags($this->levelId));


        $this->moduleId = $this->SaveModuleAndGetId($this->moduleName, $this->moduleCode);

        //check if user already exist
        if ($this->moduleId < 1) {

            $response['error'] = true;
            $response['message'] = "Module not found!";

            return $response;
        } else {

            $query = "INSERT INTO program_modules SET module_id=:sModuleId, program_id=:sProgramId,level_id=:sLevelId ";
            //preparing query statement
            $stmt = $this->getDbConnection()->prepare($query);
            // bind values
            $stmt->bindParam(":sModuleId", $this->moduleId);
            $stmt->bindParam(":sProgramId", $this->programId);
            $stmt->bindParam(":sLevelId", $this->levelId);
            if ($stmt->execute()) {

                $response['error'] = false;
                $response['message'] = "Program Module successfully added!";

                return  $response;
            } else {
                //print_r("Depart Id: " . $this->departId . " - " . $stmt->errorInfo());
                $response['error'] = true;
                $response['message'] = "Failed to add Program Module!";

                return $response;
            }
        }
    }

    //get module id
    public function GetModuleId($moduleCode)
    {
        $query = "SELECT id FROM modules WHERE code = :sCode LIMIT 0,1";

        $stmt = $this->getDbConnection()
            ->prepare($query);
        $stmt->execute(array(
            ':sCode' => $moduleCode
        ));
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($stmt->rowCount() > 0) {
            extract($data);
            return $id;
        } else {
            return 0;
        }
    }

    //save module and return Id
    public function SaveModuleAndGetId($moduleName, $moduleCode)
    {
        $id = $this->GetModuleId($moduleCode);

        if ($id > 0) {
            return $id;
        } else {
            //save module as new 

            $query = "INSERT INTO modules SET name=:sModuleName, code=:sCode";
            //preparing query statement
            $stmt = $this->getDbConnection()->prepare($query);
            // bind values
            $stmt->bindParam(":sModuleName",$moduleName);
            $stmt->bindParam(":sCode", $moduleCode);

            if ($stmt->execute()) {

                //get new module id
               $moduleId = $this->GetModuleId($moduleCode);

                return  $moduleId;
            }
            return $id;
        }
    }
    //function to GetAllProgramModulesForSelectList
    public function GetAllProgramModulesForSelectList($programId,$levelId)
    {
        $response = array();

        $query = "SELECT program_modules.`id` as programModuleId, 
        modules.name as moduleName, 
        modules.code as moduleCode
        FROM `program_modules`
        JOIN modules ON modules.id = program_modules.module_id 
        WHERE program_modules.program_id = :uPid and program_modules.level_id =:uLid ORDER BY modules.name";

        $stmt = $this->getDbConnection()
            ->prepare($query);
        $stmt->execute(array(
            ':uPid' => $programId,
            ':uLid' => $levelId,
        ));

         while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            //extract data
            extract($row);
            $response[]=array(
               'programModuleId'=>$programModuleId,
               'moduleName'=>$moduleName,
               'moduleCode'=>$moduleCode
            );
        }

        return $response;
    }
    //function to GetProgramAndLevelByProgramModuleId
    public function GetProgramAndLevelByProgramModuleId($Id)
    {
        $response = array();

        $query = "SELECT program_modules.`program_id` as programId, 
        program_modules.level_id as levelId
        FROM `program_modules`
        WHERE program_modules.id = :uPid";

        $stmt = $this->getDbConnection()
            ->prepare($query);
        $stmt->execute(array(
            ':uPid' => $Id
        ));

         while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            //extract data
            extract($row);
            $response['programId']=$programId;
            $response['levelId']=$levelId;
        }

        return $response;
    }

    public function UpdateSchoolDetails()
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
