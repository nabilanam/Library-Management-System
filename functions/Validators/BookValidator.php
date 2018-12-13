<?php
require_once 'Validator.php';
require_once '../Models/DTO.php';
require_once '../Models/Book.php';
require_once '../Repositories/SimpleRepositoryFacade.php';
require_once '../Repositories/BooksRepository.php';
require_once '../Repositories/AuthorsBooksRepository.php';


$conditions = [
    'edition_year' => ['type' => 'int', 'min' => 3, 'max' => 4, 'optional' => false],
    'publish_year' => ['type' => 'int', 'min' => 3, 'max' => 4, 'optional' => false],
    'pages' => ['type' => 'int', 'min' => 1, 'max' => 4, 'optional' => false],
    'copies' => ['type' => 'int', 'min' => 1, 'max' => 5, 'optional' => false],
    'title' => ['type' => 'text', 'min' => 3, 'max' => 50, 'optional' => false],
    'edition' => ['type' => 'text', 'min' => 1, 'max' => 50, 'optional' => false],
    'shelf' => ['type' => 'text', 'min' => 1, 'max' => 50, 'optional' => false],
    'source' => ['type' => 'text', 'min' => 3, 'max' => 50, 'optional' => false],
    'condition' => ['type' => 'text', 'min' => 3, 'max' => 50, 'optional' => false],
    'category' => ['type' => 'text', 'min' => 3, 'max' => 50, 'optional' => false],
    'publisher' => ['type' => 'text', 'min' => 1, 'max' => 50, 'optional' => false],
    'isbn' => ['type' => 'text', 'min' => 10, 'max' => 20, 'optional' => true],
    'subtitle' => ['type' => 'text', 'min' => 3, 'max' => 100, 'optional' => true],
    'note' => ['type' => 'text', 'min' => 3, 'max' => 200, 'optional' => true],
    'price' => ['type' => 'currency', 'min' => 1, 'max' => 10, 'optional' => false],
    'authors' => ['type' => 'text_arr', 'min' => 3, 'max' => 50, 'optional' => false]
];

