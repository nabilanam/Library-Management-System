<?php
$page_title = 'Browse Members';
require_once '../templates/navbar.php';
require_once '../templates/member_table.php';
require_once '../functions/Models/User.php';
require_once '../functions/Models/UserDetails.php';
require_once '../functions/Repositories/UsersRepository.php';
require_once '../functions/Repositories/SimpleRepositoryFacade.php';

/* @var User $user */
if (isset($_POST['delete_id']) && isAdmin()) {
    $id = $_POST['delete_id'];

    $db = Database::getInstance();
    $db->beginTransaction();

    try {
        $users_repo = new UsersRepository();
        $details_repo = new UserDetailsRepository();
        $user = $users_repo->findById($id);
        if ($user) {
            if ($users_repo->removeById($id)) {
                if ($details_repo->removeById($user->getUserDetails()->getId())) {
                    $db->commit();
                    redirectTo(APP_URL_BASE . '/members/browse.php');
                }
                $db->rollback();
            }
            setAlert('Sorry only new members can be deleted!', 'danger');
        }
    } catch (Exception $e) {
        $db->rollback();
        setAlert('Sorry only new members can be deleted!', 'danger');
    }
}
alertBox();


$repo = new UsersRepository();

$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$results_per_page = 5;
$number_of_rows = $repo->totalNonInstituteRecords();
$total_pages = ceil($number_of_rows / $results_per_page);
$first_result = ($current_page - 1) * $results_per_page;

$arr = $repo->getPaginated($first_result, $results_per_page);

printMemberTable($arr);

printPagination($current_page, $total_pages, APP_URL_BASE . '/members/browse.php?');
require_once '../templates/footer.php';