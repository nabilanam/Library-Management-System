<?php
require_once __DIR__.'/../Models/MailSettings.php';

class MailSettingsRepository
{
    private $db;
    private $table;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->table = 'mail_settings';
    }

    /**
     * @return MailSettings|false
     */
    public function find(){
        $query = "SELECT * FROM $this->table WHERE id=1";
        $result = $this->db->query($query);
        $arr = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)){
            $arr[] = new MailSettings(
                $row['host'],
                $row['username'],
                $row['password'],
                $row['port']
            );
        }
        if (count($arr) == 1){
            return $arr[0];
        }
        return false;
    }

    /**
     * @param MailSettings $settings
     * @return MailSettings|false
     */
    public function save(MailSettings $settings){
        $data = [
            'host'=>$settings->getHost(),
            'username'=>$settings->getUsername(),
            'password'=>$settings->getPassword(),
            'port'=>$settings->getPort()
        ];
        $query = "UPDATE $this->table
        SET host=:host, username=:username, password=:password, port=:port
        WHERE id=1";
        $result = $this->db->bindQuery($query,$data);
        return $result->rowCount() == 1 ? $settings : false;
    }
}