<?php
require_once '../functions/init.php';
require_once '../functions/Repositories/UsersRepository.php';
/* @var User $user */
if (isset($_GET['id']) && isset($_GET['code'])) {
    $id = $_GET['id'];
    $code = $_GET['code'];
    $repo = new UsersRepository();
    $arr = $repo->findById($id);
    if (count($arr) == 1){
        $user = $arr[0];
        if ($code === $user->getValidationCode()){
            $user->setActivated(1);
            $user->setValidationCode(null);
            if($repo->update($user)){
                $_SESSION['activation'] = true;
                setUser($user->getId(), $user->getUserDetails()->getFirstName(), $user->getUserType()->getName());
                setAlert('Account activation successful! Create a password!','success');
                redirectTo(APP_BASE_URL.'/auth/confirm_password.php');
            }
        }
    }
}