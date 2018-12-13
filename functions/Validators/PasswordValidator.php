<?php
require_once __DIR__ . '/../Repositories/UsersRepository.php';

if (isset($_POST['id']) && isset($_POST['save_pass'])) {

    $pass1 = $_POST['pass1'];
    $pass2 = $_POST['pass2'];

    if ($pass1 !== $pass2) {
        setAlert('Password do not match!', 'danger');
        redirectTo(APP_URL_BASE . '/auth/login.php');
    }
    if (strlen($pass1) < 6) {
        setAlert('Password length must be greater than 6 digits!', 'danger');
        redirectTo(APP_URL_BASE . '/auth/login.php');
    }

    $repo = new UsersRepository();
    $user = $repo->findById($_POST['id']);

    if (!$user) {
        setAlert('User ID not found!', 'danger');
        redirectTo(APP_URL_BASE . '/auth/login.php');
    }

    if ($hash = getHashedPassword($pass1)) {
        $user->setPasswordHash($hash);
        $user->setValidationCode(null);
        if ($repo->update($user)) {
            unset($_SESSION['activation']);
            setAlert('Password changed successfully. Please log in!', 'success');
            redirectTo(APP_URL_BASE . '/auth/login.php');
        } else {
            setAlert('Database error! Please try again!', 'danger');
            redirectTo(APP_URL_BASE . '/auth/login.php');
        }
    } else {
        setAlert('Error creating password!', 'danger');
        redirectTo(APP_URL_BASE . '/auth/login.php');
    }
}