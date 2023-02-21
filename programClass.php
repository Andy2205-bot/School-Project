<?php
require_once('ebase.php');

class ProgramClass extends Ebase
{
    private $table_name = "programs";

    //properties
    public $name;
    public $id;
    public $schoolId;

    //function to get all titles
    public function GetAllPrograms()
    {
        //creating sql query statement
        $query = "SELECT programs.`id` as id, programs.`name` as name, schools.sname as school, programs.`created_on` 
        FROM `programs`
        JOIN schools ON schools.id = programs.school_id ";

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
    function addNewProgram($name)
    {
        $response = array();
        //posted values
        // posted values
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->schoolId = htmlspecialchars(strip_tags($this->schoolId));
        $pname = htmlspecialchars(strip_tags($this->name));

        //check if user already exist
        if ($this->GetIsProgramExist($name) > 0) {

            $response['error'] = true;
            $response['message'] = "Program Already Exist!";

            return $response;
        } else {

            $query = "INSERT INTO programs SET name=:sName, school_id=:sSchoolId";

            //preparing query statement
            $stmt = $this->getDbConnection()->prepare($query);
            // bind values
            $stmt->bindParam(":sName", $name);
            $stmt->bindParam(":sSchoolId", $this->schoolId);

            if ($stmt->execute()) {

                $response['error'] = false;
                $response['message'] = "Program successfully added!";

                return  $response;
            } else {
                $response['error'] = true;
                $response['message'] = "Failed to add new program!";

                return $response;
            }
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
