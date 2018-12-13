<?php

$page_title = 'Book View';
require_once '../templates/navbar.php';
require_once '../functions/Repositories/BooksRepository.php';
require_once '../functions/Repositories/AuthorsBooksRepository.php';

/* @var Book $book */
if (isset($_GET['book_id'])) {
    $id = $_GET['book_id'];
    $repo = new BooksRepository();
    if (!($book = $repo->findById($id))) {
        die('Book doesn\'t exist!');
    }
} else {
    redirectTo(APP_URL_BASE . '/books/browse.php');
}
alertBox();
?>
    <div class="row">
        <div class="column three wide">
            <img class="ui small rounded centered left floated image"
                 src="<?php echo APP_URL_COVERS . '/' . $book->getCoverPath(); ?>">
        </div>
        <div class="column nine wide">
            <div class="ui placeholder segment">
            <table class="ui padded selectable celled center aligned striped table">
                <tbody>
                <tr>
                    <th class="three wide">ID :</th>
                    <td class="six wide"><?php echo $book->getId(); ?></td>
                </tr>
                <tr>
                    <th>ISBN :</th>
                    <td><?php echo $book->getIsbn(); ?></td>
                </tr>
                <tr>
                    <th>Title :</th>
                    <td><?php echo $book->getTitle(); ?></td>
                </tr>
                <tr>
                    <th>Subtitle :</th>
                    <td><?php echo $book->getSubtitle(); ?></td>
                </tr>
                <tr>
                    <th>Authors :</th>
                    <td>
                        <?php
                        $repo = new AuthorsBooksRepository();
                        $arr = $repo->findFirst($book->getId());
                        $name = '';
                        /* @var DTO $author */
                        foreach ($arr as $author) {
                            $name = $name . $author->getName() . ", ";
                        }
                        echo substr($name, 0, -2);
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Category :</th>
                    <td><?php echo $book->getCategory()->getName(); ?></td>
                </tr>
                <tr>
                    <th>Publisher :</th>
                    <td><?php echo $book->getPublisher()->getName(); ?></td>
                </tr>
                <tr>
                    <th>Publication Year :</th>
                    <td><?php echo $book->getPublicationYear(); ?></td>
                </tr>
                <tr>
                    <th>Edition :</th>
                    <td><?php echo $book->getEdition(); ?></td>
                </tr>
                <tr>
                    <th>Edition Year :</th>
                    <td><?php echo $book->getEditionYear(); ?></td>
                </tr>
                <tr>
                    <th>Total Pages :</th>
                    <td><?php echo $book->getTotalPages(); ?></td>
                </tr>
                <tr>
                    <th>Total Copies :</th>
                    <td><?php echo $book->getTotalCopies(); ?></td>
                </tr>
                <tr>
                    <th>Available Copies :</th>
                    <td><?php echo $book->getAvailableCopies(); ?></td>
                </tr>
                <tr>
                    <th>Condition :</th>
                    <td><?php echo $book->getCondition()->getName(); ?></td>
                </tr>
                <tr>
                    <th>Ebook :</th>
                    <td><a
                                href="<?php echo $book->getEbookPath() ? APP_URL_EBOOKS . '/' . $book->getEbookPath() : '#'; ?>">Click
                            here</a></td>
                </tr>
                <tr>
                    <th>Shelf :</th>
                    <td><?php echo $book->getShelf()->getName(); ?></td>
                </tr>
                <tr>
                    <th>Source :</th>
                    <td><?php echo $book->getSource()->getName(); ?></td>
                </tr>
                <tr>
                    <th>Note :</th>
                    <td><?php echo $book->getNote(); ?></td>
                </tr>
                <tr>
                    <th>Price :</th>
                    <td><?php echo 'BDT ' . $book->getPrice() . '/='; ?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    </div>

<?php
require_once '../templates/footer.php';