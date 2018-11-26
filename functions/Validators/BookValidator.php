<?php
require_once '../Models/DTO.php';
require_once '../Models/Book.php';
require_once '../Repositories/SimpleRepositoryFacade.php';
require_once '../Repositories/BooksRepository.php';
require_once '../Repositories/AuthorsBooksRepository.php';

if (isset($_POST['save_book']) && getUser()['access'] == 'admin') {
    $book = new Book();
    $db = Database::getInstance();
    $validator = new BookValidator();
    $book_repo = new BooksRepository();
    $db->beginTransaction();

    try {
        /////// simple ///////

        if ($validator->isPostValid('isbn')) {
            $book->setIsbn($_POST['isbn']);
        }
        if ($validator->isPostValid('subtitle')) {
            $book->setSubtitle($_POST['subtitle']);
        }
        if ($validator->isPostValid('title')) {
            $book->setTitle($_POST['title']);
        }
        if ($validator->isPostValid('edition')) {
            $book->setEdition($_POST['edition']);
        }
        if ($validator->isPostValid('edition_year')) {
            $book->setEditionYear((int)$_POST['edition_year']);
        }
        if ($validator->isPostValid('publish_year')) {
            $book->setPublicationYear((int)$_POST['publish_year']);
        }
        if ($validator->isPostValid('pages')) {
            $book->setTotalPages((int)$_POST['pages']);
        }
        if ($validator->isPostValid('copies')) {
            $book->setTotalCopies((int)$_POST['copies']);
            $book->setAvailableCopies((int)$_POST['copies']);
        }
        if ($validator->isPostValid('price')) {
            $book->setPrice($_POST['price']);
        }
        if ($validator->isPostValid('note')) {
            $book->setNote($_POST['note']);
        }

        /////// dto ///////

        if ($validator->isPostValid('shelf')) {
            $authors_repo = SimpleRepositoryFacade::getShelvesRepository();
            if ($dtos = $authors_repo->find(new DTO(null, $_POST['shelf']))) {
                $book->setShelf($dtos[0]);
            }
        }
        if ($validator->isPostValid('source')) {
            $authors_repo = SimpleRepositoryFacade::getSourcesRepository();
            if ($dtos = $authors_repo->find(new DTO(null, $_POST['source']))) {
                $book->setSource($dtos[0]);
            }
        }
        if ($validator->isPostValid('condition')) {
            $authors_repo = SimpleRepositoryFacade::getConditionsRepository();
            if ($dtos = $authors_repo->find(new DTO(null, $_POST['condition']))) {
                $book->setCondition($dtos[0]);
            }
        }
        if ($validator->isPostValid('category')) {
            $authors_repo = SimpleRepositoryFacade::getCategoriesRepository();
            if ($dtos = $authors_repo->find(new DTO(null, $_POST['category']))) {
                $book->setCategory($dtos[0]);
            }
        }
        if ($validator->isPostValid('publisher')) {
            $authors_repo = SimpleRepositoryFacade::getPublishersRepository();
            if ($dto = $authors_repo->findOrInsert(new DTO(null, $_POST['publisher']))) {
                $book->setPublisher($dto);
            }
        }

        /////// file ///////

        $cover = $_FILES['cover_photo'];
        if ($cover['error'] == UPLOAD_ERR_OK) {
            if ($cover_path = $validator->uploadImage($cover)) {
                $book->setCoverPath($cover_path);
            }
        }

        $ebook = $_FILES['eBook'];
        if ($ebook['error'] == UPLOAD_ERR_OK) {
            if ($ebook_path = $validator->uploadEbook($ebook)) {
                $book->setEbookPath($ebook_path);
            }
        }
        /////// save book //////
        $authors_repo = SimpleRepositoryFacade::getAuthorsRepository();
        $authors_books_repo = new AuthorsBooksRepository();

        if (isset($_POST['id'])) {
            $book->setId($_POST['id']);
            if ($book = $book_repo->update($book)) {
                if ($validator->isPostValid('authors')) {
                    $authors = $_POST['authors'];

                    foreach ($authors as $name) {
                        if ($dto = $authors_repo->findOrInsert(new DTO(null, $name))) {
                            if (!$authors_books_repo->isJoinExist($dto->getId(),$book->getId())){
                                $authors_books_repo->join($dto->getId(), $book->getId());
                            }
                        }
                    }

                    $db->commit();

                    /////// Redirect ///////
                    setAlert('Book updated successfully!','success');
                    redirectTo(APP_BASE_URL . '/books/view.php?book_id='.$book->getId());
                }
            }
        } else if ($book = $book_repo->add($book)) {
            if ($validator->isPostValid('authors')) {
                $authors = $_POST['authors'];

                foreach ($authors as $name) {
                    if ($dto = $authors_repo->findOrInsert(new DTO(null, $name))) {
                        $authors_books_repo->join($dto->getId(), $book->getId());
                    }
                }

                $db->commit();

                /////// Redirect ///////
                setAlert('Book added successfully!','success');
                redirectTo(APP_BASE_URL . '/books/add.php');
            }
        } else{
            $db->rollback();
        }
    } catch (PDOException $e) {
        $db->rollback();
        echo $e;
    }

    /////// Redirect ///////
    setAlert('Book couldn\'t be added!','danger');
    redirectTo(APP_BASE_URL . '/books/add.php');
}


class BookValidator
{
    private $book_repo;
    private $conditions;
    private $upload_dir;

