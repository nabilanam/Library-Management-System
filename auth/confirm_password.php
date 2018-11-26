<?php
$page_title = 'Confirm Password';
require_once '../functions/Repositories/UsersRepository.php';
/* @var User $user */

$repo = new UsersRepository();
if (isset($_POST['id']) && isset($_POST['save_pass'])) {
    $pass1 = $_POST['pass1'];
    $pass2 = $_POST['pass2'];
    if ($pass1 === $pass2) {
        if (strlen($pass1) >= 6) {
            $user_id = $_POST['id'];
            $arr = $repo->findById($user_id);
            if (count($arr) == 1) {
                $user = $arr[0];
                if ($hash = getHashedPassword($pass1)) {
                    $user->setPasswordHash($hash);
                    $user->setValidationCode(null);
                    if ($repo->update($user)) {
                        unset($_SESSION['activation']);
                        setAlert('Password changed successfully. Please log in!', 'success');
                        redirectTo(APP_BASE_URL . '/auth/login.php');
                    } else {
                        setAlert('Database error! Please try again!', 'danger');
                    }
                } else {
                    setAlert('Error creating password!', 'danger');
                }
            } else {
                setAlert('User not found!', 'danger');
            }
        } else {
            setAlert('Password length must be greater than 6 digits!', 'danger');
        }
    } else {
        setAlert('Password do not match!', 'danger');
    }
    redirectTo(APP_BASE_URL . '/auth/confirm_password.php');
}


if (!(isset($_SESSION['activation']) && $_SESSION['activation'])
    && !(isset($_GET['id']) && isset($_GET['code']))) {
    redirectTo(APP_BASE_URL . '/dashboard');
}elseif(isset($_GET['id']) && isset($_GET['code'])){
    $arr = $repo->findById($_GET['id']);
    if (count($arr) == 1){
        $user = $arr[0];
        if (empty($user->getValidationCode()) || $_GET['code'] != $user->getValidationCode()){
            setAlert('Wrong validation code!','danger');
            redirectTo(APP_BASE_URL.'/dashboard');
        } else{
            setAlert('Please enter new password! Password length must be greater than 5.','success');
        }
    }
}

alertBox();
?>

    <!doctype html>
    <html lang="en">
    <head>
        <title>LMS - Confirm Password</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.3/semantic.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote-bs4.css">
        <link rel="stylesheet" type="text/css" href="../assets/css/style.css">

    </head>
    <body>
    <div class="offset-md-3 col-md-7">
        <form class="ui form" action="confirm_password.php" method="post">
            <div class="field">
                <label for="pass1">New Password</label>
                <input name="pass1" id="pass1" type="password"/>
            </div>
            <div class="field">
                <label for="pass2">Confirm Password</label>
                <input name="pass2" id="pass2" type="password"/>
            </div>
            <div>
                <input type="hidden" name="id" value="<?php echo $_GET['id']?>">
                <button id="save_pass" name="save_pass" type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>


<?php
require_once '../templates/footer.php';