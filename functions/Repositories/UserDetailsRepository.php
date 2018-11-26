<?php
require_once 'Repository.php';

class UserDetailsRepository implements Repository
{
    private $db;
    private $table;

    /**
     * UserDetailsRepository constructor.
     */
    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->table = 'user_details';
    }


    /**
     * @param UserDetails $details
     * @return UserDetails|bool
     */
    public function add($details)
    {
        $data = [
            'first_name'=>$details->getFirstName(),
            'last_name'=>$details->getLastName(),
            'genders_id'=>$details->getGender()->getId(),
            'mobile_no'=>$details->getMobileNo(),
            'present_address'=>$details->getPresentAddress(),
            'permanent_address'=>$details->getPermanentAddress()
        ];
        $query = "INSERT INTO $this->table SET first_name=:first_name, last_name=:last_name, genders_id=:genders_id, mobile_no=:mobile_no, present_address=:present_address, permanent_address=:permanent_address";
        $result = $this->db->bindQuery($query, $data);
        if($result->rowCount() == 1){
            $details->setId($this->db->lastInsertId());
            return $details;
        }
        return false;
    }

    public function remove($id)
    {
        $query = "DELETE FROM $this->table WHERE id=:id";
        $result = $this->db->bindQuery($query,['id'=>$id]);
        return $result->rowCount() == 1;
    }

    public function find($details)
    {
        // TODO: Implement find() method.
    }

    public function findById($id)
    {
        // TODO: Implement findById() method.
    }

    public function findOrInsert($details)
    {
        // TODO: Implement findOrInsert() method.
    }

    /**
     * @param UserDetails $details
     * @return bool
     */
    public function update($details)
    {
        $data = [
            'id'=>$details->getId(),
            'first_name'=>$details->getFirstName(),
            'last_name'=>$details->getLastName(),
            'genders_id'=>$details->getGender()->getId(),
            'mobile_no'=>$details->getMobileNo(),
            'present_address'=>$details->getPresentAddress(),
            'permanent_address'=>$details->getPermanentAddress()
        ];
        $query = "UPDATE $this->table SET first_name=:first_name, last_name=:last_name, genders_id=:genders_id, mobile_no=:mobile_no, present_address=:present_address, permanent_address=:permanent_address WHERE id=:id";
        $result = $this->db->bindQuery($query, $data);
        return $result->rowCount() == 1;
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