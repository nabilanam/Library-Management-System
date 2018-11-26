<?php
$page_title = 'Browse Book';
require_once '../functions/Models/DTO.php';
require_once '../functions/Repositories/BooksRepository.php';
require_once '../functions/Repositories/AuthorsBooksRepository.php';
require_once '../templates/navbar.php';

if (isset($_POST['delete_id']) && isAdmin()) {
    $id = $_POST['delete_id'];

    $db = Database::getInstance();
    $db->beginTransaction();

    $repo = new AuthorsBooksRepository();
    $arr = $repo->findAuthors($id);
    foreach ($arr as $authros) {
        $repo->remove($authros[0]->getId(), $id);
    }

    $repo = new BooksRepository();
    if ($repo->remove($id)) {
        $db->commit();

        setAlert("Book with $id deleted successfully", 'success');
        redirectTo(APP_BASE_URL . '/books/browse.php');
    } else {
        $db->rollback();
        echo 'ERROR';
    }
}
alertBox();
?>
    <!-- Form Designing-->

    <div class="panel-body">
        <div class="col-md-10" style="width: 80%">
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <td>ID</td>
                    <td>Title</td>
                    <td>Subtitle</td>
                    <td>ISBN</td>
                    <td>Authors</td>
                    <td>Publication Year</td>
                    <td>Publisher</td>
                    <td>Edition</td>
                    <td>Total Copies</td>
                    <td>Available Copies</td>
                    <td>Price</td>
                    <td>Action</td>
                </tr>
                </thead>
                <tbody>
                <?php
                $repo = new BooksRepository();
                $books = $repo->getAll();
                /* @var Book $book */
                foreach ($books as $book) {
                    $data = '
                                <tr>
                                    <td>' . $book->getId() . '</td>
                                    <td>' . $book->getTitle() . '</td>
                                    <td>' . $book->getSubtitle() . '</td>
                                    <td>' . $book->getIsbn() . '</td>
                                    <td>';
                    $authors_books_repo = new AuthorsBooksRepository();
                    $arr = $authors_books_repo->findAuthors($book->getId());
                    $name = '';
                    foreach ($arr as $res) {
                        $name = $name . $res[0]->getName() . ', ';
                    }
                    $data = $data . substr($name, 0, -2);
                    $data = $data . '</td>
                                    <td>' . $book->getPublicationYear() . '</td>
                                    <td>' . $book->getPublisher()->getName() . '</td>
                                    <td>' . $book->getEdition() . '</td>
                                    <td>' . $book->getTotalCopies() . '</td>
                                    <td>' . $book->getAvailableCopies() . '</td>
                                    <td>' . $book->getPrice() . '</td>
                                    <td>
                                        <a class="btn btn-success"
                                           href="' . APP_BASE_URL . '/books/view.php?book_id=' . $book->getId() . '" role="button" target="_blank">View</a>
                                        ';
                    if (isAdmin()) {
                        $data = $data .
                            '<a class="btn btn-primary" href="' . APP_BASE_URL . '/books/edit.php?book_id=' . $book->getId() . '" role="button" target="_blank">Edit</a>
                            <form action="" method="POST">
                                <a class="btn btn-danger" href="#" role="button" onclick="this.parentNode.submit(); return false;">Delete</a>
                                <input type="hidden" name="delete_id" value="' . $book->getId() . '">
                            </form>';
                    }
                    echo $data . '</td></tr>';
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>


<?php
require_once '../templates/footer.php';
