<?php
require_once __DIR__ . '/../Models/DTO.php';
require_once __DIR__ . '/../Models/Book.php';
require_once 'Repository.php';
require_once 'AuthorsBooksRepository.php';
require_once 'SimpleRepositoryFacade.php';

class BooksRepository implements Repository
{
    private $db;
    private $table;
    private $categories_repo;
    private $conditions_repo;
    private $publishers_repo;
    private $shelves_repo;
    private $sources_repo;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->table = 'books';
        $this->categories_repo = SimpleRepositoryFacade::getCategoriesRepository();
        $this->conditions_repo = SimpleRepositoryFacade::getConditionsRepository();
        $this->publishers_repo = SimpleRepositoryFacade::getPublishersRepository();
        $this->shelves_repo = SimpleRepositoryFacade::getShelvesRepository();
        $this->sources_repo = SimpleRepositoryFacade::getSourcesRepository();
    }

    /* @var Book $book
     * @return Book|bool
     */
    public function add($book)
    {
        $data = [
            'shelves_id' => $book->getShelf()->getId(),
            'sources_id' => $book->getSource()->getId(),
            'categories_id' => $book->getCategory()->getId(),
            'publishers_id' => $book->getPublisher()->getId(),
            'conditions_id' => $book->getCondition()->getId(),
            'isbn' => $book->getIsbn(),
            'edition_year' => $book->getEditionYear(),
            'publication_year' => $book->getPublicationYear(),
            'total_pages' => $book->getTotalPages(),
            'total_copies' => $book->getTotalCopies(),
            'available_copies' => $book->getAvailableCopies(),
            'price' => $book->getPrice(),
            'title' => $book->getTitle(),
            'subtitle' => $book->getSubtitle(),
            'edition' => $book->getEdition(),
            'note' => $book->getNote(),
            'cover_path' => $book->getCoverPath(),
            'ebook_path' => $book->getEbookPath()
        ];

        $query = "INSERT INTO $this->table SET  shelves_id=:shelves_id, sources_id=:sources_id, categories_id=:categories_id,"
            . "publishers_id=:publishers_id, conditions_id=:conditions_id, isbn=:isbn, title=:title, subtitle=:subtitle,"
            . "edition=:edition, edition_year=:edition_year, publication_year=:publication_year, total_pages=:total_pages,"
            . "total_copies=:total_copies, available_copies=:available_copies, price=:price, note=:note, cover_path=:cover_path,"
            . "ebook_path=:ebook_path";

        $result = $this->db->bindQuery($query, $data);

        if ($result->rowCount() == 1) {
            $book->setId($this->db->lastInsertId());
            return $book;
        }
        return false;
    }

    /**
     * @param $id
     * @return bool
     */
    public function remove($id)
    {
        /* @var Book $book */
        $book = $this->findById($id)[0];
        if (file_exists($book->getCoverPath())) {
            unlink($book->getCoverPath());
        }
        if (file_exists($book->getEbookPath())) {
            unlink($book->getEbookPath());
        }

        $query = "DELETE FROM $this->table WHERE id=:id";
        $result = $this->db->bindQuery($query, ['id' => $id]);
        return $result->rowCount() == 1;
    }

    public function find($book)
    {
        // TODO: Implement find() method.
    }

    /**
     * @param $id
     * @return array Book
     */
    public function findById($id)
    {
        $query = "SELECT bk.id, bk.shelves_id, bk.publishers_id, bk.sources_id, bk.conditions_id, bk.categories_id,
                bk.isbn, bk.title, bk.subtitle, bk.edition, bk.edition_year, bk.publication_year, bk.total_pages, 
                bk.total_copies, bk.available_copies, bk.price, bk.note, bk.cover_path, bk.ebook_path,
                bct.name categories_name, 
                bcn.name conditions_name, 
                bp.name publishers_name, 
                bsh.name shelves_name, 
                bsr.name sources_name 
                FROM $this->table bk 
                INNER JOIN book_categories bct ON bk.categories_id = bct.id 
                INNER JOIN book_conditions bcn on bk.conditions_id = bcn.id 
                INNER JOIN book_publishers bp on bk.publishers_id = bp.id 
                INNER JOIN book_shelves bsh on bk.shelves_id = bsh.id 
                INNER JOIN book_sources bsr on bk.sources_id = bsr.id 
                WHERE bk.id=:id";
        $result = $this->db->bindQuery($query, ['id' => $id]);

        return $this->getBooksArray($result);
    }

    public function findByCategory($id)
    {
        $query = "SELECT bk.id, bk.shelves_id, bk.publishers_id, bk.sources_id, bk.conditions_id, bk.categories_id,
                bk.isbn, bk.title, bk.subtitle, bk.edition, bk.edition_year, bk.publication_year, bk.total_pages, 
                bk.total_copies, bk.available_copies, bk.price, bk.note, bk.cover_path, bk.ebook_path,
                bct.name categories_name, 
                bcn.name conditions_name, 
                bp.name publishers_name, 
                bsh.name shelves_name, 
                bsr.name sources_name 
                FROM $this->table bk 
                INNER JOIN book_categories bct ON bk.categories_id = bct.id 
                INNER JOIN book_conditions bcn on bk.conditions_id = bcn.id 
                INNER JOIN book_publishers bp on bk.publishers_id = bp.id 
                INNER JOIN book_shelves bsh on bk.shelves_id = bsh.id 
                INNER JOIN book_sources bsr on bk.sources_id = bsr.id 
                WHERE bct.id=:id";
        $result = $this->db->bindQuery($query, ['id' => $id]);

        return $this->getBooksArray($result);
    }

    public function findByShelf($id)
    {
        $query = "SELECT bk.id, bk.shelves_id, bk.publishers_id, bk.sources_id, bk.conditions_id, bk.categories_id,
                bk.isbn, bk.title, bk.subtitle, bk.edition, bk.edition_year, bk.publication_year, bk.total_pages, 
                bk.total_copies, bk.available_copies, bk.price, bk.note, bk.cover_path, bk.ebook_path,
                bct.name categories_name, 
                bcn.name conditions_name, 
                bp.name publishers_name, 
                bsh.name shelves_name, 
                bsr.name sources_name 
                FROM $this->table bk 
                INNER JOIN book_categories bct ON bk.categories_id = bct.id 
                INNER JOIN book_conditions bcn on bk.conditions_id = bcn.id 
                INNER JOIN book_publishers bp on bk.publishers_id = bp.id 
                INNER JOIN book_shelves bsh on bk.shelves_id = bsh.id 
                INNER JOIN book_sources bsr on bk.sources_id = bsr.id 
                WHERE bsh.id=:id";
        $result = $this->db->bindQuery($query, ['id' => $id]);

        return $this->getBooksArray($result);
    }

    public function findOrInsert($book)
    {
        // TODO: Implement findOrUpdate() method.
    }

    /**
     * @param Book $book
     * @return Book|bool
     */
    public function update($book)
    {
        $data = [
            'id' => $book->getId(),
            'shelves_id' => $book->getShelf()->getId(),
            'sources_id' => $book->getSource()->getId(),
            'categories_id' => $book->getCategory()->getId(),
            'publishers_id' => $book->getPublisher()->getId(),
            'conditions_id' => $book->getCondition()->getId(),
            'isbn' => $book->getIsbn(),
            'edition_year' => $book->getEditionYear(),
            'publication_year' => $book->getPublicationYear(),
            'total_pages' => $book->getTotalPages(),
            'total_copies' => $book->getTotalCopies(),
            'available_copies' => $book->getAvailableCopies(),
            'price' => $book->getPrice(),
            'title' => $book->getTitle(),
            'subtitle' => $book->getSubtitle(),
            'edition' => $book->getEdition(),
            'note' => $book->getNote()
        ];

        $query = "UPDATE $this->table SET  shelves_id=:shelves_id, sources_id=:sources_id, categories_id=:categories_id,"
            . "publishers_id=:publishers_id, conditions_id=:conditions_id, isbn=:isbn, title=:title, subtitle=:subtitle,"
            . "edition=:edition, edition_year=:edition_year, publication_year=:publication_year, total_pages=:total_pages,"
            . "total_copies=:total_copies, available_copies=:available_copies, price=:price, note=:note";

        if (!empty($book->getCoverPath())) {
            $data['cover_path'] = $book->getCoverPath();
            $query = $query . ", cover_path=:cover_path";
        }
        if (!empty($book->getCoverPath())) {
            $data['ebook_path'] = $book->getEbookPath();
            $query = $query . ", ebook_path=:ebook_path";
        }

        $query = $query . " WHERE id=:id";

        $result = $this->db->bindQuery($query, $data);

        if ($result->rowCount() == 1) {
            return $book;
        }
        return false;
    }

    /**
     * @return array Book
     */
    public function getAll()
    {
        $query = "SELECT bk.id, bk.shelves_id, bk.publishers_id, bk.sources_id, bk.conditions_id, bk.categories_id,"
            . " bk.isbn, bk.title, bk.subtitle, bk.edition, bk.edition_year, bk.publication_year, bk.total_pages,"
            . " bk.total_copies, bk.available_copies, bk.price, bk.note, bk.cover_path, bk.ebook_path,"
            . " bct.name categories_name, bcn.name conditions_name, bp.name publishers_name, bsh.name shelves_name, bsr.name sources_name"
            . " FROM $this->table bk"
            . " INNER JOIN book_categories bct ON bk.categories_id = bct.id"
            . " INNER JOIN book_conditions bcn on bk.conditions_id = bcn.id"
            . " INNER JOIN book_publishers bp on bk.publishers_id = bp.id"
            . " INNER JOIN book_shelves bsh on bk.shelves_id = bsh.id"
            . " INNER JOIN book_sources bsr on bk.sources_id = bsr.id";
        $result = $this->db->query($query);

        return $this->getBooksArray($result);
    }

    public function getNextAutoIncrement()
    {
        return $this->db->getNextAutoIncrement($this->table);
    }

    private function getBooksArray($result)
    {
        $arr = [];
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $book = new Book();
            $book->setId($row['id']);

            $book->setShelf(new DTO($row['shelves_id'], $row['shelves_name']));
            $book->setSource(new DTO($row['sources_id'], $row['sources_name']));
            $book->setCategory(new DTO($row['categories_id'], $row['categories_name']));
            $book->setCondition(new DTO($row['conditions_id'], $row['conditions_name']));
            $book->setPublisher(new DTO($row['publishers_id'], $row['publishers_name']));

            $book->setTotalPages($row['total_pages']);
            $book->setTotalCopies($row['total_copies']);
            $book->setAvailableCopies($row['available_copies']);

            $book->setIsbn($row['isbn']);
            $book->setTitle($row['title']);
            $book->setSubtitle($row['subtitle']);

            $book->setEdition($row['edition']);
            $book->setEditionYear($row['edition_year']);
            $book->setPublicationYear($row['publication_year']);

            $book->setNote($row['note']);
            $book->setPrice($row['price']);
            $book->setCoverPath($row['cover_path']);
            $book->setEbookPath($row['ebook_path']);

            $arr[] = $book;
        }
        return $arr;
    }
}