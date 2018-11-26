<?php
require_once 'Repository.php';

class UserTypesRepository implements Repository
{
    private $db;
    private $table;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->table = 'user_types';
    }

    /**
     * @param UserType $type
     * @return bool
     */
    public function add($type)
    {
        $data = [
            'name' => $type->getName(),
            'book_limit' => $type->getBookLimit(),
            'day_limit' => $type->getDayLimit(),
            'fine_per_day' => $type->getFinePerDay()
        ];
        $query = "INSERT INTO $this->table SET name=:name, book_limit=:book_limit, day_limit=:day_limit, fine_per_day=:fine_per_day";
        $result = $this->db->bindQuery($query,$data);
        return $result->rowCount() == 1;
    }

    public function remove($id)
    {
        $query = "DELETE FROM $this->table WHERE id=:id";
        $result = $this->db->bindQuery($query,['id'=>$id]);
        return $result->rowCount() == 1;
    }

    public function find($object)
    {
        // TODO: Implement find() method.
    }

    public function findById($id)
    {
        $query = "SELECT * FROM $this->table WHERE id=:id";
        $result = $this->db->bindQuery($query,['id'=>$id]);
        $arr = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)){
            $type = new UserType($row['id'],$row['name'],$row['book_limit'],$row['day_limit'],$row['fine_per_day']);
            $arr[] = $type;
        }
        return $arr;
    }

    public function findOrInsert($object)
    {
        // TODO: Implement findOrInsert() method.
    }

    /**
     * @param UserType $type
     * @return bool
     */
    public function update($type)
    {
        $data = [
            'id' => $type->getId(),
            'name' => $type->getName(),
            'book_limit' => $type->getBookLimit(),
            'day_limit' => $type->getDayLimit(),
            'fine_per_day' => $type->getFinePerDay()
        ];
        $query = "Update $this->table SET name=:name, book_limit=:book_limit, day_limit=:day_limit, fine_per_day=:fine_per_day WHERE id=:id";
        $result = $this->db->bindQuery($query,$data);
        return $result->rowCount() == 1;
    }

    public function getAll()
    {
        $query = "SELECT * FROM $this->table";
        $result = $this->db->query($query);
        $arr = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)){
            $type = new UserType($row['id'],$row['name'],$row['book_limit'],$row['day_limit'],$row['fine_per_day']);
            $arr[] = $type;
        }
        return $arr;
    }

    public function getNextAutoIncrement()
    {
        // TODO: Implement getNextAutoIncrement() method.
    }
}