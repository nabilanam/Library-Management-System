<?php
require_once __DIR__.'/../Utilities/Database.php';

abstract class JoinedRepository
{
    protected $db;
    protected $table;

    public function __construct($table)
    {
        $this->db = Database::getInstance();
        $this->table = $table;
    }

    public abstract function join($first_table_id, $second_table_id);

    public abstract function remove($first_table_id, $second_table_id);

    public abstract function isJoinExist($first_table_id, $second_table_id);

    public abstract function findFirst($second_table_id);

    public abstract function findSecond($first_table_id);
}