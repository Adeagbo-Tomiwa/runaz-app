<?php
// config/database.php

class Database {
    private $host = "localhost";
    private $db_name = "runaz_app";
    private $username = "root";
    private $password = "";  // Empty for XAMPP default
    private $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->conn->exec("SET NAMES utf8mb4");
        } catch(PDOException $e) {
            error_log("Connection error: " . $e->getMessage());
            return null;
        }

        return $this->conn;
    }
}
?>