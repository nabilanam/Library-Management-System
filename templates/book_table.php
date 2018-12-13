<?php
require_once __DIR__.'/../functions/Enums/Status.php';


/**
 * @param Book[] $arr
 */
function printBookTable($arr)
{
    $data = '<table id="table" class="ui selectable celled table">
    <thead>
    <tr>
        <th class="one wide">ID</th>
        <th class="one wide">Title</th>
        <th class="one wide">Subtitle</th>
        <th class="one wide">ISBN</th>
        <th class="one wide">Authors</th>
        <th class="one wide">Publication Year</th>
        <th class="one wide">Publisher</th>
        <th class="one wide">Edition</th>
        <th class="one wide">Total Copies</th>
        <th class="one wide">Available Copies</th>
        <th class="one wide">Price</th>
        <th class="one wide">Action</th>
    </tr>
    </thead>
    <tbody>';
    foreach ($arr as $book) {
        $data = $data . '<tr>
                             <td>' . $book->getId() . '</td>
                             <td>' . $book->getTitle() . '</td>
                             <td>' . $book->getSubtitle() . '</td>
                             <td>' . $book->getIsbn() . '</td>
                             <td>';
        $authors_books_repo = new AuthorsBooksRepository();
        $authors = $authors_books_repo->findFirst($book->getId());
        $name = '';
        foreach ($authors as $author) {
            $name = $name . $author->getName() . ', ';
        }
        $data = $data . substr($name, 0, -2);
        $data = $data . '</td>
                              <td>' . $book->getPublicationYear() . '</td>
                              <td>' . $book->getPublisher()->getName() . '</td>
                              <td>' . $book->getEdition() . '</td>
                              <td>' . $book->getTotalCopies() . '</td>
                              <td>' . $book->getAvailableCopies() . '</td>
                              <td>' . $book->getPrice() . '</td>
                              <td><div class="ui mini basic vertical buttons">
                                      ';
        if (isAdmin()) {
            $data = $data
                . '<a class="ui blue basic button" href="' . APP_URL_BASE . '/issue/index.php?book_id=' . $book->getId() . '">Issue</a>
                   <a class="ui green basic button" href="' . APP_URL_BASE . '/books/view.php?book_id=' . $book->getId() . '">View</i></a>
                   <a class="ui blue basic button" href="' . APP_URL_BASE . '/books/edit.php?book_id=' . $book->getId() . '">Edit</a>
                   <form action="" method="POST">
                       <a class="ui red basic button" href="#" onclick="this.parentNode.submit(); return false;">Delete</a>
                       <input type="hidden" name="delete_id" value="' . $book->getId() . '">
                   </form>';
        } else {
            $data = $data
                . '<a class="ui green basic button" href="' . APP_URL_BASE . '/books/view.php?book_id=' . $book->getId() . '">View</i></a>
                   <form action="../functions/Validators/RequestValidator.php" method="POST">
                       <input type="hidden" name="book_id" value="' . $book->getId() . '">
                       <input type="hidden" name="user_id" value="' . getUser()['id'] . '">
                       <input type="hidden" name="issue_date" value="' . null . '">
                       <input type="hidden" name="return_date" value="' . null . '">
                       <input type="hidden" name="status_id" value="' . Status::PENDING . '">
                       <input class="ui blue basic button" type="submit" value="Request"/>
                   </form>';
        }
        $data = $data . '</div></td></tr>';
    }
    echo $data . '</tbody></table>';
}
