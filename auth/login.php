<?php
require_once '../functions/Repositories/UsersRepository.php';

/* @var User $user */
unset($_SESSION['user_id']);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $repo = new UsersRepository();
        $arr = $repo->findByEmail($email);
        if (count($arr) == 1) {
            $user = $arr[0];
            if (password_verify($password, $user->getPasswordHash())) {
                setUser($user->getId(), $user->getUserDetails()->getFirstName(), $user->getUserType()->getName());
                setAlert("Welcome {$user->getUserDetails()->getFirstName()}!", 'success');
                redirectTo(APP_BASE_URL . '/dashboard');
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
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>User registration system using PHP and MYSQL </title>

    <!-- Bootstrap -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../Style.css">


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<?php alertBox() ?>

<div class="header">
    <h2>Sign in</h2>
</div>

<form method="post">
    <div class="input-group">
        <label for="email">Enter your Email </label>
        <input id="email" type="email" name="email">
    </div>

    <div class="input-group">
        <label for="password">Password</label>
        <input id="password" type="password" name="password">
    </div>
    <div class="input-group">
        <button type="submit" name="login" class="btn">Sign in</button>
    </div>
    <p>
        <a href="<?php echo APP_BASE_URL . '/auth/reset_password.php' ?>">Forget password</a>
    </p>
</form>

</body>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="../assets/js/bootstrap.min.js"></script>

</html>