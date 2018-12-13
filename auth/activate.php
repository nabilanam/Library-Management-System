<?php
require_once '../functions/init.php';
require_once '../functions/Repositories/UsersRepository.php';

if (isset($_GET['id']) && isset($_GET['code'])) {

    $id = $_GET['id'];
    $code = $_GET['code'];
    $repo = new UsersRepository();
    $user = $repo->findById($id);
    if (!$user) {
        setAlert('User ID not found!','danger');
        redirectTo(APP_URL_BASE.'/auth/login.php');
    }
    if ($code !== $user->getValidationCode()) {
        setAlert('Invalid code!','danger');
        redirectTo(APP_URL_BASE.'/auth/login.php');
    }
    $user->setActivated(1);
    $user->setValidationCode(null);
    if ($user = $repo->update($user)) {
        $_SESSION['id'] = $user->getId();
        setUser($user->getId(), $user->getUserDetails()->getFirstName(), $user->getUserType()->getName());
        setAlert('Account activation successful! Create a password!', 'success');
        redirectTo(APP_URL_BASE . '/auth/confirm_password.php');
    }

}
setAlert('Database error! Please try later!', 'danger');
redirectTo(APP_URL_BASE . '/auth/login.php');