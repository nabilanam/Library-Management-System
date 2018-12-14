<?php
$page_title = 'Search Members';
require_once '../templates/navbar.php';
require_once '../templates/member_table.php';
require_once '../functions/Repositories/UsersRepository.php';

$repo = new UsersRepository();

$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$results_per_page = 5;


if (isset($_GET['query']) && isset($_GET['option'])) {
    $query = trim($_GET['query']);
    $option = $_GET['option'];

    if (empty($query)){
        setAlert('Enter query!','danger');
        redirectTo(APP_URL_BASE.'/members/search.php');
    }

    if ($option == 1) {
        $number_of_rows = $repo->totalAllTypeSearchRecords($query);
        $total_pages = ceil($number_of_rows / $results_per_page);
        $first_result = ($current_page - 1) * $results_per_page;
        $arr = $repo->getPaginatedAllTypeSearch($query, $first_result, $results_per_page);
    }elseif ($option == 2) {
        $number_of_rows = $repo->totalPersonalSearchRecords($query);
        $total_pages = ceil($number_of_rows / $results_per_page);
        $first_result = ($current_page - 1) * $results_per_page;
        $arr = $repo->getPaginatedPersonalSearch($query, $first_result, $results_per_page);
    } elseif ($option == 3) {
        $number_of_rows = $repo->totalStaffSearchRecords($query);
        $total_pages = ceil($number_of_rows / $results_per_page);
        $first_result = ($current_page - 1) * $results_per_page;
        $arr = $repo->getPaginatedStaffSearch($query, $first_result, $results_per_page);
    } elseif ($option == 4) {
        $number_of_rows = $repo->totalStudentSearchRecords($query);
        $total_pages = ceil($number_of_rows / $results_per_page);
        $first_result = ($current_page - 1) * $results_per_page;
        $arr = $repo->getPaginatedStudentSearch($query, $first_result, $results_per_page);
    } elseif ($option == 5) {
        $number_of_rows = $repo->totalEducatorSearchRecords($query);
        $total_pages = ceil($number_of_rows / $results_per_page);
        $first_result = ($current_page - 1) * $results_per_page;
        $arr = $repo->getPaginatedEducatorSearch($query, $first_result, $results_per_page);
    }
}
?>

    <div class="row">
        <?php alertBox() ?>
    </div>


    <div class="ui placeholder segment">
        <form class="ui form" method="GET" action="search.php">
            <div class="inline fields">
                <div class="field">
                    <label for="query">Search by</label>
                    <select name="option" id="option">
                        <option value="1">All</option>
                        <option value="2">Personal</option>
                        <option value="3">Staff</option>
                        <option value="4">Student</option>
                        <option value="5">Educator</option>
                    </select>
                </div>
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
    printMemberTable($arr);
    printPagination($current_page, $total_pages, APP_URL_BASE . '/members/search.php?option=' . $option . '&query=' . $query . '&');
}
require_once '../templates/footer.php';
