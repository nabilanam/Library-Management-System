<?php
$page_title = 'Search Books';
require_once '../templates/navbar.php';
require_once '../templates/book_table.php';
require_once '../functions/Repositories/BooksRepository.php';
require_once '../functions/Repositories/AuthorsBooksRepository.php';

$book_repo = new BooksRepository();
$author_book_repo = new AuthorsBooksRepository();


$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$results_per_page = 5;


if (isset($_GET['query']) && isset($_GET['option'])) {
    $query = trim($_GET['query']);
    $option = $_GET['option'];
    if ($option == 1) {
        $number_of_rows = $book_repo->totalTitleSearchRecords($query);
        $total_pages = ceil($number_of_rows / $results_per_page);
        $first_result = ($current_page - 1) * $results_per_page;
        $arr = $book_repo->getPaginatedTitleSearch($query, $first_result, $results_per_page);
    } elseif ($option == 3) {
        $number_of_rows = $book_repo->totalPublisherSearchRecords($query);
        $total_pages = ceil($number_of_rows / $results_per_page);
        $first_result = ($current_page - 1) * $results_per_page;
        $arr = $book_repo->getPaginatedPublisherSearch($query, $first_result, $results_per_page);
    } elseif ($option == 2) {
        $number_of_rows = $author_book_repo->totalAuthorSearchRecords($query);
        $total_pages = ceil($number_of_rows / $results_per_page);
        $first_result = ($current_page - 1) * $results_per_page;
        $arr = $author_book_repo->getPaginatedBooks($query, $first_result, $results_per_page);
    }
}
?>

    <div class="row">
        <?php alertBox() ?>
    </div>


    <div class="ui placeholder segment">
        <form class="ui form" method="GET" action="search.php">
            <div class="inline fields">
                <div class="field">
                    <label for="query">Search by</label>
                    <select name="option" id="option">
                        <option value="1">Title</option>
                        <option value="2">Author</option>
                        <option value="3">Publisher</option>
                    </select>
                </div>
                <div class="field">
                    <input type="text" name="query" id="query">
                </div>
                <div class="field">
                    <button type="submit" class="ui blue button">Search</button>
                </div>
            </div>
        </form>
    </div>

<?php
if (!empty($arr)) {
    printBookTable($arr);
    printPagination($current_page, $total_pages, APP_URL_BASE . '/books/search.php?option=' . $option . '&query=' . $query . '&');
}
require_once '../templates/footer.php';
