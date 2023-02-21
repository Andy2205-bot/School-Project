
<?php
require_once('ebase.php');

class StudentLevelClass extends Ebase
{
    private $table_name = "student_levels";

    //properties

    //function to get all titles
    public function GetAllStudentLevels()
    {
        //creating sql query statement
        $query = "SELECT * From ".$this->table_name." ORDER BY name ASC";

        //accessing getDbConnection() function from base class to get connection string
        $stmt = $this->getDbConnection()
            ->prepare($query);
        $stmt->execute();

        //returning result statement
        return $stmt; 
    }

       // used by select drop-down list
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