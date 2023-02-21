
<?php
require_once('ebase.php');

class SchoolClass extends Ebase
{
    private $table_name = "schools";

    //properties
    public $name;
    public $id;
    //function to get all titles
    public function GetAllSchools()
    {
        //creating sql query statement
        $query = "SELECT * From " . $this->table_name . " ORDER BY sname ASC";

        //accessing getDbConnection() function from base class to get connection string
        $stmt = $this->getDbConnection()
            ->prepare($query);
        $stmt->execute();

        //returning result statement
        return $stmt;
    }

    //create school
    function addNewSchool()
    {
        $response = array();
        //posted values
        // posted values
        $this->name = htmlspecialchars(strip_tags($this->name));

        //check if user already exist
        if ($this->GetIsSchoolExist($this->name) > 0) {

            $response['error'] = true;
            $response['message'] = "School Already Exist!";

            return $response;
        } else {

            $query = "INSERT INTO schools SET sname=:sName";
            //preparing query statement
            $stmt = $this->getDbConnection()->prepare($query);
            // bind values
            $stmt->bindParam(":sName", $this->name);
            if ($stmt->execute()) {

                $response['error'] = false;
                $response['message'] = "School successfully added!";

                return  $response;
            } else {
                //print_r("Depart Id: " . $this->departId . " - " . $stmt->errorInfo());
                $response['error'] = true;
                $response['message'] = "Failed to add new school!";

                return $response;
            }
        }
    }

    //check f school exist
    public function GetIsSchoolExist($name)
    {
        $query = "SELECT id FROM schools WHERE sname = :sName LIMIT 0,1";

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
    //function to get user account if exist
    public function GetSchoolDetailsById($id)
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
