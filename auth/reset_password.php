<?php
$page_title = 'Reset Password';
require_once '../functions/Models/User.php';
require_once '../functions/Models/Mail.php';
require_once '../functions/Repositories/UsersRepository.php';
require_once '../functions/Utilities/Mailer.php';

/* @var User $user */
if (isset($_POST['reset']) && isset($_POST['email'])) {
    $email = $_POST['email'];
    $repo = new UsersRepository();
    $user = $repo->findByEmail($email);
    $sent = false;
    if ($user) {
        $user->setValidationCode(getUniqueToken());
        if ($repo->update($user)) {
            $message = "Please click the following link to change password<br>"
                . '<a href="'
                . APP_URL_BASE . '/auth/confirm_password.php?id=' . $user->getId() . '&code=' . $user->getValidationCode() . '">Click Here</a>';

            $mail = new Mail(
                null,
                $email,
                'Reset Password',
                $message
            );

            $mailer = new Mailer();
            if ($mailer->send($mail)) {
                $sent = true;
            }
        }
        if ($sent) {
            setAlert('Please check your email for instruction!', 'success');
        } else {
            setAlert('Email could not be sent! Please try again.', 'danger');
        }
    } else {
        setAlert('User not found!', 'danger');
    }
    redirectTo(APP_URL_BASE.'/auth/login.php');
}
?>

    <!doctype html>
    <html lang="en">
    <head>
        <title><?php echo 'LMS - ' . $page_title ?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.3/semantic.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote-bs4.css">
    </head>
<body>

<?php alertBox() ?>
    <div class="ui column stackable center page grid middle aligned" style="height: 100vh;">
        <div class="row">
            <div class="five wide column"></div>
            <form class="ui six wide column form segment" method="POST" action="reset_password.php">
                <div class="field">
                    <label for="name">Enter Email:</label>
                    <input class="form-control" id="email" name="email" placeholder="abc@xyz.com">
                </div>
                <div class="field">
                    <input type="button" id="reset" name="reset" class="btn btn-primary" value="Reset password">
                </div>
            </form>
        </div>
    </div>

<?php require_once '../templates/footer.php' ?>