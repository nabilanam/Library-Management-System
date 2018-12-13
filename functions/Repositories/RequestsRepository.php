<?php
require_once 'Pagination.php';
require_once 'UsersRepository.php';
require_once 'BooksRepository.php';
require_once __DIR__.'/../Models/Request.php';
require_once __DIR__.'/../Enums/Status.php';

class RequestsRepository extends Repository implements Pagination
{
    private $users_repo;
    private $books_repo;

    public function __construct()
    {
        parent::__construct('requests');
        $this->users_repo = new UsersRepository();
        $this->books_repo = new BooksRepository();
    }


    /**
     * @param Request $request
     * @return bool|Request
     */
    public function add($request)
    {
        $data = [
            'books_id' => $request->getBook()->getId(),
            'users_id' => $request->getUser()->getId(),
            'request_status_id' => $request->getStatus()->getId(),
            'request_date' => $request->getRequestDate(),
            'issue_date' => $request->getIssueDate(),
            'return_date' => $request->getReturnDate(),
            'receive_date' => $request->getReceiveDate(),
            'total_fine' => $request->getTotalFine(),
            'user_read' => $request->getUserRead()
        ];
        $query = "INSERT INTO requests 
                    SET books_id=:books_id,users_id=:users_id,
                    request_status_id=:request_status_id, 
                    request_date=:request_date, issue_date=:issue_date, 
                    return_date=:return_date,receive_date=:receive_date,
                    total_fine=:total_fine, user_read=:user_read";
        $result = $this->db->bindQuery($query, $data);
        if ($result->rowCount() == 1) {
            $request->setId($this->db->lastInsertId());
            return $request;
        }
        return false;
    }

    public function removeById($id)
    {
        // TODO: Implement remove() method.
    }