    function __construct()
    {
        $this->conditions = [
            'edition_year' => array('type' => 'int', 'min' => 3, 'max' => 4, 'optional' => false),
            'publish_year' => array('type' => 'int', 'min' => 3, 'max' => 4, 'optional' => false),
            'pages' => array('type' => 'int', 'min' => 1, 'max' => 4, 'optional' => false),
            'copies' => array('type' => 'int', 'min' => 1, 'max' => 5, 'optional' => false),
            'title' => array('type' => 'text', 'min' => 3, 'max' => 50, 'optional' => false),
            'edition' => array('type' => 'text', 'min' => 1, 'max' => 50, 'optional' => false),
            'shelf' => array('type' => 'text', 'min' => 1, 'max' => 50, 'optional' => false),
            'source' => array('type' => 'text', 'min' => 3, 'max' => 50, 'optional' => false),
            'condition' => array('type' => 'text', 'min' => 3, 'max' => 50, 'optional' => false),
            'category' => array('type' => 'text', 'min' => 3, 'max' => 50, 'optional' => false),
            'publisher' => array('type' => 'text', 'min' => 1, 'max' => 50, 'optional' => false),
            'isbn' => array('type' => 'text', 'min' => 10, 'max' => 20, 'optional' => true),
            'subtitle' => array('type' => 'text', 'min' => 3, 'max' => 100, 'optional' => true),
            'note' => array('type' => 'text', 'min' => 3, 'max' => 200, 'optional' => true),
            'price' => array('type' => 'currency', 'min' => 1, 'max' => 10, 'optional' => false),
            'authors' => array('type' => 'text_arr', 'min' => 3, 'max' => 50, 'optional' => false)
        ];
        $this->book_repo = new BooksRepository();
    }

    /**
     * @param string $key
     * @return bool
     */
    function isPostValid($key)
    {
        if (isset($_POST[$key])) {
            $condition = $this->conditions[$key];
            $min = $condition['min'];
            $max = $condition['max'];
            $type = $condition['type'];
            $is_optional = $condition['optional'];
            switch ($type) {
                case 'int':
                    $data = $_POST[$key];
                    if ($this->isPositiveInteger($data)
                        && $this->isStringValidLength($data, $min, $max, $is_optional)) {
                        return true;
                    }
                    return false;
                case 'text':
                    $data = $_POST[$key];
                    if ($this->isStringValidLength($data, $min, $max, $is_optional)) {
                        return true;
                    }
                    return false;
                case 'currency':
                    $data = $_POST[$key];
                    if ($this->isValidCurrency($data)
                        && $this->isStringValidLength($data, $min, $max, $is_optional)) {
                        return true;
                    }
                    return false;
                case 'text_arr':
                    $data = $_POST[$key];
                    if (is_array($data)) {
                        if (count($data) > 0) {
                            foreach ($data as $value) {
                                if (!$this->isStringValidLength($value, $min, $max)) {
                                    return false;
                                }
                            }
                            return true;
                        }
                    }
                    return false;
            }
        }
        return false;
    }

    /**
     * @param string $str
     * @param int $min
     * @param int $max
     * @param bool $is_optional
     * @return bool
     */
    function isStringValidLength($str, $min, $max, $is_optional = false)
    {
        $len = strlen($str);
        if ($is_optional) {
            return ($len == 0 || ($len >= $min && $len <= $max));
        }
        return ($len >= $min && $len <= $max);
    }

    /**
     * @param $number
     * @return bool
     */
    function isPositiveInteger($number)
    {
        if (is_numeric($number)) {
            if (is_string($number) && strpos($number, '.') === true) {
                return false;
            }
            $number = (int)$number;
            if ($number > 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param string $number
     * @return bool|int
     */
    function isValidCurrency($number)
    {
        return preg_match("/^[\d+]{1,5}([\.]{1,1}[\d+]{1,2})?$/", $number);
    }


    public function uploadImage($file)
    {
        if (is_uploaded_file($file['tmp_name']) || file_exists($file['tmp_name'])) {
            $extensions = array(
                'jpg' => 'image/jpeg',
                'png' => 'image/png'
            );
            return $this->upload($file, $extensions, 512000, APP_UPLOAD_DIR_COVERS);
        }
        return false;
    }

    public function uploadEbook($file)
    {
        if (is_uploaded_file($file['tmp_name']) || file_exists($file['tmp_name'])) {
            $eBook_dir = UPLOAD_DIR . "/ebooks";
            $extensions = array(
                'pdf' => 'application/pdf',
                'epub' => 'application/epub+zip',
                'chm' => 'application/vnd.ms-htmlhelp',
                'djvu' => 'image/vnd.djvu',
                'mobi' => 'application/x-mobipocket-ebook'
            );
            return $this->upload($file, $extensions, 30000000, APP_UPLOAD_DIR_EBOOKS);
        }
        return false;
    }

    public function upload($file, $extensions, $max_size, $directory)
    {
        if (!file_exists($directory)) {
            mkdir($directory);
        }
        if (isset($file['error']) && !is_array($file['error']) && $file['error'] == UPLOAD_ERR_OK) {
            if ($file['size'] < $max_size) {
                $file_info = new finfo(FILEINFO_MIME_TYPE);
                if (false !== $ext = array_search($file_info->file($file['tmp_name']), $extensions, true)) {
                    $sha1 = sha1_file($file['tmp_name']);
                    $destination_path = sprintf($directory . DIRECTORY_SEPARATOR . "%s.%s", $sha1, $ext);
                    if (move_uploaded_file($file['tmp_name'], $destination_path)) {
                        return $sha1.'.'.$ext;
                    }
                }
            }
        }
        return false;
    }
}