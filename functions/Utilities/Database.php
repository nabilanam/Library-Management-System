<?php

final class Database
{
    /* @var PDO */
    private $conn;
    private $host = 'localhost';
    private $db_name = 'lms';
    private $username = 'root';
    private $password = '';
    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    private function __construct()
    {
        try {
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name,
                $this->username, $this->password, array(PDO::ATTR_PERSISTENT => true));
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo 'Connection error: ' . $exception->getMessage();
        }

        return $this->conn;
    }

    /**
     * @param $query
     * @return bool|PDOStatement
     */
    public function query($query)
    {
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }


    public function bindQuery($query, $data)
    {
        $stmt = $this->conn->prepare($query);
        $stmt->execute($data);
        return $stmt;
    }

    /**
     * @param $table
     * @return bool|string
     */
    public function getNextAutoIncrement($table)
    {
        $query = "SELECT `AUTO_INCREMENT` FROM  INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA=$this->db_name AND TABLE_NAME=$table";
        $result = $this->query($query);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return $result->rowCount() == 1 ? $row['AUTO_INCREMENT'] : false;
    }

    /**
     * @return string
     */
    public function lastInsertId()
    {
        return $this->conn->lastInsertId();
    }

    public function beginTransaction()
    {
        return $this->conn->beginTransaction();
    }

    public function commit()
    {
        return $this->conn->commit();
    }

    public function rollback()
    {
        return $this->conn->rollBack();
    }
}