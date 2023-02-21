
<?php
require_once('ebase.php');

class StaffClass extends Ebase
{
    private $table_name = "staff";
    //properties
    public $titleId;
    public $firstnames;
    public $lastname;
    public $contacts;
    public $email;
    public $roleId;
    public $departId;

    //function to get all titles
    public function GetAllStaffMembers()
    {
        //creating sql query statement
        $query = "SELECT staff.`id` as staffId,staff.`firstnames`,staff_roles.name as role, staff.`lastname`, staff.`email`, staff.`contacts`, titles.name as title, depart.name as depart, staff.`created_on`
        FROM `staff`
        JOIN titles ON titles.id = staff.title_id
        JOIN depart ON depart.id = staff.departId
        JOIN staff_roles ON staff_roles.id = staff.role_id ORDER BY depart.`name` ASC";

        //accessing getDbConnection() function from base class to get connection string
        $stmt = $this->getDbConnection()
            ->prepare($query);
        $stmt->execute();

        //returning result statement
        return $stmt;
    }

    //create student
    function addNewStaff()
    {
        $response = array();
        //posted values
        // posted values
        $this->titleId = htmlspecialchars(strip_tags($this->titleId));
        $this->firstnames = htmlspecialchars(strip_tags($this->firstnames));
        $this->lastname = htmlspecialchars(strip_tags($this->lastname));
        $this->contacts = htmlspecialchars(strip_tags($this->contacts));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->roleId = htmlspecialchars(strip_tags($this->roleId));
        $this->departId = htmlspecialchars(strip_tags($this->departId));

        //check if user already exist
        if ($this->GetIsStaffExist($this->email) > 0) {
            $response['error'] = true;
            $response['message'] = "Staff Already Exist!";

            return $response;
        } else {

            $query = "INSERT INTO staff SET title_id=:sTitleId,firstnames=:sFnames,lastname=:sLastnames,contacts=:sContacts,email=:sEmail, role_id=:sRoleId,departId=:sdepartId";
            //preparing query statement
            $stmt = $this->getDbConnection()->prepare($query);
            // bind values
            $stmt->bindParam(":sTitleId", $this->titleId);
            $stmt->bindParam(":sFnames", $this->firstnames);
            $stmt->bindParam(":sLastnames", $this->lastname);
            $stmt->bindParam(":sContacts", $this->contacts);
            $stmt->bindParam(":sEmail", $this->email);
            $stmt->bindParam(":sRoleId", $this->roleId);
            $stmt->bindParam(":sdepartId", $this->departId);
            if ($stmt->execute()) {

                $response['error'] = false;
                $response['message'] = "Staff successfully added!";

                return  $response;
            } else {
                //print_r("Depart Id: " . $this->departId . " - " . $stmt->errorInfo());
                $response['error'] = true;
                $response['message'] = "Failed to add new Staff!";

                return $response;
            }
        }
    }

    //check f school exist
    public function GetIsStaffExist($email)
    {
        $query = "SELECT id FROM staff WHERE email = :sEmail LIMIT 0,1";

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
