<?php
date_default_timezone_set('Africa/Accra');
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 'On');
ini_set('error_log', dirname(__FILE__) . '../../error.log');

class DBConnection
{
    private $host = 'localhost'; 
    private $database_name = 'invoice'; 
    private $username = 'irbba'; 
    private $password = 'incorrect';
    private $pdo;

    public function __construct()
    {
        // No need to pass the connection details in the constructor anymore
    }

    public function connect()
    {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->database_name};charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            $this->pdo = new PDO($dsn, $this->username, $this->password, $options);
            return $this->pdo;
        } catch (PDOException $e) {
            error_log("Connection failed: " . $e->getMessage());
            return null;
        }
    }
}
