<?php
require_once 'Repository.php';

class UserTypesRepository extends Repository
{

    public function __construct()
    {
        parent::__construct('user_types');
    }

    /**
     * @param UserType $type
     * @return false|UserType
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
        if($result->rowCount() == 1){
            $type->setId($this->db->lastInsertId());
            return $type;
        }
        return false;
    }

    /**
     * @param $id
     * @return bool
     */
    public function removeById($id)
    {
        $query = "DELETE FROM $this->table WHERE id=:id";
        $result = $this->db->bindQuery($query,['id'=>$id]);
        return $result->rowCount() == 1;
    }

    /**
     * @param $id
     * @return UserType|false
     */
    public function findById($id)
    {
        $query = "SELECT * FROM $this->table WHERE id=:id";
        $result = $this->db->bindQuery($query,['id'=>$id]);
        $arr = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)){
            $type = new UserType($row['id'],$row['name'],$row['book_limit'],$row['day_limit'],$row['fine_per_day']);
            $arr[] = $type;
        }
        return count($arr) == 1 ? $arr[0] : false;
    }

    /**
     * @param UserType $type
     * @return UserType|false
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
        return $result->rowCount() == 1 ? $type : false;
    }

    /**
     * @return UserType[]
     */
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
}