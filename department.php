<?php
require_once('ebase.php');

class StaffDepartmentClass extends Ebase
{
    private $table_name = "depart";

    //properties

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