if (isset($_POST['save_book']) && isAdmin()) {
    $db = Database::getInstance();
    $book_repo = new BooksRepository();
    $db->beginTransaction();

    $book = new Book();
    try {
        /////// simple ///////

        if (isPostValid('isbn')) {
            $book->setIsbn($_POST['isbn']);
        }
        if (isPostValid('subtitle')) {
            $book->setSubtitle($_POST['subtitle']);
        }
        if (isPostValid('title')) {
            $book->setTitle($_POST['title']);
        }
        if (isPostValid('edition')) {
            $book->setEdition($_POST['edition']);
        }
        if (isPostValid('edition_year')) {
            $book->setEditionYear((int)$_POST['edition_year']);
        }
        if (isPostValid('publish_year')) {
            $book->setPublicationYear((int)$_POST['publish_year']);
        }
        if (isPostValid('pages')) {
            $book->setTotalPages((int)$_POST['pages']);
        }
        if (isPostValid('copies')) {
            $book->setTotalCopies((int)$_POST['copies']);
            $book->setAvailableCopies((int)$_POST['copies']);
        }
        if (isPostValid('price')) {
            $book->setPrice($_POST['price']);
        }
        if (isPostValid('note')) {
            $book->setNote($_POST['note']);
        }

        /////// dto ///////

        if (isPostValid('shelf')) {
            $authors_repo = SimpleRepositoryFacade::getShelvesRepository();
            if ($dto = $authors_repo->findByName($_POST['shelf'])) {
                $book->setShelf($dto);
            }
        }
        if (isPostValid('source')) {
            $authors_repo = SimpleRepositoryFacade::getSourcesRepository();
            if ($dto = $authors_repo->findByName($_POST['source'])) {
                $book->setSource($dto);
            }
        }
        if (isPostValid('condition')) {
            $authors_repo = SimpleRepositoryFacade::getConditionsRepository();
            if ($dto = $authors_repo->findByName($_POST['condition'])) {
                $book->setCondition($dto);
            }
        }
        if (isPostValid('category')) {
            $authors_repo = SimpleRepositoryFacade::getCategoriesRepository();
            if ($dto = $authors_repo->findByName($_POST['category'])) {
                $book->setCategory($dto);
            }
        }
        if (isPostValid('publisher')) {
            $authors_repo = SimpleRepositoryFacade::getPublishersRepository();
            if ($dto = $authors_repo->findOrInsert($_POST['publisher'])) {
                $book->setPublisher($dto);
            }
        }

        /////// file ///////

        $cover = $_FILES['cover_photo'];
        if ($cover['error'] == UPLOAD_ERR_OK) {
            if ($cover_path = uploadImage($cover)) {
                $book->setCoverPath($cover_path);
            }
        }

        $ebook = $_FILES['eBook'];
        if ($ebook['error'] == UPLOAD_ERR_OK) {
            if ($ebook_path = uploadEbook($ebook)) {
                $book->setEbookPath($ebook_path);
            }
        }
        /////// save book //////
        $authors_repo = SimpleRepositoryFacade::getAuthorsRepository();
        $authors_books_repo = new AuthorsBooksRepository();

        if (isset($_POST['id'])) {
            $book->setId($_POST['id']);
            if ($book = $book_repo->update($book)) {
                if (isPostValid('authors')) {
                    $authors = $_POST['authors'];

                    foreach ($authors as $name) {
                        if ($dto = $authors_repo->findOrInsert($name)) {
                            if (!$authors_books_repo->isJoinExist($dto->getId(), $book->getId())) {
                                $authors_books_repo->join($dto->getId(), $book->getId());
                            }
                        }
                    }

                    $db->commit();

                    /////// Redirect ///////
                    setAlert('Book updated successfully!', 'success');
                    redirectTo(APP_URL_BASE . '/books/view.php?book_id=' . $book->getId());
                }
            }
            /////// Redirect ///////
            setAlert('Nothing to update!', 'warning');
            redirectTo(APP_URL_BASE . '/books/edit.php?book_id=' . $_POST['id']);
        } else if ($book = $book_repo->add($book)) {
            if (isPostValid('authors')) {
                $authors = $_POST['authors'];

                foreach ($authors as $name) {
                    if ($dto = $authors_repo->findOrInsert($name)) {
                        $authors_books_repo->join($dto->getId(), $book->getId());
                    }
                }

                $db->commit();

                /////// Redirect ///////
                setAlert('Book added successfully!', 'success');
                redirectTo(APP_URL_BASE . '/books/add.php');
            }
        } else {
            $db->rollback();
        }
    } catch (PDOException $e) {
        $db->rollback();
    }

    /////// Redirect ///////
    setAlert('Book couldn\'t be added!', 'danger');
    redirectTo(APP_URL_BASE . '/books/add.php');
}

/**
 * @param string $key
 * @return bool
 */
function isPostValid($key)
{
    global $conditions;

    if (isset($_POST[$key])) {
        $condition = $conditions[$key];
        $min = $condition['min'];
        $max = $condition['max'];
        $type = $condition['type'];
        $is_optional = $condition['optional'];
        switch ($type) {
            case 'int':
                $data = $_POST[$key];
                if (isPositiveInteger($data)
                    && isStringValidLength($data, $min, $max, $is_optional)) {
                    return true;
                }
                return false;
            case 'text':
                $data = $_POST[$key];
                if (isStringValidLength($data, $min, $max, $is_optional)) {
                    return true;
                }
                return false;
            case 'currency':
                $data = $_POST[$key];
                if (isValidCurrency($data)
                    && isStringValidLength($data, $min, $max, $is_optional)) {
                    return true;
                }
                return false;
            case 'text_arr':
                $data = $_POST[$key];
                if (is_array($data)) {
                    if (count($data) > 0) {
                        foreach ($data as $value) {
                            if (!isStringValidLength($value, $min, $max)) {
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
