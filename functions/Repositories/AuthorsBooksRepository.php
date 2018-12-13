<?php
require_once 'JoinedRepository.php';
require_once 'SimpleRepositoryFacade.php';
require_once 'BooksRepository.php';

class AuthorsBooksRepository extends JoinedRepository
{

    public function __construct()
    {
        parent::__construct('authors_books');
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

    /**
     * @param $author_id
     * @param $book_id
     * @return bool
     */
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
     * @return DTO[]
     */
    public function findFirst($book_id)
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
     * @return Book[]
     */
    public function findSecond($author_id)
    {
        $result = $this->db->bindQuery("SELECT books_id FROM $this->table WHERE authors_id=:author", ['author' => $author_id]);

        $arr = [];
        $book_repo = new BooksRepository();
        while ($row = $result->fetch(PDO::FETCH_NUM)) {
            $arr[] = $book_repo->findById($row[0]);
        }

        return $arr;
    }

    public function getPaginatedBooks($search, $to, $limit){
        $query = "SELECT books_id FROM authors_books
                  INNER JOIN books b ON books_id = b.id
                  INNER JOIN book_authors a ON authors_id = a.id
                  WHERE a.name
                  LIKE '%$search%'
                  LIMIT $to, $limit";
        $result = $this->db->query($query);

        $arr = [];
        $book_repo = new BooksRepository();
        while ($row = $result->fetch(PDO::FETCH_NUM)) {
            $arr[] = $book_repo->findById($row[0]);
        }

        return $arr;
    }

    public function totalAuthorSearchRecords($search){
        $query = "SELECT count(*) FROM authors_books
                  INNER JOIN books b ON books_id = b.id
                  INNER JOIN book_authors a ON authors_id = a.id
                  WHERE a.name
                  LIKE '%$search%'";
        $result = $this->db->query($query);

        return $result->fetchColumn();
    }
}