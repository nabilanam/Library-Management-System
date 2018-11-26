<?php
$page_title = 'Edit Member';
require_once '../templates/navbar.php';
require_once '../functions/Models/User.php';
require_once '../functions/Models/UserDetails.php';
require_once '../functions/Repositories/UsersRepository.php';
require_once '../functions/Repositories/UserTypesRepository.php';

if (!isAdmin()){
    redirectTo(APP_BASE_URL.'/dashboard');
}
/* @var User $user */

if (isset($_GET['member_id'])) {
    $id = $_GET['member_id'];
    $repo = new UsersRepository();
    $arr = $repo->findById($id);
    if (count($arr) == 1) {
        $user = $repo->findById($id)[0];
        $type_id = $user->getUserType()->getId();
        $details = $user->getUserDetails();
        $gender = $details->getGender();
    } else {
        die('Member doesn\'t exist!');
    }
}
?>
    <!-- Form Designing-->
    <div class="ui container">
        <div class="panel-heading" style="background-color:gray()">
            <h3 style="color: blue;text-align: center">Edit member information</h3>
        </div>

        <div class="panel-body">
            <div class="col-md-10">
                <div class="offset-md-3 col-md-7">
                    <form class="ui form" action="../functions/Validators/MemberValidator.php" method="POST">
                        <div class="two fields">
                            <div class="field">
                                <label for="first_name">First Name</label>
                                <input type="text" name="first_name" id="first_name" class="form-control"
                                       value="<?php echo $details->getFirstName() ?>" placeholder="John">
                            </div>
                            <div class="field">
                                <label for="last_name">Last Name</label>
                                <input type="text" name="last_name" id="last_name" class="form-control"
                                       value="<?php echo $details->getLastName() ?>" placeholder="Doe">
                            </div>
                        </div>

                        <div class="two fields">
                            <div class="field">
                                <label for="user_type">User Type</label>
                                <select class="ui fluid dropdown" name="user_type" id="user_type">
                                    <?php
                                    $repo = new UserTypesRepository();
                                    $arr = $repo->getAll();
                                    $data = '';
                                    foreach ($arr as $type) {
                                        if($type->getId() == $type_id){
                                            $data = $data . '<option selected value="'.$type->getId().'">'.$type->getName().'</option>';
                                        } else{
                                            $data = $data . '<option value="'.$type->getId().'">'.$type->getName().'</option>';
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
                                        $id = $gender->getId();
                                        if ($id == 1){
                                            echo '
                                                <option value="1" selected>Male</option>
                                                <option value="2">Female</option>
                                                <option value="3">Other</option>
                                            ';
                                        }elseif ($id == 2){
                                            echo '
                                                <option value="1">Male</option>
                                                <option value="2" selected>Female</option>
                                                <option value="3">Other</option>
                                            ';
                                        }elseif ($id == 2){
                                            echo '
                                                <option value="1">Male</option>
                                                <option value="2">Female</option>
                                                <option value="3" selected>Other</option>
                                            ';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="field">
                            <label for="email">Email *</label>
                            <input type="email" name="email" id="email" class="form-control"
                                   value="<?php echo $user->getEmail() ?>" placeholder="johndoe@anonymous.com">
                        </div>
                        <div class="field">
                            <label for="mobile">Mobile No</label>
                            <input type="text" name="mobile" id="mobile" class="form-control"
                                   value="<?php echo $details->getMobileNo() ?>" placeholder="0123456789">
                        </div>

                        <div class="field">
                            <label for="present_address">Present Address</label>
                            <input type="text" name="present_address" id="present_address" class="form-control"
                                   value="<?php echo $details->getPresentAddress() ?>"
                                   placeholder="H#01, S#01, Bosumoti, Dhaka">
                        </div>
                        <div class="field">
                            <label for="permanent_address">Permanent Address</label>
                            <input type="text" name="permanent_address" id="permanent_address" class="form-control"
                                   value="<?php echo $details->getPermanentAddress() ?>"
                                   placeholder="H#01, S#01, Bosumoti, Dhaka">
                        </div>

                        <div>
                            <button id="save_member" name="save_member" type="button" class="btn btn-primary">Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php
require_once '../templates/footer.php';