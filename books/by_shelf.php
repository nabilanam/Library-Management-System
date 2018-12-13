<?php
$page_title = 'Books by Category';
require_once '../functions/Repositories/BooksRepository.php';
require_once '../templates/navbar.php';
require_once '../templates/book_table.php';

/* @var Book $book */

if (isset($_GET['shelf'])) {
    $id = $_GET['shelf'];
} else {
    $id = 1;
}

alertBox();
?>

    <div class="row">
        <form class="two wide column">
            <select id="shelf" name="shelf" class="ui search fluid dropdown" onchange="this.form.submit()">
                <?php
                $repo = SimpleRepositoryFacade::getShelvesRepository();
                $arr = $repo->getAll();
                foreach ($arr as $dto) {
                    if (!empty($id) && $dto->getId() == $id) {
                        echo '<option selected value="' . $dto->getId() . '">' . $dto->getName() . '</option>';
                    } elseif ($dto->getId() == 1) {
                        echo '<option selected value="' . $dto->getId() . '">' . $dto->getName() . '</option>';
                    } else {
                        echo '<option value="' . $dto->getId() . '">' . $dto->getName() . '</option>';
                    }
                }
                ?>
            </select>
        </form>
    </div>

<?php

$repo = new BooksRepository();

$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$results_per_page = 4;
$number_of_rows = $repo->totalRecordsByShelf($id);
$total_pages = ceil($number_of_rows / $results_per_page);
$first_result = ($current_page - 1) * $results_per_page;

$arr = $repo->findByShelfPaginated($id,$first_result,$results_per_page);

printBookTable($arr);

printPagination($current_page,$total_pages,APP_URL_BASE.'/books/by_shelf.php?shelf='.$id.'&');

require_once '../templates/footer.php';