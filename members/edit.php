<?php
$page_title = 'Edit Member';
require_once '../templates/navbar.php';
require_once '../functions/Models/User.php';
require_once '../functions/Models/UserDetails.php';
require_once '../functions/Repositories/UsersRepository.php';
require_once '../functions/Repositories/UserTypesRepository.php';

if (!isAdmin()) {
    redirectTo(APP_URL_BASE . '/dashboard');
}

if (isset($_GET['member_id'])) {
    $user_id = $_GET['member_id'];

    $repo = new UsersRepository();
    $user = $repo->findById($user_id);

    if ($user) {

        $type_id = $user->getUserType()->getId();
        $details = $user->getUserDetails();
        $gender = $details->getGender();

    } else {
        setAlert('Member doesn\'t exist!', 'danger');
        redirectTo(APP_URL_BASE . '/members/browse.php');
    }
} else{
    setAlert('Invalid request!', 'danger');
    redirectTo(APP_URL_BASE . '/members/browse.php');
}
?>

<div class="row">
    <?php alertBox() ?>
</div>

    <div class="ui placeholder segment">
        <form class="ui form" action="../functions/Validators/MemberValidator.php" method="POST" enctype="multipart/form-data">
            <div class="two fields">
                <div class="field">
                    <label for="first_name">First Name *</label>
                    <input type="text" name="first_name" id="first_name" class="form-control"
                           value="<?php echo $details->getFirstName() ?>" placeholder="John">
                </div>
                <div class="field">
                    <label for="last_name">Last Name *</label>
                    <input type="text" name="last_name" id="last_name" class="form-control"
                           value="<?php echo $details->getLastName() ?>" placeholder="Doe">
                </div>
            </div>

            <div class="two fields">
                <div class="field">
                    <label for="user_type">User Type *</label>
                    <select class="ui fluid dropdown" name="user_type" id="user_type">
                        <?php
                        $repo = new UserTypesRepository();
                        $arr = $repo->getAll();
                        $data = '';
                        foreach ($arr as $type) {
                            if ($type->getId() == $type_id) {
                                $data = $data . '<option selected value="' . $type->getId() . '">' . $type->getName() . '</option>';
                            } else {
                                $data = $data . '<option value="' . $type->getId() . '">' . $type->getName() . '</option>';
                            }
                        }
                        echo $data;
                        ?>
                    </select>
                </div>
                <div class="field">
                    <label for="user_gender">Gender *</label>
                    <select class="ui fluid dropdown" name="user_gender" id="user_gender">
                        <?php
                        $db_id = $gender->getId();
                        $data = '';
                        for ($id = 1; $id <= 3; $id++) {
                            if ($id == 1) {
                                if ($id == $db_id) {
                                    $data .= '<option value="1" selected>Male</option>';
                                } else $data .= '<option value="1">Male</option>';
                            } elseif ($id == 2) {
                                if ($id == $db_id) {
                                    $data .= '<option value="2" selected>Female</option>';
                                } else $data .= '<option value="2">Female</option>';
                            } elseif ($id == 3) {
                                if ($id == $db_id) {
                                    $data .= '<option value="3" selected>Other</option>';
                                } else $data .= '<option value="3">Other</option>';
                            }
                        }
                        echo $data;
                        ?>
                    </select>
                </div>
            </div>

            <div class="field">
                <label for="mobile">Mobile No</label>
                <input type="text" name="mobile" id="mobile" class="form-control"
                       value="<?php echo $details->getMobileNo() ?>" placeholder="0123456789">
            </div>
            <div class="field disabled">
                <label for="email">Email *</label>
                <input type="email" name="email" id="email" class="form-control"
                       value="<?php echo $user->getEmail() ?>" placeholder="johndoe@anonymous.com">
            </div>

            <div class="field">
                <label for="present_address">Present Address</label>
                <input type="text" name="present_address" id="present_address" class="form-control"
                       value="<?php echo $details->getPresentAddress() ?>"
                       placeholder="H#01, S#01, Bosumoti, Dhaka">
            </div>
            <div class="field">
                <label for="permanent_address">Permanent Address *</label>
                <input type="text" name="permanent_address" id="permanent_address" class="form-control"
                       value="<?php echo $details->getPermanentAddress() ?>"
                       placeholder="H#01, S#01, Bosumoti, Dhaka">
            </div>

            <div class="field">
                <label for="pro_pic">Profile Picture (JPG, PNG)</label>
                <input id="pro_pic" name="pro_pic" type="file">
            </div>

            <div>
                <button id="edit_member" name="edit_member" type="button" class="ui button blue">Save
                </button>
            </div>
            <input type="hidden" name="user_id" value="<?php echo $user_id ?>">
        </form>
    </div>

<?php
require_once '../templates/footer.php';