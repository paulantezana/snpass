<?php

class Database
{
    private $connection;
    public function __construct()
    {
        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_CASE => PDO::CASE_LOWER
        );

//         $this->connection = new PDO('mysql:host=localhost;dbname=paulpvad_snpass', 'paulpvad_snpass', 'CSs#$%1@.651s2', $options);
        $this->connection = new PDO('mysql:host=localhost;dbname=skynetpass', 'root', '', $options);
        $this->connection->exec("SET CHARACTER SET UTF8");
    }
    public function getConnection() : PDO
    {
        return $this->connection;
    }
    public function closeConnection()
    {
        $this->connection = null;
    }
}