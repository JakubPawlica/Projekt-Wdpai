<?php

require_once 'config.php';

class Database
{
    private $username;
    private $password;
    private $host;
    private $database;

    private static $instance = null;
    private $connection;

    public function __construct()
    {
        $this->username = USERNAME;
        $this->password = PASSWORD;
        $this->host = HOST;
        $this->database = DATABASE;

        $this->getConnection();
    }

    private function getConnection()
    {
        try {
            $this->connection = new PDO(
                "pgsql:host=$this->host;port=5432;dbname=$this->database",
                $this->username,
                $this->password,
                ["sslmode" => "prefer"]
            );

            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            $controller = new DefaultController();
            $controller->error404();
            exit;
        }
    }

    public function connect()
    {
        return $this->connection;
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }

        return self::$instance;
    }
}