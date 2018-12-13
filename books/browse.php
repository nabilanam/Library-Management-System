<?php
$page_title = 'Browse Books';
require_once '../functions/Models/DTO.php';
require_once '../functions/Repositories/BooksRepository.php';
require_once '../functions/Repositories/AuthorsBooksRepository.php';
require_once '../templates/navbar.php';
require_once '../templates/book_table.php';

if (isset($_POST['delete_id']) && isAdmin()) {
    $id = $_POST['delete_id'];

    $db = Database::getInstance();
    $db->beginTransaction();

    $repo = new AuthorsBooksRepository();
    $arr = $repo->findFirst($id);
    foreach ($arr as $author) {
        $repo->remove($author->getId(), $id);
    }

    $repo = new BooksRepository();
    if ($repo->removeById($id)) {
        $db->commit();

        setAlert("Book with $id deleted successfully", 'success');
        redirectTo(APP_URL_BASE . '/books/browse.php');
    } else {
        $db->rollback();
        echo 'ERROR';
    }
}
alertBox();

$repo = new BooksRepository();

$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$results_per_page = 5;
$number_of_rows = $repo->totalRecords();
$total_pages = ceil($number_of_rows / $results_per_page);
$first_result = ($current_page - 1) * $results_per_page;


$books = $repo->getPaginated($first_result, $results_per_page);

printBookTable($books);

printPagination($current_page,$total_pages,APP_URL_BASE.'/books/browse.php?');
?>

<?php
require_once '../templates/footer.php';
