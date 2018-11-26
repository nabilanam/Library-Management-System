<?php
require_once 'Repository.php';

class InstitutesRepository implements Repository
{
    private $db;
    private $table;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->table= 'institutes';
    }

    /**
     * @param Institute $institute
     * @return bool
     */
    public function add($institute){
        $data = [
            'name'=>$institute->getName(),
            'address'=>$institute->getAddress(),
            'email'=>$institute->getEmail(),
            'phone'=>$institute->getPhone(),
            'logo_path'=>$institute->getLogoPath()
        ];
        $query = "INSERT INTO $this->table SET name=:name, address=:address, email=:email, phone=:phone, logo_path=:logo_path";
        $result = $this->db->bindQuery($query,$data);
        return $result->rowCount() == 1;
    }

    public function remove($id)
    {
        // TODO: Implement remove() method.
    }

    public function find($object)
    {
        // TODO: Implement find() method.
    }

    public function findById($id)
    {
        // TODO: Implement findById() method.
    }

    public function findOrInsert($object)
    {
        // TODO: Implement findOrInsert() method.
    }

    public function update($object)
    {
        // TODO: Implement update() method.
    }

    public function getAll()
    {
        // TODO: Implement getAll() method.
    }

    public function getNextAutoIncrement()
    {
        // TODO: Implement getNextAutoIncrement() method.
    }
}