<?php
require_once 'Validator.php';
require_once '../Enums/UserTypes.php';
require_once '../Enums/Gender.php';
require_once '../Models/User.php';
require_once '../Models/Mail.php';
require_once '../Models/UserDetails.php';
require_once '../Utilities/Mailer.php';
require_once '../Repositories/UsersRepository.php';
require_once '../Repositories/UserTypesRepository.php';

if ((isset($_POST['save_institute']) || isset($_POST['edit_institute'])) && isAdmin()) {
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $name = isset($_POST['name']) ? $_POST['name'] : null;
    $address = isset($_POST['address']) ? $_POST['address'] : null;

    $user_type_id = UserTypes::INSTITUTE;
    $user_gender_id = Gender::OTHER;

    $mobile = isset($_POST['mobile']) && !empty($_POST['mobile']) ? $_POST['mobile'] : null;

    $logo = null;


    if (isset($_POST['save_institute'])) {
        $redirect_url = APP_URL_BASE . '/institutes/add.php';
    } else {
        $redirect_url = APP_URL_BASE . '/institutes/edit.php?id=' . $_POST['user_id'];
    }

    if (!isset($name) || !isStringValidLength($name, 3, 60)) {
        setAlert('First Name length should be between 3 and 60!', 'danger');
        redirectTo($redirect_url);
    }

    if (isset($_POST['save_institute'])
        && (!isset($email) || empty($email) || !preg_match('/^\S+@\S+\.\S+$/', $email))) {
        setAlert($_POST['email'], 'danger');
        redirectTo($redirect_url);
    }

    if (!isset($address) || !isStringValidLength($address, 3, 128)) {
        setAlert('Address length should be between 3 and 128!', 'danger');
        redirectTo($redirect_url);
    }

    if (file_exists($_FILES['logo']['tmp_name']) || is_uploaded_file($_FILES['logo']['tmp_name'])) {
        $file = $_FILES['logo'];
        if ($file['error'] == UPLOAD_ERR_OK) {
            $logo = uploadProfilePicture($file);
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
        $name,
        null,
        $mobile,
        null,
        $address,
        $logo
    );

    $db = Database::getInstance();
    $db->beginTransaction();

    $deatil_repo = new UserDetailsRepository();
    $user_repo = new UsersRepository();

    if (isset($_POST['save_institute'])) {
        try {
            if (empty($logo)) {
                setAlert('Valid logo required!', 'danger');
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
            unlink(APP_DIR_LOGOS . '/' . $logo);
            $db->rollback();

        } catch (Exception $e) {
            unlink(APP_DIR_LOGOS . '/' . $logo);
            $db->rollback();
        }
    } elseif (isset($_POST['edit_institute'])) {

        $user = $user_repo->findById($_POST['user_id']);
        $details = $user->getUserDetails();
        $details->setFirstName($name);
        $details->setGender($gender);
        $details->setMobileNo($mobile);
        $details->setPermanentAddress($address);
        if (!empty($logo)) {
            $details->setProPic($logo);
        }
        $user->setUserDetails($details);
        $user->setUserType($type);

        try {
            $details = $deatil_repo->update($details);
            $user = $user_repo->update($user);

            if ($details || $user) {
                $db->commit();

                setAlert('User info updated successfully!', 'success');
                redirectTo($redirect_url);

            } else {

                if (!empty($logo)) {
                    unlink(APP_DIR_LOGOS . '/' . $logo);
                }

                setAlert('Nothing to update!', 'warning');
                redirectTo($redirect_url);
            }

        } catch (Exception $e) {
            if (!empty($logo)) {
                unlink(APP_DIR_LOGOS . '/' . $logo);
            }
            $db->rollback();
            echo $e;
            return;
        }
    }

    setAlert('Email address must be unique!', 'danger');
    redirectTo(APP_URL_BASE . '/institutes/browse.php');

}