<?php

class Database
{

    private $host = "localhost";
    private $db = "pos_system";
    private $user = "root";
    private $pass = "";

    public function connect()
    {

        $conn = new mysqli(
            $this->host,
            $this->user,
            $this->pass,
            $this->db
        );

        if ($conn->connect_error) {
            die("Database Connection Failed");
        }

        return $conn;
    }
}
