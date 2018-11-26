<?php include('store.php') ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>User registration system using PHP and MYSQL </title>

    <!-- Bootstrap -->
    <link href="../asset/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="../Style.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="header">
    <h2>Register</h2>
</div>

          <form method="post" action="register.php">


      <div class="input-group">
        <label>Username</label>
        <input type="text" name="user_name">
    </div>

    <div class="input-group">
        <label>Email</label>
        <input type="text" name="email">
    </div>

    <div class="input-group">
        <label>Password</label>
        <input type="password" name="password_1">
    </div>

    <div class="input-group">
        <label>Confirm Password</label>
        <input type="password" name="password_2">

    </div>

    <div class="input-group">
        <button type="submit" name="register" class="btn">Register</button>
    </div>
    <p>
        Already a member? <a href="Login/login.php">Sign in</a>
    </p>

</form>

</body>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="js/bootstrap.min.js"></script>

