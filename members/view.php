<?php
$page_title = 'Member View';
require_once '../templates/navbar.php';
require_once '../functions/Repositories/UsersRepository.php';

if (!isAdmin()){
    redirectTo(APP_BASE_URL.'/dashboard');
}

/* @var User $user */
if (isset($_GET['member_id'])) {
    $id = $_GET['member_id'];
    $repo = new UsersRepository();
    $arr = $repo->findById($id);
    if (count($arr) == 1){
        $user = $arr[0];
        $details = $user->getUserDetails();
    }else{
        die('Book doesn\'t exist!');
    }
} else {
    redirectTo(APP_BASE_URL . '/books/browse.php');
}

?>

<div class="ui container text-center" style="">
    <table class="table table-bordered" style="">
        <tbody>
        <tr>
            <th scope="row" style="text-align: center">ID :</th>
            <td colspan="2" style="text-align: center"><?php echo $user->getId(); ?></td>
        </tr>
        <tr>
            <th scope="row" style="text-align: center">First Name :</th>
            <td colspan="2" style="text-align: center"><?php echo $details->getFirstName(); ?></td>
        </tr>
        <tr>
            <th scope="row" style="text-align: center">Last Name :</th>
            <td colspan="2" style="text-align: center"><?php echo $details->getLastName(); ?></td>
        </tr>
        <tr>
            <th scope="row" style="text-align: center">Membership:</th>
            <td colspan="2" style="text-align: center"><?php echo $user->getUserType()->getName(); ?></td>
        </tr>
        <tr>
            <th scope="row" style="text-align: center">Email :</th>
            <td colspan="2" style="text-align: center"><?php echo $user->getEmail(); ?></td>
        </tr>
        <tr>
            <th scope="row" style="text-align: center">User Type :</th>
            <td colspan="2" style="text-align: center"><?php echo $user->getUserType()->getName(); ?></td>
        </tr>
        <tr>
            <th scope="row" style="text-align: center">Mobile :</th>
            <td colspan="2" style="text-align: center"><?php echo $details->getMobileNo(); ?></td>
        </tr>
        <tr>
            <th scope="row" style="text-align: center">Present Address :</th>
            <td colspan="2" style="text-align: center"><?php echo $details->getPresentAddress(); ?></td>
        </tr>
        <tr>
            <th scope="row" style="text-align: center">Permanent Address :</th>
            <td colspan="2" style="text-align: center"><?php echo $details->getPermanentAddress(); ?></td>
        </tr>
        </tbody>
    </table>
</div>

<?php
require_once '../templates/footer.php';