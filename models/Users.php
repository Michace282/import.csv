<?php


namespace Models;


use mysqli;

class Users
{
    public $dbConnection;
    public $error = '';

    function __construct($db_host, $db_username, $db_password, $db_name) {
        $this->dbConnection = new mysqli($db_host, $db_username, $db_password, $db_name);
        $this->initialize();
    }

    public function initialize() {
        // If there are errors (if the no# of errors is > 1), print out the error and cancel loading the page via exit();
        if (mysqli_connect_errno()) {
            $this->error = "Could not connect to MySQL database: %s\n".  mysqli_connect_error();
        }
        //проверка на существование таблицы, если не существует создаем ее
        $query = "SELECT id FROM users";
        $result = mysqli_query($this->dbConnection, $query);

        if(!$result) {
            $query = "CREATE TABLE USERS (
                          id int(11) AUTO_INCREMENT,
                          level enum('manager', 'delegate'),
                          manager_id int(11) NULL,
                          first_name  varchar(50) NULL,
                          last_name varchar(50) NULL,
                          email  varchar(100) UNIQUE,
                          PRIMARY KEY (id))";
            mysqli_query($this->dbConnection, $query);
        }
    }
    public function findByEmail($email) {
        $query = "SELECT * FROM users WHERE email = '".$email. "' LiMIT 1";
        $result = mysqli_query($this->dbConnection, $query);
        return ($result ? $result->fetch_all()[0] : false);
    }

    public function findAll() {
        $query = "SELECT * FROM users";
        $result = mysqli_query($this->dbConnection, $query);
        return (!empty($result) ? $result->fetch_all() : false);
    }

    public function insert($level, $first_name, $last_name, $email) {
        $query = "INSERT INTO `users`(`id`, `level`, `manager_id`, `first_name`, `last_name`, `email`) VALUES (null,'".$level."',null,'".$first_name."','".$last_name."','".$email."')";
        $result = mysqli_query($this->dbConnection, $query);
        return $result;
    }

    public function updateManagerId($user_id, $manager_id) {
        $query = "Update users set manager_id = ".$manager_id." WHERE id = ".$user_id;
        $result = mysqli_query($this->dbConnection, $query);
        return $result;
    }

    public function gerError() {
        return $this->error;
    }
}