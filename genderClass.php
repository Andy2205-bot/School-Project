<?php
require_once('ebase.php');

class GenderClass extends Ebase
{
    private $table_name = "gender";

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