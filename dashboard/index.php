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
                printItem($st->totalUsers(), 'user', 'Total Users',APP_URL_BASE . '/members/browse.php');
                printItem($st->totalBooksBorrowed(), 'book', 'Total Books Borrowed',APP_URL_BASE . '/books/browse.php');
                printItem($st->totalPendingRequests(), 'bell', 'Total Pending Requests',APP_URL_BASE . '/requests');
                printItem($st->totalNonReturnedBooks(), 'book', 'Total Non Returned Books',APP_URL_BASE.'/circulation/delays.php');
                printItem($st->totalUsersActivatedThisMonth(), 'user', 'Total Users Registered This Month',APP_URL_BASE. '/members/browse.php');
                printItem($st->totalBooksBorrowedThisMonth(), 'book', 'Total Books Borrowed This Month',APP_URL_BASE.'/circulation/history.php');
                printItem($st->totalEmailsSentThisMonth(), 'mail', 'Total Emails Sent This Month', APP_URL_BASE . '/mail/history.php');
            } else {
                printItem($st->totalBooksBorrowedByUser(), 'book', 'Total Books Borrowed', APP_URL_BASE . '/circulation/history.php');
                printItem($st->totalPendingRequestsByUser(), 'user', 'Total Pending Requests',APP_URL_BASE . '/requests');
                printItem($st->totalNonReturnedBooksByUser(), 'user', 'Total Non Returned Books',APP_URL_BASE.'/circulation/delays.php');
            }
            ?>
        </div>
    </div>


<?php
require_once '../templates/footer.php';