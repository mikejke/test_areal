<?php
/**
 * Database преставляет собой базу данных
 */
class Database {
    // конфигурация базы данных
    private $host       = "localhost";
    private $db_name    = "testareal";
    private $username   = "root";
    private $password   = "";

    public $connection;

    /**
     * getConnection метод для подключения к базе данных
     */
    public function getConnection(){
        $this->conn = null;

        try {
            $this->connection 
                = new PDO("mysql:host=".$this->host.";dbname=".$this->db_name, $this->username, $this->password);
        } catch(PDOException $e) {
            echo "Connection error: " . $e->getMessage();
        }

        return $this->connection;
    }
}
?>