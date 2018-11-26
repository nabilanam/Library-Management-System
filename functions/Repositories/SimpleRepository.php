<?php
require_once 'Repository.php';

class SimpleRepository implements Repository
{
    protected $db;
    protected $table;

    public function __construct($table)
    {
        $this->db = Database::getInstance();
        $this->table = $table;
    }

    /* @var DTO $dto
     * @return DTO|bool
     */
    public function add($dto)
    {
        $data = [
            'name' => $dto->getName()
        ];
        $query = "INSERT INTO $this->table SET name=:name";
        $result = $this->db->bindQuery($query, $data);
        if ($result->rowCount() == 1) {
            $dto->setId($this->db->lastInsertId());
            return $dto;
        }
        return false;
    }

    /* @var DTO $dto
     * @return bool
     */
    public function remove($id)
    {
        $data = [
            'id' => $id
        ];
        $query = "DELETE FROM $this->table WHERE id=:id";
        $result = $this->db->bindQuery($query, $data);
        return $result->rowCount() == 1;
    }

    /* @var DTO $dto
     * @return array
     */
    public function find($dto)
    {
        $data = [
            'name' => $dto->getName()
        ];
        $query = "SELECT id,name FROM $this->table WHERE name=:name";
        $result = $this->db->bindQuery($query, $data);
        $arr = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $arr[] = new DTO($row['id'], $row['name']);
        }
        return $arr;
    }

    /* @var DTO $dto
     * @return DTO|bool
     */
    public function findOrInsert($dto)
    {
        if ($arr = $this->find($dto)) {
            return $arr[0];
        }
        return $this->add($dto);
    }

    /**
     * @param $id
     * @return array DTO
     */
    public function findById($id)
    {
        $query = "SELECT * FROM $this->table WHERE id=:id";
        $result = $this->db->bindQuery($query, ['id' => $id]);
        $arr = [];
        while ($row = $result->fetch(PDO::FETCH_NUM)) {
            $arr[] = new DTO($row[0], $row[1]);
        }
        return $arr;
    }

    /* @var DTO $dto
     * @return bool
     */
    public function update($dto)
    {
        $data = [
            'id' => $dto->getId(),
            'name' => $dto->getName()
        ];
        $query = "UPDATE $this->table SET name=:name WHERE id=:id";
        $result = $this->db->bindQuery($query, $data);
        return $result->rowCount() == 1;
    }

    /**
     * @return array DTO
     */
    public function getAll()
    {
        $query = "SELECT * FROM $this->table";
        $result = $this->db->query($query);
        $arr = [];
        while ($row = $result->fetch(PDO::FETCH_NUM)) {
            $arr[] = new DTO($row[0], $row[1]);
        }
        return $arr;
    }

    public function getNextAutoIncrement()
    {
        return $this->db->getNextAutoIncrement($this->table);
    }
}