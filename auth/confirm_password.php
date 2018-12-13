<?php
$page_title = 'Confirm Password';
require_once __DIR__ . '/../functions/Repositories/UsersRepository.php';


$id = null;
if (!isset($_SESSION['id']) && !isset($_GET['id'])) {

    redirectTo(APP_URL_BASE . '/auth/login.php');
}
if (isset($_GET['id']) && isset($_GET['code'])) {

    $repo = new UsersRepository();
    $user = $repo->findById($_GET['id']);
    if ($user) {
        $id = $user->getId();
        if (empty($user->getValidationCode()) || $_GET['code'] != $user->getValidationCode()) {
            setAlert('Wrong validation code!', 'danger');
            redirectTo(APP_URL_BASE . '/auth/login.php');
        }
    }

}else{
    $id = $_SESSION['id'];
    unset($_SESSION['id']);
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

    </head>
<body>
<div class="ui column stackable center page grid middle aligned" style="height: 100vh;">
    <div class="row">
        <div class="five wide column"></div>
        <form class="ui seven wide column form segment" action="../functions/Validators/PasswordValidator.php" method="post">
            <div class="field">
                <label for="pass1">New Password</label>
                <input name="pass1" id="pass1" type="password"/>
            </div>
            <div class="field">
                <label for="pass2">Confirm Password</label>
                <input name="pass2" id="pass2" type="password"/>
            </div>
            <div>
                <button id="save_pass" name="save_pass" type="submit" class="btn btn-primary">Save</button>
            </div>
            <input type="hidden" name="id" value="<?php echo $id ?>">
        </form>
    </div>
</div>


<?php
require_once '../templates/footer.php';