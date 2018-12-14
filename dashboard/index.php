<?php
$page_title = 'Dashboard';
require_once '../templates/navbar.php';
require_once '../templates/dashboard_item.php';
require_once '../functions/Utilities/Statistics.php';
$st = new Statistics();
?>

<?php alertBox(); ?>

    <div class="row">
        <div class="three column stackable ui grid">
            <?php
            if (isAdmin()) {
                printItem($st->totalBooks(), 'book', 'Total Books', APP_URL_BASE . '/books/browse.php');
                printItem($st->totalUsers(), 'user', 'Total Users');
                printItem($st->totalBooksBorrowed(), 'book', 'Total Books Borrowed');
                printItem($st->totalPendingRequests(), 'bell', 'Total Pending Requests');
                printItem($st->totalNonReturnedBooks(), 'book', 'Total Non Returned Books');
                printItem($st->totalUsersActivatedThisMonth(), 'user', 'Total Users Registered This Month');
                printItem($st->totalBooksBorrowedThisMonth(), 'book', 'Total Books Borrowed This Month');
                printItem($st->totalEmailsSentThisMonth(), 'mail', 'Total Emails Sent This Month', APP_URL_BASE . '/mail/history.php');
            } else {
                printItem($st->totalBooksBorrowedByUser(), 'book', 'Total Books Borrowed', APP_URL_BASE . '/r/browse.php');
                printItem($st->totalPendingRequestsByUser(), 'user', 'Total Pending Requests');
                printItem($st->totalNonReturnedBooksByUser(), 'user', 'Total Non Returned Books');
            }
            ?>
        </div>
    </div>


<?php
require_once '../templates/footer.php';