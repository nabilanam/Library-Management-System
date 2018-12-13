<?php
$page_title = 'Browse Institutes';
require_once '../templates/navbar.php';


$repo = new UsersRepository();
if (isset($_POST['delete_id']) && isAdmin()) {

    $id = $_POST['delete_id'];

    if ($repo->removeById($id)) {

        setAlert("Institute with id $id deleted successfully", 'success');
        redirectTo(APP_URL_BASE . '/institutes/browse.php');

    } else {
        echo 'ERROR';
    }
}

$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$results_per_page = 10;
$records = $repo->totalInstituteRecords();
$total_pages = ceil($records / $results_per_page);
$first_result = ($current_page - 1) * $results_per_page;


$arr = $repo->getInstitutesPaginated($first_result, $results_per_page);

$table = '<table class="ui selectable celled table">
    <thead>
    <tr>
        <th class="one wide">ID</th>
        <th class="one wide">Name</th>
        <th class="one wide">Address</th>
        <th class="one wide">Phone</th>
        <th class="one wide">Email</th>
        <th class="one wide">Action</th>
    </tr>
    </thead>
    <tbody>';
foreach ($arr as $user) {
    $table = $table . '<tr>
                        <td>' . $user->getId() . '</td>
                        <td>' . $user->getUserDetails()->getFirstName() . '</td>
                        <td>' . $user->getUserDetails()->getPermanentAddress() . '</td>
                        <td>' . $user->getUserDetails()->getMobileNo() . '</td>
                        <td>' . $user->getEmail() . '</td>
                        <td>
                          <div class="ui mini basic vertical buttons">
                            <a class="ui green basic button" href="' . APP_URL_BASE . '/institutes/view.php?id=' . $user->getId() . '">View</i></a>
                            <a class="ui blue basic button" href="' . APP_URL_BASE . '/institutes/edit.php?id=' . $user->getId() . '">Edit</a>
                            <form action="" method="POST">
                              <a class="ui red basic button" href="#" onclick="this.parentNode.submit(); return false;">Delete</a>
                              <input type="hidden" name="delete_id" value="' . $user->getId() . '">
                            </form>
                          </div>
                        </td>
                     </tr>';
}

alertBox();

echo $table . '</tbody></table>';

printPagination($current_page, $total_pages, APP_URL_BASE.'/institutes/browse.php?');

require_once '../templates/footer.php';