    /**
     * @param $id
     * @return Request|false
     */
    public function findById($id)
    {
        $query = "SELECT requests.id, books_id, users_id, request_status_id, 
                    request_date, issue_date, return_date, receive_date, total_fine, user_read,
                    rs.id status_id, rs.name status_name
                    FROM $this->table
                    INNER JOIN request_status rs on $this->table.request_status_id = rs.id
                    WHERE $this->table.id=:id";
        $result = $this->db->bindQuery($query,['id'=>$id]);
        $arr = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $user = $this->users_repo->findById($row['users_id']);
            $book = $this->books_repo->findById($row['books_id']);
            $status = new DTO($row['status_id'], $row['status_name']);
            $request = new Request($row['id'], $book, $user, $status, $row['request_date'], $row['issue_date'], $row['return_date'], $row['receive_date'], $row['total_fine'], $row['user_read']);
            $arr[] = $request;
        }
        if (count($arr) == 1){
            return $arr[0];
        }
        return false;
    }

    /**
     * @param $user_id
     * @param $book_id
     * @param $issue_date
     * @return false|Request
     */
    public function findByUserIdBookIdIssueDate($user_id, $book_id, $issue_date)
    {
        $data = [
            'user_id'=>$user_id,
            'book_id'=>$book_id,
            'issue_date'=>$issue_date,
        ];
        $query = "SELECT requests.id, books_id, users_id, request_status_id, 
                    request_date, issue_date, return_date, receive_date, total_fine, user_read,
                    rs.id status_id, rs.name status_name
                    FROM $this->table
                    INNER JOIN request_status rs on $this->table.request_status_id = rs.id
                    WHERE $this->table.users_id=:user_id AND $this->table.books_id=:book_id AND $this->table.issue_date=:issue_date";
        $query = $query . ' ORDER BY requests.id DESC';
        $result = $this->db->bindQuery($query,$data);
        $arr = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $user = $this->users_repo->findById($row['users_id']);
            $book = $this->books_repo->findById($row['books_id']);
            $status = new DTO($row['status_id'], $row['status_name']);
            $request = new Request($row['id'], $book, $user, $status, $row['request_date'], $row['issue_date'], $row['return_date'], $row['receive_date'], $row['total_fine'], $row['user_read']);
            $arr[] = $request;
        }
        return count($arr) == 1 ? $arr[0] : false;
    }

    /**
     * Finds requests by user id (eager loading)
     * @param $user_id
     * @param $skip_user_read
     * @return Request[]
     */
    public function findByUserIdPaginated($user_id, $skip_user_read, $to, $limit)
    {
        $query = "SELECT requests.id, books_id, users_id, request_status_id, 
                    request_date, issue_date, return_date, receive_date, total_fine, user_read,
                    rs.id status_id, rs.name status_name
                    FROM $this->table
                    INNER JOIN request_status rs on $this->table.request_status_id = rs.id
                    WHERE requests.users_id=:id ";
        if ($skip_user_read){
            $query = $query . "AND user_read = 0 ";
        }
        $query = $query . "ORDER BY requests.id DESC LIMIT $to, $limit";
        $result = $this->db->bindQuery($query,['id'=>$user_id]);
        $arr = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $user = $this->users_repo->findById($row['users_id']);
            $book = $this->books_repo->findById($row['books_id']);
            $status = new DTO($row['status_id'], $row['status_name']);
            $request = new Request($row['id'], $book, $user, $status, $row['request_date'], $row['issue_date'], $row['return_date'], $row['receive_date'], $row['total_fine'], $row['user_read']);
            $arr[] = $request;
        }
        return $arr;
    }

    /**
     * @param Request $request
     * @return Request|bool
     */
    public function update($request)
    {
        $data = [
            'id' => $request->getId(),
            'books_id' => $request->getBook()->getId(),
            'users_id' => $request->getUser()->getId(),
            'request_status_id' => $request->getStatus()->getId(),
            'request_date' => $request->getRequestDate(),
            'issue_date' => $request->getIssueDate(),
            'return_date' => $request->getReturnDate(),
            'receive_date' => $request->getReceiveDate(),
            'total_fine' => $request->getTotalFine(),
            'user_read' => $request->getUserRead()
        ];
        $query = "UPDATE requests 
                    SET books_id=:books_id,users_id=:users_id,
                    request_status_id=:request_status_id, 
                    request_date=:request_date, issue_date=:issue_date, 
                    return_date=:return_date,receive_date=:receive_date,
                    total_fine=:total_fine, user_read=:user_read
                    WHERE id=:id";
        $result = $this->db->bindQuery($query, $data);
        return $result->rowCount() == 1 ? $request : false;
    }

    /**
     * @return Request[]
     */
    public function getAll()
    {
        $query = "SELECT requests.id, books_id, users_id, request_status_id, 
                    request_date, issue_date, return_date, receive_date, total_fine, user_read,
                    rs.id status_id, rs.name status_name
                    FROM $this->table
                    INNER JOIN request_status rs on $this->table.request_status_id = rs.id";
        $result = $this->db->query($query);
        $arr = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $user = $this->users_repo->findById($row['users_id']);
            $book = $this->books_repo->findById($row['books_id']);
            $status = new DTO($row['status_id'], $row['status_name']);
            $request = new Request($row['id'], $book, $user, $status, $row['request_date'], $row['issue_date'], $row['return_date'], $row['receive_date'], $row['total_fine'], $row['user_read']);
            $arr[] = $request;
        }
        return $arr;
    }


    /**
     * @return Request[]
     */
    public function getNonReturnedBooksByUserIdPaginated($id, $to, $limit)
    {
        $query = "SELECT requests.id, books_id, users_id, request_status_id, 
                    request_date, issue_date, return_date, receive_date, total_fine, user_read,
                    rs.id status_id, rs.name status_name
                    FROM $this->table
                    INNER JOIN request_status rs on $this->table.request_status_id = rs.id
                    WHERE users_id=:id AND return_date < CURDATE() 
                    LIMIT $to, $limit";
        $result = $this->db->bindQuery($query,['id'=>$id]);
        $arr = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $user = $this->users_repo->findById($row['users_id']);
            $book = $this->books_repo->findById($row['books_id']);
            $status = new DTO($row['status_id'], $row['status_name']);
            $request = new Request($row['id'], $book, $user, $status, $row['request_date'], $row['issue_date'], $row['return_date'], $row['receive_date'], $row['total_fine'], $row['user_read']);
            $arr[] = $request;
        }
        return $arr;
    }

    /**
     * @return Request[]
     */
    public function getAllNonReturnedBooksPaginated($to, $limit)
    {
        $query = "SELECT requests.id, books_id, users_id, request_status_id, 
                    request_date, issue_date, return_date, receive_date, total_fine, user_read,
                    rs.id status_id, rs.name status_name
                    FROM $this->table
                    INNER JOIN request_status rs on $this->table.request_status_id = rs.id
                    WHERE return_date < CURDATE() 
                    LIMIT $to, $limit";
        $result = $this->db->query($query);
        $arr = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $user = $this->users_repo->findById($row['users_id']);
            $book = $this->books_repo->findById($row['books_id']);
            $status = new DTO($row['status_id'], $row['status_name']);
            $request = new Request($row['id'], $book, $user, $status, $row['request_date'], $row['issue_date'], $row['return_date'], $row['receive_date'], $row['total_fine'], $row['user_read']);
            $arr[] = $request;
        }
        return $arr;
    }

    /**
     * @param $to
     * @param $limit
     * @return Request[]
     */
    public function getPaginated($to, $limit)
    {
        $query = "SELECT requests.id, books_id, users_id, request_status_id, 
                    request_date, issue_date, return_date, receive_date, total_fine, user_read,
                    rs.id status_id, rs.name status_name
                    FROM $this->table
                    INNER JOIN request_status rs on $this->table.request_status_id = rs.id
                    ORDER BY requests.id DESC
                    LIMIT $to, $limit";
        $result = $this->db->query($query);
        $arr = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $user = $this->users_repo->findById($row['users_id']);
            $book = $this->books_repo->findById($row['books_id']);
            $status = new DTO($row['status_id'], $row['status_name']);
            $request = new Request($row['id'], $book, $user, $status, $row['request_date'], $row['issue_date'], $row['return_date'], $row['receive_date'], $row['total_fine'], $row['user_read']);
            $arr[] = $request;
        }
        return $arr;
    }

    /**
     * @return Request[]
     */
    public function getAllPendingsPaginated($to, $limit)
    {
        $data = [
            'status_id'=>Status::PENDING
        ];
        $query = "SELECT requests.id, books_id, users_id, request_status_id, 
                    request_date, issue_date, return_date, receive_date, total_fine, user_read,
                    rs.id status_id, rs.name status_name
                    FROM $this->table
                    INNER JOIN request_status rs on $this->table.request_status_id = rs.id
                    WHERE request_status_id =:status_id 
                    ORDER BY requests.id DESC
                    LIMIT $to, $limit";

        $result = $this->db->bindQuery($query,$data);
        $arr = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $user = $this->users_repo->findById($row['users_id']);
            $book = $this->books_repo->findById($row['books_id']);
            $status = new DTO($row['status_id'], $row['status_name']);
            $request = new Request($row['id'], $book, $user, $status, $row['request_date'], $row['issue_date'], $row['return_date'], $row['receive_date'], $row['total_fine'], $row['user_read']);
            $arr[] = $request;
        }
        return $arr;
    }

    public function countAllBooksByUserId($user_id)
    {
        $data = [
            'user_id'=>$user_id
        ];
        $query = "SELECT COUNT(*) FROM requests
                  WHERE users_id=:user_id";
        $result = $this->db->bindQuery($query,$data);
        return $result->fetchColumn();
    }

    public function countAllNonReturnedBooks()
    {
        $data = [
            'status_id'=>Status::APPROVED
        ];
        $query = "SELECT COUNT(*) FROM requests
                  WHERE request_status_id=:status_id";
        $result = $this->db->bindQuery($query,$data);
        return $result->fetchColumn();
    }

    public function countAllPendingBooks()
    {
        $data = [
            'status_id'=>Status::PENDING
        ];
        $query = "SELECT COUNT(*) FROM requests
                  WHERE request_status_id=:status_id";
        $result = $this->db->bindQuery($query,$data);
        return $result->fetchColumn();
    }

    public function countAllPendingBooksByUserId($id)
    {
        $data = [
            'id'=>$id,
            'status_id'=>Status::PENDING
        ];
        $query = "SELECT COUNT(*) FROM requests
                  WHERE users_id=:id AND request_status_id=:status_id";
        $result = $this->db->bindQuery($query,$data);
        return $result->fetchColumn();
    }

    public function countNonReturnedBooksByUserId($user_id)
    {
        $data = [
            'user_id'=>$user_id,
            'status_id'=>Status::APPROVED
        ];
        $query = "SELECT COUNT(*) FROM requests
                  WHERE users_id=:user_id AND request_status_id=:status_id";
        $result = $this->db->bindQuery($query,$data);
        return $result->fetchColumn();
    }
}