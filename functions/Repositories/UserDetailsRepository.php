<?php
require_once 'Repository.php';

class UserDetailsRepository extends Repository
{
    /**
     * UserDetailsRepository constructor.
     */
    public function __construct()
    {
        parent::__construct('user_details');
    }

    /**
     * @param UserDetails $details
     * @return UserDetails|bool
     */
    public function add($details)
    {
        $data = [
            'first_name' => $details->getFirstName(),
            'last_name' => $details->getLastName(),
            'genders_id' => $details->getGender()->getId(),
            'mobile_no' => $details->getMobileNo(),
            'present_address' => $details->getPresentAddress(),
            'permanent_address' => $details->getPermanentAddress(),
            'pro_pic' => $details->getProPic()
        ];
        $query = "INSERT INTO $this->table 
                  SET first_name=:first_name, last_name=:last_name, genders_id=:genders_id, mobile_no=:mobile_no, 
                  present_address=:present_address, permanent_address=:permanent_address, pro_pic=:pro_pic";
        $result = $this->db->bindQuery($query, $data);
        if ($result->rowCount() == 1) {
            $details->setId($this->db->lastInsertId());
            return $details;
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
        $result = $this->db->bindQuery($query, ['id' => $id]);
        return $result->rowCount() == 1;
    }

    public function findById($id)
    {
        // TODO: Implement findById() method.
    }

    /**
     * @param UserDetails $details
     * @return UserDetails|false
     */
    public function update($details)
    {
        $data = [
            'id' => $details->getId(),
            'first_name' => $details->getFirstName(),
            'last_name' => $details->getLastName(),
            'genders_id' => $details->getGender()->getId(),
            'mobile_no' => $details->getMobileNo(),
            'present_address' => $details->getPresentAddress(),
            'permanent_address' => $details->getPermanentAddress(),
            'pro_pic' => $details->getProPic(),
        ];
        $query = "UPDATE $this->table 
                  SET first_name=:first_name, last_name=:last_name, genders_id=:genders_id, mobile_no=:mobile_no, 
                  present_address=:present_address, permanent_address=:permanent_address, pro_pic=:pro_pic 
                  WHERE id=:id";
        $result = $this->db->bindQuery($query, $data);

        return $result->rowCount() == 1? $details : false;
    }

    public function getAll()
    {
        // TODO: Implement getAll() method.
    }
}