<?php
require_once __DIR__ . '/../init.php';
require_once __DIR__ . '/../Utilities/Database.php';

abstract class Repository
{
    protected $db;
    protected $table;

    public function __construct($table)
    {
        $this->db = Database::getInstance();
        $this->table = $table;
    }

    public abstract function add($object);

    public abstract function removeById($id);

    public abstract function findById($id);

    public abstract function update($object);

    public abstract function getAll();

    public function totalRecords(){
        $query = "SELECT COUNT(*) FROM $this->table";
        $result = $this->db->query($query);
        return $result->fetchColumn();
    }
}