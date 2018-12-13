<?php
$page_title = 'Search Institutes';
require_once '../templates/navbar.php';
require_once '../templates/modal.php';
require_once '../functions/Repositories/UsersRepository.php';

$repo = new UsersRepository();

$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$results_per_page = 5;


if (isset($_GET['query'])) {
    $query = trim($_GET['query']);
    $number_of_rows = $repo->totalInstituteSearchRecords($query);
    $total_pages = ceil($number_of_rows / $results_per_page);
    $first_result = ($current_page - 1) * $results_per_page;
    $arr = $repo->getPaginatedInstituteSearch($query, $first_result, $results_per_page);
}
?>

    <div class="row">
        <?php alertBox() ?>
    </div>


    <div class="ui placeholder segment">
        <form class="ui form" method="GET" action="search.php">
            <div class="inline fields">
                <div class="field">
                    <label for="query">Name</label>
                    <input type="text" name="query" id="query">
                </div>
                <div class="field">
                    <button type="submit" class="ui blue button">Search</button>
                </div>
            </div>
        </form>
    </div>

<?php
if (!empty($arr)) {
    modal('Delete Member', 'Are you sure you want to delete this member?');

    $data = '<table class="ui selectable celled table">
        <thead>
        <tr>
            <td class="one wide">ID</td>
            <td class="one wide">Name</td>
            <td class="one wide">User Type</td>
            <td class="one wide">Email</td>
            <td class="one wide">Mobile</td>
            <td class="one wide">Address</td>
            <td class="one wide">Action</td>
        </tr>
        </thead>
        <tbody>';
    foreach ($arr as $user) {
        $details = $user->getUserDetails();
        $data .= '<tr>
                             <td>' . $user->getId() . '</td>
                             <td>' . $details->getFirstName() . '</td>
                             <td>' . $user->getUserType()->getName() . '</td>
                             <td>' . $user->getEmail() . '</td>
                             <td>' . $details->getMobileNo() . '</td>
                             <td>' . $details->getPermanentAddress() . '</td>
                             <td>
                                <div class="ui mini basic vertical buttons">
                                 <a class="ui green basic button" href="' . APP_URL_BASE . '/institutes/view.php?id=' . $user->getId() . '">View</a>';

        if ($user->getUserType()->getId() != 1) {
            $data .= '<a class="ui blue basic button" href="' . APP_URL_BASE . '/members/edit.php?id=' . $user->getId() . '">Edit</a>
                       <form id="delete_form" action="" method="POST">
                          <input type="hidden" name="delete_id" value="' . $user->getId() . '">
                          <a class="ui red basic button" href="#" role="button" 
                          onclick="$(\'.mini.modal\')
                          .modal({
                                closable  : true,
                                onApprove : function() {
                                    $(\'#delete_form\').submit();
                                    return true;
                                }})
                          .modal(\'show\')">Delete</a>
                       </form>';
        }
        $data .= '<a class="ui gray basic button" href="' . APP_URL_BASE . '/circulation/history.php?user_id=' . $user->getId() . '">History</a>
                     </div></td></tr>';
    }
    echo $data . '</tbody></table>';
    printPagination($current_page, $total_pages, APP_URL_BASE . '/institutes/search.php?query=' . $query . '&');
}
require_once '../templates/footer.php';
