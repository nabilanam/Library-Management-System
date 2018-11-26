<?php
$page_title = 'Books by Category';
require_once '../templates/navbar.php';
require_once '../functions/Repositories/BooksRepository.php';

/* @var Book $book */

if (isset($_GET['shelf'])) {
    $id = $_GET['shelf'];
} else {
    $id = 1;
}
$repo = new BooksRepository();
$books = $repo->findByShelf($id);

alertBox();
?>

    <div class="ui column stackable center page grid">
        <div class="four wide column"></div><!-- empty div just padding -->
        <form class="ui six wide column form">
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
if (!empty($books)) {
    $data = '<div class="panel-body">
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
                        <tbody>';
    /* @var Book $book */
    foreach ($books as $book) {
        $data = $data . '<tr>
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
            $data = $data . '<a class="btn btn-primary" href="' . APP_BASE_URL . '/books/edit.php?book_id=' . $book->getId() . '" role="button" target="_blank">Edit</a>
                               <form action="" method="POST">
                                   <a class="btn btn-danger" href="#" role="button" onclick="this.parentNode.submit(); return false;">Delete</a>
                                   <input type="hidden" name="delete_id" value="' . $book->getId() . '">
                               </form>';
        }
        $data = $data . '</td></tr>';
    }
    echo $data . '</tbody></table></div>';
}
?>

<?php
require_once '../templates/footer.php';