<?php
$page_title = 'Add Member';
require_once '../templates/navbar.php';
require_once '../functions/Repositories/UserTypesRepository.php';
if (!isAdmin()) {
    redirectTo(APP_URL_BASE . '/dashboard');
}
?>

    <div class="row">
        <?php alertBox(); ?>
    </div>

    <div class="ui placeholder segment">
        <form class="ui form" action="../functions/Validators/MemberValidator.php" method="POST" enctype="multipart/form-data">
            <div class="two fields">
                <div class="field">
                    <label for="first_name">First Name *</label>
                    <input type="text" name="first_name" id="first_name" class="form-control"
                           placeholder="John">
                </div>
                <div class="field">
                    <label for="last_name">Last Name *</label>
                    <input type="text" name="last_name" id="last_name" class="form-control"
                           placeholder="Doe">
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
                            if ($type->getId() == 1) {
                                continue;
                            } elseif ($type->getId() == '3') {
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
                        <option value="1" selected>Male</option>
                        <option value="2">Female</option>
                        <option value="3">Other</option>
                    </select>
                </div>
            </div>

            <div class="field">
                <label for="email">Email *</label>
                <input type="email" name="email" id="email" class="form-control"
                       placeholder="johndoe@anonymous.com">
            </div>
            <div class="field">
                <label for="mobile">Mobile No</label>
                <input type="text" name="mobile" id="mobile" class="form-control"
                       placeholder="0123456789">
            </div>

            <div class="field">
                <label for="present_address">Present Address</label>
                <input type="text" name="present_address" id="present_address" class="form-control"
                       placeholder="H#01, S#01, Bosumoti, Dhaka">
            </div>
            <div class="field">
                <label for="permanent_address">Permanent Address *</label>
                <input type="text" name="permanent_address" id="permanent_address" class="form-control"
                       placeholder="H#01, S#01, Bosumoti, Dhaka">
            </div>

            <div class="field">
                <label for="pro_pic">Profile Picture (JPG, PNG)*</label>
                <input id="pro_pic" name="pro_pic" type="file">
            </div>

            <div>
                <button id="save_member" name="save_member" type="button" class="ui blue button">Save
                </button>
            </div>
        </form>
    </div>

<?php
require_once '../templates/footer.php';
