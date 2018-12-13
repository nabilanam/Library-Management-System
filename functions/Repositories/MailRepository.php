<?php
require_once '../Models/Mail.php';
require_once 'Pagination.php';

class MailRepository extends Repository implements Pagination
{

    /**
     * MailRepository constructor.
     */
    public function __construct()
    {
        parent::__construct('mails');
    }

    /**
     * @param Mail $mail
     * @return bool|Mail
     */
    public function add($mail)
    {
        $data = [
            'address'=>$mail->getAddress(),
            'subject'=>$mail->getSubject(),
            'message'=>$mail->getMessage()
        ];
        $query = "INSERT INTO $this->table SET address=:address, subject=:subject, message=:message";
        $result = $this->db->bindQuery($query, $data);
        if ($result->rowCount() == 1) {
            $mail->setId($this->db->lastInsertId());
            return $mail;
        }
        return false;
    }

    public function removeById($id)
    {
        // TODO: Implement removeById() method.
    }

    public function findById($id_arr)
    {
        // TODO: Implement findById() method.
    }

    public function update($mail)
    {
        // TODO: Implement update() method.
    }

    /**
     * @return Mail[]
     */
    public function getAll()
    {
        $query = "SELECT * FROM $this->table";
        $result = $this->db->query($query);
        $arr = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)){
            $arr[] = new Mail($row['id'],$row['address'],$row['subject'],$row['message']);
        }
        return $arr;
    }

    /**
     * @param $to
     * @param $limit
     * @return Mail[]
     */
    public function getPaginated($to, $limit)
    {
        $query = "SELECT * FROM $this->table 
                  LIMIT $to, $limit";
        $result = $this->db->query($query);
        $arr = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)){
            $arr[] = new Mail($row['id'],$row['address'],$row['subject'],$row['message']);
        }
        return $arr;
    }
}