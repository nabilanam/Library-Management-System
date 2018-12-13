<?php
$page_title = 'Circulation History';
require_once '../functions/Repositories/UsersRepository.php';
require_once '../functions/Repositories/RequestsRepository.php';
require_once '../templates/navbar.php';

$user_repo = new UsersRepository();
$req_repo = new RequestsRepository();

if (isAdmin()) {
    ?>
    <div class="row">
        <form class="four wide column" method="get">
            <select id="user_id" name="user_id" class="ui search fluid dropdown" onchange="this.form.submit()">
                <?php
                $arr = $user_repo->getAllIds();
                $options = '';
                foreach ($arr as $id) {
                    if ($id == 1) {
                        continue;
                    } else if (isset($_GET['user_id']) && $_GET['user_id'] == $id) {
                        $options = $options . '<option selected value="' . $id . '">' . $id . '</option>';
                    } else {
                        $options = $options . '<option value="' . $id . '">' . $id . '</option>';
                    }
                }
                echo $options;
                unset($arr);
                ?>
            </select>
        </form>
    </div>
    <?php
}


$url = APP_URL_BASE . '/circulation/history.php?';
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$limit = 5;

if (isAdmin() && isset($_GET['user_id']) && !empty($_GET['user_id'])) {

    $total_pages = ceil($req_repo->countAllBooksByUserId($_GET['user_id']) / $limit);
    $to = ($current_page - 1) * $limit;

    $url = $url . 'user_id=' . $_GET['user_id'] . '&';
    $arr = $req_repo->findByUserIdPaginated($_GET['user_id'], false, $to, $limit);

} elseif (isAdmin()) {

    $total_pages = ceil($req_repo->totalRecords() / $limit);
    $to = ($current_page - 1) * $limit;

    $arr = $req_repo->getPaginated($to, $limit);

} elseif (!isAdmin()) {

    $total_pages = ceil($req_repo->countAllBooksByUserId(getUser()['id']) / $limit);
    $to = ($current_page - 1) * $limit;

    $arr = $req_repo->findByUserIdPaginated(getUser()['id'], false, $to, $limit);
}

alertBox();
?>

    <table class="ui selectable celled table">
        <thead>
        <tr>
            <td class="one wide">Req ID</td>
            <?php if (isAdmin()) { ?>
                <td class="one wide">User ID</td>
                <td class="one wide">User Name</td>
            <?php } ?>
            <td class="one wide">Book ID</td>
            <td class="one wide">Book Title</td>
            <td class="one wide">Issue Date</td>
            <td class="one wide">Return Date</td>
            <td class="one wide">Status</td>
            <td class="one wide">Fine</td>
            <?php if (isAdmin()) { ?>
                <td class="one wide">Action</td>
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
