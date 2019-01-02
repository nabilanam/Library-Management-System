<?php
require_once __DIR__.'/../Models/Mail.php';
require_once 'Repository.php';
require_once 'Pagination.php';

class MailsRepository extends Repository implements Pagination
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
            'message'=>$mail->getMessage(),
            'dtime'=>$mail->getDtime()
        ];
        $query = "INSERT INTO $this->table SET address=:address, subject=:subject, message=:message, dtime=:dtime";
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

    /**
     * @param $id
     * @return bool|Mail
     */
    public function findById($id)
    {
        $data = [
            'id'=>$id
        ];
        $query = "SELECT * FROM $this->table WHERE id=:id";
        $result = $this->db->bindQuery($query, $data);
        if ($result->rowCount() == 1) {
            $row = $result->fetch(PDO::FETCH_ASSOC);
            $mail = new Mail(
                $row['id'],
                $row['address'],
                $row['subject'],
                $row['message'],
                $row['dtime']
            );
            return $mail;
        }
        return false;
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
            $arr[] = new Mail($row['id'],$row['address'],$row['subject'],$row['message'],$row['dtime']);
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
            $arr[] = new Mail($row['id'],$row['address'],$row['subject'],$row['message'],$row['dtime']);
        }
        return $arr;
    }

    public function totalRecordsThisMonth(){
        $query = "SELECT COUNT(*) FROM $this->table
                  WHERE MONTH(dtime) = MONTH(CURRENT_DATE())
                  AND YEAR(dtime) = YEAR(CURRENT_DATE())";
        $result = $this->db->query($query);
        return $result->fetchColumn();
    }
}