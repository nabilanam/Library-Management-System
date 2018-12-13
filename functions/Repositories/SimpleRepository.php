<?php
require_once 'Repository.php';

class SimpleRepository extends Repository
{

    public function __construct($table)
    {
        parent::__construct($table);
    }

    /**
     * @param $name
     * @return false|DTO
     */
    public function add($name)
    {
        $data = [
            'name' => $name
        ];
        $query = "INSERT INTO $this->table SET name=:name";
        $result = $this->db->bindQuery($query, $data);
        if ($result->rowCount() == 1) {
            return new DTO($this->db->lastInsertId(),$name);
        }
        return false;
    }

    /**
     * @param $id
     * @return bool
     */
    public function removeById($id)
    {
        $data = [
            'id' => $id
        ];
        $query = "DELETE FROM $this->table WHERE id=:id";
        $result = $this->db->bindQuery($query, $data);
        return $result->rowCount() == 1;
    }

    /**
     * @param $id
     * @return false|DTO
     */
    public function findById($id)
    {
        $query = "SELECT * FROM $this->table WHERE id=:id";
        $result = $this->db->bindQuery($query, ['id' => $id]);
        $arr = [];
        while ($row = $result->fetch(PDO::FETCH_NUM)) {
            $arr[] = new DTO($row[0], $row[1]);
        }
        if (count($arr) == 1){
            return $arr[0];
        }
        return false;
    }

    /**
     * @param DTO $dto
     * @return DTO|bool
     */
    public function update($dto)
    {
        $data = [
            'id' => $dto->getId(),
            'name' => $dto->getName()
        ];
        $query = "UPDATE $this->table SET name=:name WHERE id=:id";
        $result = $this->db->bindQuery($query, $data);
        return $result->rowCount() == 1 ? $dto : false;
    }

    /**
     * @return DTO[]
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

    /**
     * @param $name
     * @return false|DTO
     */
    public function findByName($name)
    {
        $data = [
            'name' => $name
        ];
        $query = "SELECT id,name FROM $this->table WHERE name=:name";
        $result = $this->db->bindQuery($query, $data);
        $arr = [];
        while ($row = $result->fetch(PDO::FETCH_NUM)) {
            $arr[] = new DTO($row[0], $row[1]);
        }
        if (count($arr) == 1){
            return $arr[0];
        }
        return false;
    }

    /**
     * @param $name
     * @return DTO|false
     */
    public function findOrInsert($name)
    {
        if ($dto = $this->findByName($name)) {
            return $dto;
        }
        return $this->add($name);
    }
}