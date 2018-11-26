<?php
require_once __DIR__ . '/../Models/DTO.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/UserType.php';
require_once __DIR__ . '/../Models/UserDetails.php';
require_once 'Repository.php';
require_once 'SimpleRepositoryFacade.php';
require_once 'UserDetailsRepository.php';
require_once 'UserTypesRepository.php';

class UsersRepository implements Repository
{

    /* @var Database */
    private $db;
    private $table;
    private $user_details_repo;

    /**
     * UserRepository constructor.
     */
    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->table = 'users';
        $this->user_details_repo = new UserDetailsRepository();
    }

    /* @var User $user
     * @return User|bool
     */
    public function add($user)
    {
        $data = [
            'user_types_id' => $user->getUserType()->getId(),
            'user_details_id' => $user->getUserDetails()->getId(),
            'email' => $user->getEmail(),
            'password_hash' => $user->getPasswordHash(),
            'validation_code' => $user->getValidationCode(),
            'activated' => $user->getActivated(),
        ];
        $query = "INSERT INTO $this->table SET user_types_id=:user_types_id, user_details_id=:user_details_id, email=:email, password_hash=:password_hash, validation_code=:validation_code, activated=:activated";
        $result = $this->db->bindQuery($query, $data);
        if ($result->rowCount() == 1) {
            $user->setId($this->db->lastInsertId());
            return $user;
        }
        return false;
    }

    public function remove($id)
    {
        $query = "DELETE FROM $this->table WHERE id=:id";
        $result = $this->db->bindQuery($query, ['id' => $id]);
        return $result->rowCount() == 1;
    }

    public function find($user)
    {
        // TODO: Implement find() method.
    }

    /**
     * @param $id
     * @return array User
     */
    public function findById($id)
    {
        $query = "SELECT 
                        u.id, u.user_types_id, u.user_details_id, u.email, u.password_hash, u.validation_code, u.activated, 
                        ut.name user_types_name, ut.book_limit, ut.day_limit, ut.fine_per_day,
                        ug.id genders_id, ug.name genders_name, 
                        ud.first_name, ud.last_name, ud.mobile_no, ud.present_address, ud.permanent_address
                        FROM $this->table u 
                        INNER JOIN user_types ut ON ut.id = u.user_types_id 
                        INNER JOIN user_details ud on u.user_details_id = ud.id 
                        INNER JOIN user_genders ug on ud.genders_id = ug.id 
                        WHERE u.id=:id";
        $result = $this->db->bindQuery($query, ['id' => $id]);

        return $this->getUsersArray($result);
    }

    public function findByEmail($email)
    {
        $query = "SELECT 
                        u.id, u.user_types_id, u.user_details_id, u.email, u.password_hash, u.validation_code, u.activated, 
                        ut.name user_types_name, ut.book_limit, ut.day_limit, ut.fine_per_day,
                        ug.id genders_id, ug.name genders_name, 
                        ud.first_name, ud.last_name, ud.mobile_no, ud.present_address, ud.permanent_address
                        FROM $this->table u 
                        INNER JOIN user_types ut ON ut.id = u.user_types_id 
                        INNER JOIN user_details ud on u.user_details_id = ud.id 
                        INNER JOIN user_genders ug on ud.genders_id = ug.id 
                        WHERE u.email=:email";
        $result = $this->db->bindQuery($query, ['email' => $email]);

        return $this->getUsersArray($result);
    }

    public function findOrInsert($user)
    {
        // TODO: Implement findOrInsert() method.
    }

    /**
     * @param User $user
     * @return bool
     */
    public function update($user)
    {
        $this->db->beginTransaction();
        $this->user_details_repo->update($user->getUserDetails());

        $data = [
            'id' => $user->getId(),
            'utype_id' => $user->getUserType()->getId(),
            'udetails_id' => $user->getUserDetails()->getId(),
            'email' => $user->getEmail(),
            'phash' => $user->getPasswordHash(),
            'vcode' => $user->getValidationCode(),
            'activated' => $user->getActivated()
        ];

        $query = "UPDATE $this->table SET user_types_id=:utype_id, user_details_id=:udetails_id, email=:email, password_hash=:phash, validation_code=:vcode, activated=:activated WHERE id=:id";
        $result = $this->db->bindQuery($query, $data);
        $this->db->commit();

        return $result->rowCount() == 1;
    }

    public function getAll()
    {
        $query = "SELECT 
                        u.id, u.user_types_id, u.user_details_id, u.email, u.password_hash, u.validation_code, u.activated, 
                        ut.name user_types_name, ut.book_limit, ut.day_limit, ut.fine_per_day,
                        ug.id genders_id, ug.name genders_name, 
                        ud.first_name, ud.last_name, ud.mobile_no, ud.present_address, ud.permanent_address 
                        FROM $this->table u 
                        INNER JOIN user_types ut ON ut.id = u.user_types_id 
                        INNER JOIN user_details ud on u.user_details_id = ud.id 
                        INNER JOIN user_genders ug on ud.genders_id = ug.id";
        $result = $this->db->query($query);

        return $this->getUsersArray($result);
    }

    public function getNextAutoIncrement()
    {
        // TODO: Implement getNextAutoIncrement() method.
    }

    private function getUsersArray($result)
    {
        $arr = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $user_type = new UserType(
                $row['user_types_id'],
                $row['user_types_name'],
                $row['book_limit'],
                $row['day_limit'],
                $row['fine_per_day']
            );

            $user_gender = new DTO($row['genders_id'], $row['genders_name']);

            $user_details = new UserDetails(
                $row['user_details_id'],
                $user_gender,
                $row['first_name'],
                $row['last_name'],
                $row['mobile_no'],
                $row['present_address'],
                $row['permanent_address']
            );

            $user = new User(
                $row['id'],
                $user_type,
                $user_details,
                $row['email'],
                $row['password_hash'],
                $row['validation_code'],
                $row['activated']
            );

            $arr[] = $user;
        }
        return $arr;
    }
}