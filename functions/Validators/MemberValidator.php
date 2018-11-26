<?php
require_once '../Models/User.php';
require_once '../Models/UserDetails.php';
require_once '../Utilities/Mail.php';
require_once '../Repositories/UsersRepository.php';
require_once '../Repositories/UserTypesRepository.php';

if (isset($_POST['save_member']) && isAdmin()) {
    $first_name = $last_name = $user_type_id = $mobile = $email = $present_address = $permanent_address = null;
    $is_valid = true;

    if (isset($_POST['first_name']) && !empty($_POST['first_name'])) {
        $first_name = trim($_POST['first_name']);
        $len = strlen($first_name);
        if (empty($first_name) || $len < 3 || $len > 20) {
            $is_valid = false;
        }
    } else {
        $is_valid = false;
    }
    if (isset($_POST['last_name']) && !empty($_POST['last_name'])) {
        $last_name = trim($_POST['last_name']);
        $len = strlen($last_name);
        if (empty($last_name) || $len < 3 || $len > 20) {
            $is_valid = false;
        }
    } else {
        $is_valid = false;
    }

    if (isset($_POST['user_type']) && !empty($_POST['user_type'])) {
        $user_type_id = trim($_POST['user_type']);
        if (empty($user_type_id) || strlen($user_type_id) > 1) {
            $is_valid = false;
        }
    } else {
        $is_valid = false;
    }
    if (isset($_POST['user_gender']) && !empty($_POST['user_gender'])) {
        $user_gender_id = trim($_POST['user_gender']);
        if (empty($user_gender_id) || strlen($user_gender_id) > 1) {
            $is_valid = false;
        }
    } else {
        $is_valid = false;
    }

    if (isset($_POST['email']) && !empty($_POST['email'])) {
        $email = trim($_POST['email']);
        if (empty($email) || !preg_match('/^\S+@\S+\.\S+$/', $email)) {
            $is_valid = false;
        }
    } else {
        $is_valid = false;
    }
    if (isset($_POST['mobile']) && !empty($_POST['mobile'])) {
        $mobile = trim($_POST['mobile']);
        $len = strlen($mobile);
        if (empty($mobile) || $len <= 10 || $len > 11) {
            $is_valid = false;
        }
    }

    if (isset($_POST['present_address']) && !empty($_POST['present_address'])) {
        $present_address = trim($_POST['present_address']);
        if (empty($present_address) || strlen($present_address) < 3) {
            $is_valid = false;
        }
    } else {
        $is_valid = false;
    }
    if (isset($_POST['permanent_address']) && !empty($_POST['permanent_address'])) {
        $permanent_address = trim($_POST['permanent_address']);
        if (empty($permanent_address) || strlen($permanent_address) < 3) {
            $is_valid = false;
        }
    } else {
        $is_valid = false;
    }

    if ($is_valid) {
        $db = Database::getInstance();
        $db->beginTransaction();
        try {
            $repo = new UserTypesRepository();
            $arr = $repo->findById($user_type_id);
            if (count($arr) == 1) {
                $type = $arr[0];
                $repo = SimpleRepositoryFacade::getUserGendersRepository();
                $arr = $repo->findById($user_gender_id);
                if (count($arr) == 1) {
                    $gender = $arr[0];
                    $repo = new UserDetailsRepository();
                    $details = $repo->add(new UserDetails(null, $gender, $first_name, $last_name, $mobile, $present_address, $permanent_address));
                    if ($details) {
                        $token = get_unique_token();
                        $user = new User(null, $type, $details, $email, null, $token, 0);
                        $repo = new UsersRepository();
                        if ($user = $repo->add($user)) {
                            $mail = new Mail();
                            $subject = 'Activate Your Account';
                            $message = 'Please click the following link to activate your account<br>'
                                . '<a href="' . APP_BASE_URL . '/auth/activate.php?id=' . $user->getId() . '&code=' . $token . '">Click Here</a>';

                            if ($mail->send_email($email, $subject, $message)) {
                                $db->commit();

                                /////// Redirect ///////
                                setAlert('Activation link sent!', 'success');
                                redirectTo(APP_BASE_URL . '/members/add.php');
                            }
                        }
                    }
                    $db->rollback();
                }
            }
        } catch (Exception $e) {
            $db->rollback();
        }
    }

    /////// Redirect ///////
    setAlert('User couldn\'t be added!', 'danger');
    redirectTo(APP_BASE_URL . '/members/add.php');
}