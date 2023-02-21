<?php
require_once('ebase.php');

class ModuleClass extends Ebase
{
    private $table_name = "program_modules";

    //properties

    //function to get all titles
    public function GetAllModulesByUserId($programId,$levelId)
    {
        //creating sql query statement
        $query = "SELECT program_modules.id as programModuleId, program_modules.program_id as programId, program_modules.module_id as moduleId, modules.name as moduleName, program_modules.level_id as levelId
        FROM `program_modules` 
        JOIN modules ON modules.id = program_modules.module_id
        WHERE program_modules.program_id = :uProgramId and program_modules.level_id =:uLevelId";

        //accessing getDbConnection() function from base class to get connection string
        $stmt = $this->getDbConnection()
            ->prepare($query);
        $stmt->execute(array(
            ':uProgramId' => $programId,
            ':uLevelId' => $levelId
        ));

        //returning result statement
        return $stmt;
    }
}
