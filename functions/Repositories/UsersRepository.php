<?php
require_once __DIR__ . '/../Models/DTO.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/UserType.php';
require_once __DIR__ . '/../Enums/UserTypes.php';
require_once __DIR__ . '/../Models/UserDetails.php';
require_once 'Repository.php';
require_once 'Pagination.php';
require_once 'SimpleRepositoryFacade.php';
require_once 'UserDetailsRepository.php';
require_once 'UserTypesRepository.php';

class UsersRepository extends Repository implements Pagination
{
    private $user_details_repo;

    /**
     * UserRepository constructor.
     */
    public function __construct()
    {
        parent::__construct('users');
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
            'activation_dtime'=> $user->getActivationDatetime()
        ];
        $query = "INSERT INTO $this->table 
                  SET user_types_id=:user_types_id, user_details_id=:user_details_id, 
                  email=:email, password_hash=:password_hash, validation_code=:validation_code, 
                  activated=:activated, activation_dtime=:activation_dtime";
        $result = $this->db->bindQuery($query, $data);
        if ($result->rowCount() == 1) {
            $user->setId($this->db->lastInsertId());
            return $user;
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

    /**
     * @param $id
     * @return User|false
     */
    public function findById($id)
    {
        $query = "SELECT 
                        u.id, u.user_types_id, u.user_details_id, u.email, 
                        u.password_hash, u.validation_code, u.activated, u.activation_dtime,
                        ut.name user_types_name, ut.book_limit, ut.day_limit, ut.fine_per_day,
                        ug.id genders_id, ug.name genders_name, 
                        ud.first_name, ud.last_name, ud.mobile_no, 
                        ud.present_address, ud.permanent_address,
                        ud.pro_pic 
                        FROM $this->table u 
                        INNER JOIN user_types ut ON ut.id = u.user_types_id 
                        INNER JOIN user_details ud on u.user_details_id = ud.id 
                        INNER JOIN user_genders ug on ud.genders_id = ug.id 
                        WHERE u.id=:id";
        $result = $this->db->bindQuery($query, ['id' => $id]);

        $arr = $this->getUsersArray($result);
        if (count($arr) == 1) {
            return $arr[0];
        }
        return false;
    }

    /**
     * @param $email
     * @return User|false
     */
    public function findByEmail($email)
    {
        $query = "SELECT 
                        u.id, u.user_types_id, u.user_details_id, u.email, 
                        u.password_hash, u.validation_code, u.activated, u.activation_dtime,
                        ut.name user_types_name, ut.book_limit, ut.day_limit, ut.fine_per_day,
                        ug.id genders_id, ug.name genders_name, 
                        ud.first_name, ud.last_name, ud.mobile_no, 
                        ud.present_address, ud.permanent_address,
                        ud.pro_pic 
                        FROM $this->table u 
                        INNER JOIN user_types ut ON ut.id = u.user_types_id 
                        INNER JOIN user_details ud on u.user_details_id = ud.id 
                        INNER JOIN user_genders ug on ud.genders_id = ug.id 
                        WHERE u.email=:email";
        $result = $this->db->bindQuery($query, ['email' => $email]);

        $arr = $this->getUsersArray($result);
        return count($arr) == 1 ? $arr[0] : false;
    }

    /**
     * @param User $user
     * @return false|User
     */
    public function update($user)
    {
        $data = [
            'id' => $user->getId(),
            'utype_id' => $user->getUserType()->getId(),
            'udetails_id' => $user->getUserDetails()->getId(),
            'email' => $user->getEmail(),
            'phash' => $user->getPasswordHash(),
            'vcode' => $user->getValidationCode(),
            'activated' => $user->getActivated(),
            'activation_dtime'=> $user->getActivationDatetime()
        ];

        $query = "UPDATE $this->table 
                  SET user_types_id=:utype_id, user_details_id=:udetails_id, email=:email, 
                  password_hash=:phash, validation_code=:vcode, activated=:activated, u.activation_dtime=:activation_dtime
                  WHERE id=:id";
        $result = $this->db->bindQuery($query, $data);

        return $result->rowCount() == 1 ? $user : false;
    }

    /**
     * @return User[]
     */
    public function getAll()
    {
        $query = "SELECT 
                        u.id, u.user_types_id, u.user_details_id, u.email, 
                        u.password_hash, u.validation_code, u.activated, u.activation_dtime,
                        ut.name user_types_name, ut.book_limit, ut.day_limit, ut.fine_per_day,
                        ug.id genders_id, ug.name genders_name, 
                        ud.first_name, ud.last_name, ud.mobile_no, 
                        ud.present_address, ud.permanent_address,
                        ud.pro_pic 
                        FROM $this->table u 
                        INNER JOIN user_types ut ON ut.id = u.user_types_id 
                        INNER JOIN user_details ud on u.user_details_id = ud.id 
                        INNER JOIN user_genders ug on ud.genders_id = ug.id";
        $result = $this->db->query($query);

        return $this->getUsersArray($result);
    }

    /**
     * @param $to
     * @param $limit
     * @return User[]
     */
    public function getPaginated($to, $limit)
    {
        $query = "SELECT 
                        u.id, u.user_types_id, u.user_details_id, u.email, 
                        u.password_hash, u.validation_code, u.activated, u.activation_dtime,
                        ut.name user_types_name, ut.book_limit, ut.day_limit, ut.fine_per_day,
                        ug.id genders_id, ug.name genders_name, 
                        ud.first_name, ud.last_name, ud.mobile_no, 
                        ud.present_address, ud.permanent_address,
                        ud.pro_pic 
                        FROM $this->table u 
                        INNER JOIN user_types ut ON ut.id = u.user_types_id 
                        INNER JOIN user_details ud on u.user_details_id = ud.id 
                        INNER JOIN user_genders ug on ud.genders_id = ug.id
                        WHERE ut.id<>:ut_ins
                        ORDER BY u.id ASC
                        LIMIT $to, $limit";
        $result = $this->db->bindQuery($query, ['ut_ins' => UserTypes::INSTITUTE]);

        return $this->getUsersArray($result);
    }

    /**
     * @param $to
     * @param $limit
     * @return User[]
     */
    public function getInstitutesPaginated($to, $limit)
    {
        $query = "SELECT 
                        u.id, u.user_types_id, u.user_details_id, u.email, 
                        u.password_hash, u.validation_code, u.activated, u.activation_dtime,
                        ut.name user_types_name, ut.book_limit, ut.day_limit, ut.fine_per_day,
                        ug.id genders_id, ug.name genders_name, 
                        ud.first_name, ud.last_name, ud.mobile_no, 
                        ud.present_address, ud.permanent_address,
                        ud.pro_pic 
                        FROM $this->table u 
                        INNER JOIN user_types ut ON ut.id = u.user_types_id 
                        INNER JOIN user_details ud on ud.id = u.user_details_id
                        INNER JOIN user_genders ug on ud.genders_id = ug.id 
                        WHERE ut.id =:ut_ins
                        ORDER BY u.id ASC 
                        LIMIT $to, $limit";
        $result = $this->db->bindQuery($query, ['ut_ins' => UserTypes::INSTITUTE]);

        return $this->getUsersArray($result);
    }

    /**
     * @return array
     */
    public function getAllIds()
    {
        $query = "SELECT id FROM $this->table";
        $result = $this->db->query($query);
        $arr = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $arr[] = $row['id'];
        }
        return $arr;
    }

