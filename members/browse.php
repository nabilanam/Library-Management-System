<?php
$page_title = 'Browse Members';
require_once '../templates/navbar.php';
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
        $arr = $users_repo->findById($id);
        if (count($arr) == 1) {
            $user = $arr[0];
            if ($users_repo->remove($id)) {
                if ($details_repo->remove($user->getUserDetails()->getId())) {
                    $db->commit();
                    redirectTo(APP_BASE_URL . '/members/browse.php');
                }else{
                    setAlert('Couldn\'t delete user details!','danger');
                    $db->rollback();
                }
            }else{
                setAlert('Couldn\'t delete user!','danger');
            }
        }
    } catch (Exception $e) {
        $db->rollback();
    }
}
alertBox();
?>
    <div class="col-md-10" style="width: 80%">
        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <td>ID</td>
                <td>First Name</td>
                <td>Last Name</td>
                <td>User Type</td>
                <td>Email</td>
                <td>Mobile</td>
                <td>Present Address</td>
                <td>Permanent Address</td>
                <td>Action</td>
            </tr>
            </thead>
            <tbody>
            <?php
            /* @var User $user */
            $repo = new UsersRepository();
            $arr = $repo->getAll();
            foreach ($arr as $user) {
                $details = $user->getUserDetails();
                $data = '<tr>
                             <td>' . $user->getId() . '</td>
                             <td>' . $details->getFirstName() . '</td>
                             <td>' . $details->getLastName() . '</td>
                             <td>' . $user->getUserType()->getName() . '</td>
                             <td>' . $user->getEmail() . '</td>
                             <td>' . $details->getMobileNo() . '</td>
                             <td>' . $details->getPresentAddress() . '</td>
                             <td>' . $details->getPermanentAddress() . '</td>
                             <td>
                                 <a class="btn btn-success" href="' . APP_BASE_URL . '/members/view.php?member_id=' . $user->getId() . '" role="button" target="_blank">View</a>
                                 <a class="btn btn-primary" href="' . APP_BASE_URL . '/members/edit.php?member_id=' . $user->getId() . '" role="button" target="_blank">Edit</a>';

                if ($user->getUserType()->getId() != 1) {
                    $data = $data
                        . '<form action="browse.php" method="POST">
                            <a class="btn btn-danger" href="#" role="button" onclick="this.parentNode.submit(); return false;">Delete</a>
                            <input type="hidden" name="delete_id" value="' . $user->getId() . '">
                           </form>';
                }
                $data = $data
                    . '<a class="btn btn-info" href="' . APP_BASE_URL . '/members/history.php?member_id=' . $user->getId() . '" role="button" target="_blank">History</a>
                     </td></tr>';
                echo $data;
            }
            ?>
            </tbody>
        </table>
    </div>

<?php
require_once '../templates/footer.php';