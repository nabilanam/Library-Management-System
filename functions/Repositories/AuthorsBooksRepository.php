<?php
require_once 'SimpleRepositoryFacade.php';
require_once 'BooksRepository.php';

class AuthorsBooksRepository
{

    private $db;
    private $table;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->table = 'authors_books';
    }

    /**
     * @param $author_id
     * @param $book_id
     * @return bool|string
     */
    public function join($author_id, $book_id)
    {
        $data = [
            'authors_id' => $author_id,
            'books_id' => $book_id
        ];
        $query = "INSERT INTO $this->table SET authors_id=:authors_id,books_id=:books_id";
        $result = $this->db->bindQuery($query, $data);
        return $result->rowCount() == 1 ? $this->db->lastInsertId() : false;
    }

    public function remove($author_id, $book_id){
        $data = [
            'authors_id' => $author_id,
            'books_id' => $book_id
        ];
        $query = "DELETE FROM $this->table WHERE authors_id=:authors_id AND books_id=:books_id";
        $result = $this->db->bindQuery($query, $data);
        return $result->rowCount() > 0;
    }

    /**
     * @param $author_id
     * @param $book_id
     * @return bool
     */
    public function isJoinExist($author_id, $book_id){
        $data = [
            'authors_id' => $author_id,
            'books_id' => $book_id
        ];
        $query = "SELECT count(*) FROM $this->table WHERE authors_id=:authors_id AND books_id=:books_id";
        $result = $this->db->bindQuery($query, $data);
        return (int)$result->fetchColumn() > 0;
    }

    /**
     * @param $book_id
     * @return array DTO
     */
    public function findAuthors($book_id)
    {
        $result = $this->db->bindQuery("SELECT authors_id FROM $this->table WHERE books_id=:book", ['book' => $book_id]);

        $arr = [];
        $author_repo = SimpleRepositoryFacade::getAuthorsRepository();
        while ($row = $result->fetch(PDO::FETCH_NUM)) {
            $arr[] = $author_repo->findById($row[0]);
        }

        return $arr;
    }

    /**
     * @param $author_id
     * @return array Book
     */
    public function findBooks($author_id)
    {
        $result = $this->db->bindQuery("SELECT books_id FROM $this->table WHERE authors_id=:author", ['author' => $author_id]);

        $arr = [];
        $book_repo = new BooksRepository();
        while ($row = $result->fetch(PDO::FETCH_NUM)) {
            $arr[] = $book_repo->findById($row[0]);
        }

        return $arr;
    }
}