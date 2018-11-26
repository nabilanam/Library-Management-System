<?php

$page_title = 'Book View';
require_once '../templates/navbar.php';
require_once '../functions/Repositories/BooksRepository.php';
require_once '../functions/Repositories/AuthorsBooksRepository.php';

/* @var Book $book */
if (isset($_GET['book_id'])) {
    $id = $_GET['book_id'];
    $repo = new BooksRepository();
    $arr = $repo->findById($id);
    if (count($arr) == 1){
        $book = $arr[0];
    }else{
        die('Book doesn\'t exist!');
    }
} else {
    redirectTo(APP_BASE_URL . '/books/browse.php');
}

?>

    <div class="ui container text-center" style="">
        <div>
            <img width="100px" height="100px" class="img-thumbnail"
                 src="<?php echo APP_COVER_URL .'/'. $book->getCoverPath(); ?>">
        </div>
        <table class="table table-bordered" style="width: 60%;">
            <tbody>
            <tr>
                <th scope="row" style="text-align: center">ID :</th>
                <td colspan="2" style="text-align: center"><?php echo $book->getId(); ?></td>
            </tr>
            <tr>
                <th scope="row" style="text-align: center">ISBN :</th>
                <td colspan="2" style="text-align: center"><?php echo $book->getIsbn(); ?></td>
            </tr>
            <tr>
                <th scope="row" style="text-align: center">Title :</th>
                <td colspan="2" style="text-align: center"><?php echo $book->getTitle(); ?></td>
            </tr>
            <tr>
                <th scope="row" style="text-align: center">Subtitle :</th>
                <td colspan="2" style="text-align: center"><?php echo $book->getSubtitle(); ?></td>
            </tr>
            <tr>
                <th scope="row" style="text-align: center">Authors :</th>
                <td colspan="2" style="text-align: center">
                    <?php
                    $repo = new AuthorsBooksRepository();
                    $arr = $repo->findAuthors($book->getId());
                    $name = '';
                    /* @var DTO $author */
                    foreach ($arr as $authors) {
                        $name = $name . $authors[0]->getName() . ", ";
                    }
                    echo substr($name, 0, -2);
                    ?>
                </td>
            </tr>
            <tr>
                <th scope="row" style="text-align: center">Category :</th>
                <td colspan="2" style="text-align: center"><?php echo $book->getCategory()->getName(); ?></td>
            </tr>
            <tr>
                <th scope="row" style="text-align: center">Publisher :</th>
                <td colspan="2" style="text-align: center"><?php echo $book->getPublisher()->getName(); ?></td>
            </tr>
            <tr>
                <th scope="row" style="text-align: center">Publication Year :</th>
                <td colspan="2" style="text-align: center"><?php echo $book->getPublicationYear(); ?></td>
            </tr>
            <tr>
                <th scope="row" style="text-align: center">Edition :</th>
                <td colspan="2" style="text-align: center"><?php echo $book->getEdition(); ?></td>
            </tr>
            <tr>
                <th scope="row" style="text-align: center">Edition Year :</th>
                <td colspan="2" style="text-align: center"><?php echo $book->getEditionYear(); ?></td>
            </tr>
            <tr>
                <th scope="row" style="text-align: center">Total Pages :</th>
                <td colspan="2" style="text-align: center"><?php echo $book->getTotalPages(); ?></td>
            </tr>
            <tr>
                <th scope="row" style="text-align: center">Total Copies :</th>
                <td colspan="2" style="text-align: center"><?php echo $book->getTotalCopies(); ?></td>
            </tr>
            <tr>
                <th scope="row" style="text-align: center">Available Copies :</th>
                <td colspan="2" style="text-align: center"><?php echo $book->getAvailableCopies(); ?></td>
            </tr>
            <tr>
                <th scope="row" style="text-align: center">Condition :</th>
                <td colspan="2" style="text-align: center"><?php echo $book->getCondition()->getName(); ?></td>
            </tr>
            <tr>
                <th scope="row" style="text-align: center">eBook :</th>
                <td colspan="1" style="text-align: center"><a
                            href="<?php echo $book->getEbookPath() ? APP_EBOOK_URL .'/' . $book->getEbookPath() : '#'; ?>">Click
                        here</a></td>
            </tr>
            <tr>
                <th scope="row" style="text-align: center">Shelf :</th>
                <td colspan="2" style="text-align: center"><?php echo $book->getShelf()->getName(); ?></td>
            </tr>
            <tr>
                <th scope="row" style="text-align: center">Source :</th>
                <td colspan="2" style="text-align: center"><?php echo $book->getSource()->getName(); ?></td>
            </tr>
            <tr>
                <th scope="row" style="text-align: center">Note :</th>
                <td colspan="2" style="text-align: center"><?php echo $book->getNote(); ?></td>
            </tr>
            <tr>
                <th scope="row" style="text-align: center">Price :</th>
                <td colspan="2" style="text-align: center"><?php echo 'BDT ' . $book->getPrice() . '/='; ?></td>
            </tr>
            </tbody>
        </table>
    </div>


<?php
require_once '../templates/footer.php';