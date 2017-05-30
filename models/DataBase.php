<?php

namespace app\models;

use mysqli;

class DataBase
{
    private $serverName = "localhost";
    private $dataBaseName = "frameworkDB";
    private $username = "root";
    private $password = "";
    public $conn;

    public function __construct()
    {
        $this->conn = $this->setConnection();
    }

    private function setConnection()
    {
        $conn = new mysqli($this->serverName, $this->username, $this->password, $this->dataBaseName);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        return $conn;
    }

    function insertNewUser($email, $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (email, password) VALUES ('$email', '$hashedPassword');";
        if ($this->conn->multi_query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    function verifyEmail($email)
    {
        $sql = "SELECT email FROM users WHERE email = '$email'";
        if ($this->conn === false) {
            echo "No connection";
            return false;
        } else {
            $result = $this->conn->query($sql);
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if ($email == $row["email"]) {
                    return false;
                } else {
                    return true;
                }
            }
            return true;
        }
    }

    function loginUser($email, $password, $remember)
    {
        $sql = "SELECT email, password FROM users WHERE email = '$email'";
        if ($this->conn === false) {
            echo "No connection";
            return false;
        } else {
            $result = $this->conn->query($sql);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    if (password_verify($password, $row["password"])) {
                        return true;
                    } else {
                        return false;
                    }
                }
            }
            return false;
        }
    }
}