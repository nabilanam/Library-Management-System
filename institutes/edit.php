<?php
$page_title = 'Edit Institute';
require_once '../templates/navbar.php';

if (!isset($_GET['id'])){
    setAlert('Invalid request!','warning');
    redirectTo(APP_URL_BASE.'/institutes/browse.php');
}

$id = $_GET['id'];
$repo = new UsersRepository();
$user = $repo->findById($id);
if (!$user || $user->getUserType()->getId() != UserTypes::INSTITUTE){
    setAlert('Institute id not found!','danger');
    redirectTo(APP_URL_BASE.'/institutes/browse.php');
}

?>

<div class="row">
    <?php alertBox(); ?>
</div>

<div class="ui placeholder segment">
    <form class="ui form" action="../functions/Validators/InstituteValidator.php" method="POST"
          enctype="multipart/form-data">

        <div class="field">
            <label for="name">Name *</label>
            <input type="text" name="name" id="name" placeholder="Uttara University"
            value="<?php echo $user->getUserDetails()->getFirstName() ?>">
        </div>

        <div class="field">
            <label for="address">Address *</label>
            <input type="text" name="address" id="address" placeholder="R#15, S#06, Azampur, Uttara"
                   value="<?php echo $user->getUserDetails()->getPermanentAddress() ?>">
        </div>

        <div class="field disabled">
            <label for="email">Email *</label>
            <input type="email" name="email" id="email" placeholder="info@uttarauniversity.edu.bd"
                   value="<?php echo $user->getEmail() ?>">
        </div>

        <div class="field">
            <label for="logo">Logo</label>
            <input type="file" name="logo" id="logo">
            <p class="help-block">Format : png, jpg</p>
        </div>

        <div class="field">
            <label for="mobile">Phone</label>
            <input type="text" name="mobile" id="mobile" placeholder="+8802-58952280"
                   value="<?php echo $user->getUserDetails()->getMobileNo() ?>">
        </div>

        <div class="field">
            <button type="button" name="edit_institute" id="edit_institute" class="ui blue button">Save</button>
        </div>
        <input type="hidden" name="user_id" value="<?php echo $id ?>">
    </form>
</div>
<?php
require_once '../templates/footer.php';
?>