    public function totalNonInstituteRecords()
    {
        $query = "SELECT COUNT(*) FROM $this->table
                  WHERE user_types_id<>:ut_inst";
        $result = $this->db->bindQuery($query, ['ut_inst' => UserTypes::INSTITUTE]);
        return $result->fetchColumn();
    }

    public function totalInstituteRecords()
    {
        $query = "SELECT COUNT(*) FROM $this->table
                  WHERE user_types_id=:ut_inst";
        $result = $this->db->bindQuery($query, ['ut_inst' => UserTypes::INSTITUTE]);
        return $result->fetchColumn();
    }

    /**
     * @param PDOStatement $result
     * @return User[]
     */
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
                $row['permanent_address'],
                $row['pro_pic']
            );

            $user = new User(
                $row['id'],
                $user_type,
                $user_details,
                $row['email'],
                $row['password_hash'],
                $row['validation_code'],
                $row['activated'],
                $row['activation_dtime']
            );

            $arr[] = $user;
        }
        return $arr;
    }

    public function totalAllTypeSearchRecords($search)
    {
        $query = "SELECT COUNT(*) FROM $this->table
                  INNER JOIN user_details ud ON ud.id = user_details_id 
                  WHERE CONCAT(ud.first_name, ' ', ud.last_name)
                  LIKE '%$search%'";
        $result = $this->db->query($query);
        return $result->fetchColumn();
    }

    public function totalPersonalSearchRecords($search)
    {
        $query = "SELECT COUNT(*) FROM $this->table
                  INNER JOIN user_types ut ON ut.id =  user_types_id
                  INNER JOIN user_details ud ON ud.id = user_details_id 
                  WHERE ut.id = " . UserTypes::PERSONAL . " AND CONCAT(ud.first_name, ' ', ud.last_name)
                  LIKE '%$search%'";
        $result = $this->db->query($query);
        return $result->fetchColumn();
    }

    public function totalStaffSearchRecords($search)
    {
        $query = "SELECT COUNT(*) FROM $this->table
                  INNER JOIN user_types ut ON ut.id =  user_types_id
                  INNER JOIN user_details ud ON ud.id = user_details_id 
                  WHERE ut.id = " . UserTypes::STAFF . " AND CONCAT(ud.first_name, ' ', ud.last_name)
                  LIKE '%$search%'";
        $result = $this->db->query($query);
        return $result->fetchColumn();
    }

    public function totalStudentSearchRecords($search)
    {
        $query = "SELECT COUNT(*) FROM $this->table
                  INNER JOIN user_types ut ON ut.id =  user_types_id
                  INNER JOIN user_details ud ON ud.id = user_details_id 
                  WHERE ut.id = " . UserTypes::STUDENT . " AND CONCAT(ud.first_name, ' ', ud.last_name)
                  LIKE '%$search%'";
        $result = $this->db->query($query);
        return $result->fetchColumn();
    }

    public function totalEducatorSearchRecords($search)
    {
        $query = "SELECT COUNT(*) FROM $this->table
                  INNER JOIN user_types ut ON ut.id =  user_types_id
                  INNER JOIN user_details ud ON ud.id = user_details_id 
                  WHERE ut.id = " . UserTypes::EDUCATOR . " AND CONCAT(ud.first_name, ' ', ud.last_name)
                  LIKE '%$search%'";
        $result = $this->db->query($query);
        return $result->fetchColumn();
    }

    public function totalInstituteSearchRecords($search)
    {
        $query = "SELECT COUNT(*) FROM $this->table
                  INNER JOIN user_types ut ON ut.id =  user_types_id
                  INNER JOIN user_details ud ON ud.id = user_details_id 
                  WHERE ut.id = " . UserTypes::INSTITUTE . " AND ud.first_name
                  LIKE '%$search%'";
        $result = $this->db->query($query);
        return $result->fetchColumn();
    }

    public function totalActivatedRecordsThisMonth()
    {
        $query = "SELECT COUNT(*) FROM $this->table
                  WHERE MONTH(activation_dtime) = MONTH(CURRENT_DATE())
                  AND YEAR(activation_dtime) = YEAR(CURRENT_DATE())";
        $result = $this->db->query($query);
        return $result->fetchColumn();
    }

    public function getPaginatedAllTypeSearch($search, $to, $limit)
    {
        $query = "SELECT 
                        u.id, u.user_types_id, u.user_details_id, u.email, 
                        u.password_hash, u.validation_code, u.activated, u.activation_dtime,
                        ut.name user_types_name, ut.book_limit, ut.day_limit, ut.fine_per_day,
                        ug.id genders_id, ug.name genders_name, 
                        ud.first_name, ud.last_name, ud.mobile_no, 
                        ud.present_address, ud.permanent_address,
                        ud.pro_pic 
                        FROM $this->table u 
                        INNER JOIN user_types ut ON ut.id = u.user_types_id 
                        INNER JOIN user_details ud on ud.id = u.user_details_id
                        INNER JOIN user_genders ug on ud.genders_id = ug.id 
                        WHERE CONCAT(ud.first_name, ' ', ud.last_name)
                        LIKE '%$search%'
                        ORDER BY ud.first_name, u.id
                        LIMIT $to, $limit";
        $result = $this->db->query($query);

        return $this->getUsersArray($result);
    }

    public function getPaginatedPersonalSearch($search, $to, $limit)
    {
        $query = "SELECT 
                        u.id, u.user_types_id, u.user_details_id, u.email, 
                        u.password_hash, u.validation_code, u.activated, u.activation_dtime, 
                        ut.name user_types_name, ut.book_limit, ut.day_limit, ut.fine_per_day,
                        ug.id genders_id, ug.name genders_name, 
                        ud.first_name, ud.last_name, ud.mobile_no, 
                        ud.present_address, ud.permanent_address,
                        ud.pro_pic 
                        FROM $this->table u 
                        INNER JOIN user_types ut ON ut.id = u.user_types_id 
                        INNER JOIN user_details ud on ud.id = u.user_details_id
                        INNER JOIN user_genders ug on ud.genders_id = ug.id 
                        WHERE ut.id =" . UserTypes::PERSONAL . " AND CONCAT(ud.first_name, ' ', ud.last_name)
                        LIKE '%$search%'
                        ORDER BY ud.first_name, u.id
                        LIMIT $to, $limit";
        $result = $this->db->query($query);

        return $this->getUsersArray($result);
    }

    public function getPaginatedStaffSearch($search, $to, $limit)
    {
        $query = "SELECT 
                        u.id, u.user_types_id, u.user_details_id, u.email, 
                        u.password_hash, u.validation_code, u.activated, u.activation_dtime,
                        ut.name user_types_name, ut.book_limit, ut.day_limit, ut.fine_per_day,
                        ug.id genders_id, ug.name genders_name, 
                        ud.first_name, ud.last_name, ud.mobile_no, 
                        ud.present_address, ud.permanent_address,
                        ud.pro_pic 
                        FROM $this->table u 
                        INNER JOIN user_types ut ON ut.id = u.user_types_id 
                        INNER JOIN user_details ud on ud.id = u.user_details_id
                        INNER JOIN user_genders ug on ud.genders_id = ug.id 
                        WHERE ut.id =" . UserTypes::STAFF . " AND CONCAT(ud.first_name, ' ', ud.last_name)
                        LIKE '%$search%'
                        ORDER BY ud.first_name, u.id
                        LIMIT $to, $limit";
        $result = $this->db->query($query);

        return $this->getUsersArray($result);
    }

    public function getPaginatedStudentSearch($search, $to, $limit)
    {
        $query = "SELECT 
                        u.id, u.user_types_id, u.user_details_id, u.email, 
                        u.password_hash, u.validation_code, u.activated, u.activation_dtime,
                        ut.name user_types_name, ut.book_limit, ut.day_limit, ut.fine_per_day,
                        ug.id genders_id, ug.name genders_name, 
                        ud.first_name, ud.last_name, ud.mobile_no, 
                        ud.present_address, ud.permanent_address,
                        ud.pro_pic 
                        FROM $this->table u 
                        INNER JOIN user_types ut ON ut.id = u.user_types_id 
                        INNER JOIN user_details ud on ud.id = u.user_details_id
                        INNER JOIN user_genders ug on ud.genders_id = ug.id 
                        WHERE ut.id =" . UserTypes::STUDENT . " AND CONCAT(ud.first_name, ' ', ud.last_name)
                        LIKE '%$search%'
                        ORDER BY ud.first_name, u.id
                        LIMIT $to, $limit";
        $result = $this->db->query($query);

        return $this->getUsersArray($result);
    }

    public function getPaginatedEducatorSearch($search, $to, $limit)
    {
        $query = "SELECT 
                        u.id, u.user_types_id, u.user_details_id, u.email, 
                        u.password_hash, u.validation_code, u.activated, u.activation_dtime,
                        ut.name user_types_name, ut.book_limit, ut.day_limit, ut.fine_per_day,
                        ug.id genders_id, ug.name genders_name, 
                        ud.first_name, ud.last_name, ud.mobile_no, 
                        ud.present_address, ud.permanent_address,
                        ud.pro_pic 
                        FROM $this->table u 
                        INNER JOIN user_types ut ON ut.id = u.user_types_id 
                        INNER JOIN user_details ud on ud.id = u.user_details_id
                        INNER JOIN user_genders ug on ud.genders_id = ug.id 
                        WHERE ut.id =" . UserTypes::EDUCATOR . " AND CONCAT(ud.first_name, ' ', ud.last_name)
                        LIKE '%$search%'
                        ORDER BY ud.first_name, u.id
                        LIMIT $to, $limit";
        $result = $this->db->query($query);

        return $this->getUsersArray($result);
    }

    public function getPaginatedInstituteSearch($search, $to, $limit)
    {
        $query = "SELECT 
                        u.id, u.user_types_id, u.user_details_id, u.email, 
                        u.password_hash, u.validation_code, u.activated, u.activation_dtime,
                        ut.name user_types_name, ut.book_limit, ut.day_limit, ut.fine_per_day,
                        ug.id genders_id, ug.name genders_name, 
                        ud.first_name, ud.last_name, ud.mobile_no, 
                        ud.present_address, ud.permanent_address,
                        ud.pro_pic 
                        FROM $this->table u 
                        INNER JOIN user_types ut ON ut.id = u.user_types_id 
                        INNER JOIN user_details ud on ud.id = u.user_details_id
                        INNER JOIN user_genders ug on ud.genders_id = ug.id 
                        WHERE ut.id =" . UserTypes::INSTITUTE . " AND ud.first_name
                        LIKE '%$search%'
                        ORDER BY ud.first_name, u.id
                        LIMIT $to, $limit";
        $result = $this->db->query($query);

        return $this->getUsersArray($result);
    }


}