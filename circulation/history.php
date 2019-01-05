<?php
$page_title = 'Circulation History';
require_once '../functions/Repositories/UsersRepository.php';
require_once '../functions/Repositories/RequestsRepository.php';
require_once '../templates/navbar.php';

$url = APP_URL_BASE . '/circulation/history.php?';
$req_repo = new RequestsRepository();

$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$limit = 5;

if (isAdmin()) {

    $query = isset($_GET['query']) ? $_GET['query'] : '';
    $query_type = isset($_GET['type']) ? $_GET['type'] : '';

    switch ($query_type) {
        case '1':
            $total_pages = ceil($req_repo->totalRequestsByUserId($query) / $limit);
            $to = ($current_page - 1) * $limit;
            $url = $url . 'type=' . $query_type . '&query=' . $query . '&';
            $arr = $req_repo->findByUserIdPaginated($query, false, $to, $limit);
            break;
        case '2':
            $total_pages = ceil($req_repo->totalRequestsByBookId($query) / $limit);
            $to = ($current_page - 1) * $limit;
            $url = $url . 'type=' . $query_type . '&query=' . $query . '&';
            $arr = $req_repo->findByBookIdPaginated($query, $to, $limit);
            break;
        case '3':
            $total_pages = ceil($req_repo->totalRequestsByUserName($query) / $limit);
            $to = ($current_page - 1) * $limit;
            $url = $url . 'type=' . $query_type . '&query=' . $query . '&';
            $arr = $req_repo->findByUserNamePaginated($query, $to, $limit);
            break;
        case '4':
            $total_pages = ceil($req_repo->totalRequestsByBookTitle($query) / $limit);
            $to = ($current_page - 1) * $limit;
            $url = $url . 'type=' . $query_type . '&query=' . $query . '&';
            $arr = $req_repo->findByBookTitlePaginated($query, $to, $limit);
            break;
        default:
            $total_pages = ceil($req_repo->totalRecords() / $limit);
            $to = ($current_page - 1) * $limit;
            $arr = $req_repo->getPaginated($to, $limit);
            break;
    }

} elseif (!isAdmin()) {
    $query = isset($_GET['query']) ? $_GET['query'] : '';
    $query_type = isset($_GET['type']) ? $_GET['type'] : '';

    switch ($query_type){
        case '4':
            $total_pages = ceil($req_repo->totalRequestsByBookTitleForUserId(getUser()['id'], $query) / $limit);
            $to = ($current_page - 1) * $limit;
            $url = $url . 'type=' . $query_type . '&query=' . $query . '&';
            $arr = $req_repo->findByBookTitleForUserIdPaginated(getUser()['id'], $query, $to, $limit);
            break;
        default:
            $total_pages = ceil($req_repo->totalRequestsByUserId(getUser()['id']) / $limit);
            $to = ($current_page - 1) * $limit;
            $arr = $req_repo->findByUserIdPaginated(getUser()['id'], false, $to, $limit);
            break;
    }
}
?>
    <div class="row">
        <?php alertBox(); ?>
    </div>

    <div class="ui segment">
        <form class="ui form" method="get" action="">
            <div class="inline fields">
                <div class="field">
                    <label for="option">Type</label>
                    <select name="type" id="option">
                        <?php
                        if (isAdmin()) {
                            echo '<option value="1">User ID</option>
                                  <option value="2">Book ID</option>
                                  <option value="3">User Name</option>';
                        }
                        ?>
                        <option value="4">Book Title</option>
                    </select>
                </div>
                <div class="field">
                    <input type="text" name="query" id="query" placeholder="Search item">
                </div>
                <div class="field">
                    <button type="submit" class="ui blue button">Search</button>
                </div>
            </div>
        </form>
    </div>

    <table class="ui selectable celled table">
        <thead>
        <tr>
            <th class="one wide">Req ID</th>
            <?php if (isAdmin()) { ?>
                <th class="one wide">User ID</th>
                <th class="one wide">User Name</th>
            <?php } ?>
            <th class="one wide">Book ID</th>
            <th class="one wide">Book Title</th>
            <th class="one wide">Issue Date</th>
            <th class="one wide">Return Date</th>
            <th class="one wide">Status</th>
            <th class="one wide">Fine</th>
            <?php if (isAdmin()) { ?>
                <th class="one wide">Action</th>
            <?php } ?>
        </tr>
        </thead>
        <tbody>
        <?php
        if (!empty($arr) && count($arr) > 0) {
            $data = '';
            /* @var Request $req */
            foreach ($arr as $req) {
                $book = $req->getBook();
                $data .= '<tr>
                  <td>' . $req->getId() . '</td>';
                if (isAdmin()) {
                    $user_detail = $req->getUser()->getUserDetails();
                    $data .= '<td>' . $user_detail->getId() . '</td>
                  <td>' . $user_detail->getFirstName() . ' ' . $user_detail->getLastName() . '</td>';
                }
                $data .= '<td>' . $book->getId() . '</td>
                  <td>' . $book->getTitle() . '</td>
                  <td>' . $req->getIssueDate() . '</td>
                  <td>' . $req->getReturnDate() . '</td>
                  <td>' . $req->getStatus()->getName() . '</td>
                  <td>' . $req->getTotalFine() . '</td>';
                if (isAdmin()) {
                    $data .= '<td><a class="ui basic blue button" href="' . APP_URL_BASE . '/mail/send.php?email=' . $req->getUser()->getEmail() . '">Email</a></td>';
                }
                $data .= '</tr>';
            }
            echo $data;
        }
        ?>
        </tbody>
    </table>


<?php
printPagination($current_page, $total_pages, $url);
require_once '../templates/footer.php';
