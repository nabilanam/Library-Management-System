<?php
require_once '../functions/Repositories/UsersRepository.php';

/* @var User $user */
unset($_SESSION['user_id']);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['login'])) {

        $user_repo = new UsersRepository();

        $email = $_POST['email'];
        $password = $_POST['password'];

        if ($user = $user_repo->findByEmail($email)) {

            if (password_verify($password, $user->getPasswordHash())) {
                setUser($user->getId(), $user->getUserDetails()->getFirstName(), $user->getUserType()->getName());
                setAlert("Welcome {$user->getUserDetails()->getFirstName()}!", 'success');
                redirectTo(APP_URL_BASE . '/dashboard');
            } else {
                setAlert('Invalid password! Please try again!', 'danger');
            }

        }
    }
}
?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>LMS - Login </title>

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.3/semantic.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote-bs4.css">
        <!--        <link rel="stylesheet" type="text/css" href="../assets/css/style.css">-->
    </head>
<body style="background-color: #eee;">

<div style="text-align: center">
    <?php alertBox() ?>
</div>


<div class="ui column stackable center page grid middle aligned" style="height: 100vh;">
    <div class="row">
        <div class="five wide column"></div>
        <form class="ui seven wide column form segment" method="post">
            <div class="field">
                <h2 style="text-align: center;">Library Management System</h2>
            </div>
            <div class="ui inverted divider"></div>
            <div class="field">
                <label for="email">Email: </label>
                <input id="email" type="email" name="email">
            </div>

            <div class="field">
                <label for="password">Password: </label>
                <input id="password" type="password" name="password">
            </div>


            <div class="field">
                <button type="submit" name="login" class="btn">Log In</button>
            </div>
            <p>
                <a href="<?php echo APP_URL_BASE . '/auth/reset_password.php' ?>">Forget password</a>
            </p>
        </form>
    </div>
</div>

<?php require_once '../templates/footer.php' ?>