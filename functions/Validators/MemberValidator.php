<?php
require_once 'Validator.php';
require_once '../Models/User.php';
require_once '../Models/Mail.php';
require_once '../Models/UserDetails.php';
require_once '../Utilities/Mailer.php';
require_once '../Repositories/UsersRepository.php';
require_once '../Repositories/UserTypesRepository.php';

if ((isset($_POST['save_member']) || isset($_POST['edit_member']))
    && (isAdmin() || (isset($_POST['user_id']) && $_POST['user_id'] == getUser()['id']))) {

    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $first_name = isset($_POST['first_name']) ? $_POST['first_name'] : null;
    $last_name = isset($_POST['last_name']) ? $_POST['last_name'] : null;
    $permanent_address = isset($_POST['permanent_address']) ? $_POST['permanent_address'] : null;

    $user_type_id = isset($_POST['user_type']) ? $_POST['user_type'] : null;
    $user_gender_id = isset($_POST['user_gender']) ? $_POST['user_gender'] : null;

    $mobile = isset($_POST['mobile']) && !empty($_POST['mobile']) ? $_POST['mobile'] : null;
    $present_address = isset($_POST['present_address']) ? $_POST['present_address'] : null;

    $pro_pic = null;

    if (isset($_POST['save_member'])) {
        $redirect_url = APP_URL_BASE . '/members/add.php';
    } elseif (!isAdmin()) {
        $redirect_url = APP_URL_BASE . '/profile/edit.php';
    } else {
        $redirect_url = APP_URL_BASE . '/members/edit.php?member_id=' . $_POST['user_id'];
    }

    if (!isset($first_name) || !isStringValidLength($first_name, 3, 20)) {
        setAlert('First Name length should be between 3 and 20!', 'danger');
        redirectTo($redirect_url);
    }

    if (!isset($last_name) || !isStringValidLength($last_name, 3, 20)) {
        setAlert('Last Name length should be between 3 and 20!', 'danger');
        redirectTo($redirect_url);
    }

    if (!isset($user_type_id) || !isStringValidLength($user_type_id, 0, 1)) {
        setAlert('Invalid User Type!', 'danger');
        redirectTo($redirect_url);
    }

    if (!isset($user_gender_id) || !isStringValidLength($user_gender_id, 0, 1)) {
        setAlert('Invalid gender type!', 'danger');
        redirectTo($redirect_url);
    }

    if (isset($_POST['save_member'])
        && (!isset($email) || empty($email) || !preg_match('/^\S+@\S+\.\S+$/', $email))) {
        setAlert($_POST['email'], 'danger');
        redirectTo($redirect_url);
    }

    if (!isset($permanent_address) || !isStringValidLength($permanent_address, 3, 128)) {
        setAlert('Permanent address length should be between 3 and 128!', 'danger');
        redirectTo($redirect_url);
    }

    if (file_exists($_FILES['pro_pic']['tmp_name']) || is_uploaded_file($_FILES['pro_pic']['tmp_name'])) {
        $file = $_FILES['pro_pic'];
        if ($file['error'] == UPLOAD_ERR_OK) {
            $pro_pic = uploadProfilePicture($file);
        }
    }

    $repo = new UserTypesRepository();
    $type = $repo->findById($user_type_id);
    if (!$type) {
        setAlert('Invalid User Type!', 'danger');
        redirectTo($redirect_url);
    }

    $repo = SimpleRepositoryFacade::getUserGendersRepository();
    $gender = $repo->findById($user_gender_id);
    if (!$gender) {
        setAlert('Unknown Gender!', 'danger');
        redirectTo($redirect_url);
    }


    $details = new UserDetails(
        null,
        $gender,
        $first_name,
        $last_name,
        $mobile,
        $present_address,
        $permanent_address,
        $pro_pic
    );

    $db = Database::getInstance();
    $db->beginTransaction();

    $deatil_repo = new UserDetailsRepository();
    $user_repo = new UsersRepository();

    if (isset($_POST['save_member'])) {
        try {
            if (empty($pro_pic)) {
                setAlert('Valid profile picture required!', 'danger');
                redirectTo($redirect_url);
            }
            if ($details = $deatil_repo->add($details)) {
                $token = getUniqueToken();
                $user = new User(null, $type, $details, $email, null, $token, 0);

                if ($user = $user_repo->add($user)) {
                    $mailer = new Mailer();
                    $subject = 'Activate Your Account';
                    $link = APP_URL_BASE . '/auth/activate.php?id=' . $user->getId() . '&code=' . $token;
                    $message = 'Please click the following link to activate your account<br>'
                        . '<a href="' . $link . '">' . $link . '</a>';
                    $mail = new Mail(null, $email, $subject, $message);

                    if ($mailer->send($mail)) {
                        $db->commit();

                        setAlert('Activation link sent!', 'success');
                        redirectTo($redirect_url);
                    }
                }
            }
            unlink(APP_DIR_PRO_PICS . '/' . $pro_pic);
            $db->rollback();

        } catch (Exception $e) {
            unlink(APP_DIR_PRO_PICS . '/' . $pro_pic);
            $db->rollback();
        }
    } elseif (isset($_POST['edit_member'])) {

        $user = $user_repo->findById($_POST['user_id']);

        $details = $user->getUserDetails();
        $details->setFirstName($first_name);
        $details->setLastName($last_name);
        $details->setGender($gender);
        $details->setMobileNo($mobile);
        $details->setPresentAddress($present_address);
        $details->setPermanentAddress($permanent_address);
        if (!empty($pro_pic)) {
            $details->setProPic($pro_pic);
        }
        $user->setUserDetails($details);
        $user->setUserType($type);

        try {
            $details = $deatil_repo->update($details);
            if ($details){
                $user->setUserDetails($details);
            }
            $user = $user_repo->update($user);

            if ($details || $user) {
                $db->commit();

                setAlert('User info updated successfully!', 'success');
                if (!isAdmin()) {
                    redirectTo(APP_URL_BASE . '/profile');
                } else {
                    redirectTo(APP_URL_BASE . '/members/view.php?member_id=' . $_POST['user_id']);
                }

            } else {

                if (!empty($pro_pic)) {
                    unlink(APP_DIR_PRO_PICS . '/' . $pro_pic);
                }

                setAlert('Nothing to update!', 'warning');
                redirectTo($redirect_url);
            }

        } catch (Exception $e) {
            if (!empty($pro_pic)) {
                unlink(APP_DIR_PRO_PICS . '/' . $pro_pic);
            }
            $db->rollback();
            echo $e;
            return;
        }
    }

    setAlert('Email address must be unique!', 'danger');
    redirectTo($redirect_url);

} elseif (isset($_POST['delete_id']) && isAdmin()) {
    $id = $_POST['delete_id'];

    if ($page_title === 'Browse Members') {
        $redirect_url = APP_URL_BASE . '/members/browse.php';
    } else {
        $redirect_url = APP_URL_BASE . '/members/search.php';
    }

    $db = Database::getInstance();
    $db->beginTransaction();

    $users_repo = new UsersRepository();
    $details_repo = new UserDetailsRepository();
    $user = $users_repo->findById($id);

    try {
        if ($user) {
            if ($users_repo->removeById($id)) {
                if ($details_repo->removeById($user->getUserDetails()->getId())) {
                    $db->commit();
                    setAlert('Success!', 'success');
                    redirectTo($redirect_url);
                }
                $db->rollback();
            }
        }
    } catch (Exception $e) {
        $db->rollback();
    }

    setAlert('Sorry only members without circulation history can be deleted!', 'danger');
    redirectTo($redirect_url);
}