<?php
$page_title = 'Issue';
require_once '../templates/navbar.php';
require_once '../functions/Utilities/Database.php';
require_once '../functions/Repositories/BooksRepository.php';
require_once '../functions/Repositories/UsersRepository.php';
require_once '../functions/Repositories/RequestsRepository.php';

if (isset($_GET['book_id'])) {
    $book_repo = new BooksRepository();
    $book = $book_repo->findById($_GET['book_id']);

    if (!$book) {
        setAlert('No book found with id ' . $_GET['book_id'] . ' !', 'danger');
        redirectTo(APP_URL_BASE . '/issue/');
    }
}

alertBox();
?>

    <div class="row">
        <div class="column five wide">
            <div class="ui placeholder segment">
                <form class="ui form" action="../functions/Validators/RequestValidator.php" method="post">
                    <div class="field">
                        <label for="user_id">User ID :</label>
                        <input type="text" id="user_id" name="user_id" style="text-align: center">
                    </div>
                    <div class="field">
                        <label for="book_id">Book ID :</label>
                        <input type="text" id="book_id" name="book_id"
                               value="<?php echo isset($_GET['book_id']) ? $_GET['book_id'] : '' ?>"
                               style="text-align: center">
                    </div>
                    <div class="field">
                        <label for="issue_date">Issue Date :</label>
                        <input type="date" id="issue_date" name="issue_date" style="text-align: center">
                    </div>
                    <div class="field">
                        <label for="return_date">Return Date :</label>
                        <input type="date" id="return_date" name="return_date" style="text-align: center">
                    </div>
                    <div class="field">
                        <input type="hidden" name="status_id" value="<?php echo Status::APPROVED ?>">
                        <input type="hidden" name="request_date" value="<?php getToday() ?>">
                        <button class="ui blue button" type="submit" name="approve">Issue</button>
                    </div>
                </form>
            </div>
        </div>
        <?php if (isset($book))
            echo '<div class="column three wide">
                <img class="ui small rounded centered left floated image"
                     src="' . APP_URL_COVERS . '/' . $book->getCoverPath() . '">
                </div>
                ';
        ?>
    </div>


<?php
require_once '../templates/footer.php';