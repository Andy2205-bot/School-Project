<?php
//this class contains properties which are shared by other classes, hence it will be inherited by as a base class by other classes
class Ebase
{
    // database connection 
    private $conn;
    // object properties
    public $id;
    public $name;
    public $created_on;

    //passing db connection as a constructer parameter
    public function __construct($db)
    {
        //initializing dbconnection
        $this->conn = $db;
    }

    //fuction to return database connection
    function getDbConnection()
    {
        return $this->conn;
    }
}