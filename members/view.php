<?php
$page_title = 'Member View';
require_once '../templates/navbar.php';
require_once '../functions/Repositories/UsersRepository.php';

if (!isAdmin()) {
    redirectTo(APP_URL_BASE . '/dashboard');
}

/* @var User $user */
if (isset($_GET['member_id'])) {
    $id = $_GET['member_id'];
    $repo = new UsersRepository();
    $user = $repo->findById($id);
    if ($user) {
        $details = $user->getUserDetails();
    } else {
        setAlert('Member doesn\'t exist!','danger');
        redirectTo(APP_URL_BASE . '/members/browse.php');
    }
} else {
    redirectTo(APP_URL_BASE . '/members/browse.php');
}

?>
    <div class="row">
        <?php alertBox() ?>
    </div>

    <div class="ui placeholder segment">
        <table class="ui padded table celled striped selectable">
            <tbody>
            <tr>
                <th>Photo :</th>
                <td><img class="ui small rounded centered floated image"
                         src="<?php echo APP_URL_PRO_PICS . '/' . $details->getProPic(); ?>" alt=""></td>
            </tr>
            <tr>
                <th>ID :</th>
                <td><?php echo $user->getId(); ?></td>
            </tr>
            <tr>
                <th>First Name :</th>
                <td><?php echo $details->getFirstName(); ?></td>
            </tr>
            <tr>
                <th>Last Name :</th>
                <td><?php echo $details->getLastName(); ?></td>
            </tr>
            <tr>
                <th>Email :</th>
                <td><?php echo $user->getEmail(); ?></td>
            </tr>
            <tr>
                <th>User Type :</th>
                <td><?php echo $user->getUserType()->getName(); ?></td>
            </tr>
            <tr>
                <th>Mobile :</th>
                <td><?php echo $details->getMobileNo(); ?></td>
            </tr>
            <tr>
                <th>Present Address :</th>
                <td><?php echo $details->getPresentAddress(); ?></td>
            </tr>
            <tr>
                <th>Permanent Address :</th>
                <td><?php echo $details->getPermanentAddress(); ?></td>
            </tr>
            </tbody>
        </table>

    </div>
<?php
require_once '../templates/footer.php';