<?php
// **********************Validation Functions**********************

function username_exists($username)
{
    $sql = "SELECT id FROM users WHERE username = '$username'";
    $result = query($sql);
    if (row_count($result) == 1) {
        return true;
    }
    return false;
}

function email_exists($email)
{
    $sql = "SELECT id FROM users WHERE email = '$email'";
    $result = query($sql);
    if (row_count($result) == 1) {
        return true;
    }
    return false;
}

// **********************Registration Functions **********************

function validate_registration()
{
    $min = 3;
    $max = 20;
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $first_name = clean($_POST['first_name']);
        $last_name = clean($_POST['last_name']);
        $username = clean($_POST['username']);
        $email = clean($_POST['email']);
        $password = clean($_POST['password']);
        $confirm_password = clean($_POST['confirm_password']);

//first name
        if (empty($first_name)) {
            $errors[] = "First Name can't be empty";
        } elseif (strlen($first_name) < $min) {
            $errors[] = "First Name can't be lesser than {$min} characters";
        } elseif (strlen($first_name) > $max) {
            $errors[] = "First Name can't be greater than {$max} characters";
        }

//last name
        if (empty($last_name)) {
            $errors[] = "Last Name can't be empty";
        } elseif (strlen($last_name) < $min) {
            $errors[] = "Last Name can't be lesser than {$min} characters";
        } elseif (strlen($last_name) > $max) {
            $errors[] = "Last Name can't be greater than {$max} characters";
        }

//username
        if (empty($username)) {
            $errors[] = "Username can't be empty";
        } elseif (username_exists($username)) {
            $errors[] = "Sorry this username already taken!";
        } elseif (strlen($username) < $min) {
            $errors[] = "Username can't be lesser than {$min} characters";
        } elseif (strlen($username) > $max) {
            $errors[] = "Username can't be greater than {$max} characters";
        }

//email
        if (empty($email)) {
            $errors[] = "Email can't be empty";
        } elseif (email_exists($email)) {
            $errors[] = "Sorry this email is already taken!";
        } elseif (strlen($email) < $min) {
            $errors[] = "Email can't be lesser than {$min} characters";
        }

//password
        if (strlen($password) < $min) {
            $errors[] = "Password can't be lesser than {$min} characters";
        } elseif (strlen($password) > $max) {
            $errors[] = "Password can't be greater than {$max} characters";
        }
        if ($password != $confirm_password) {
            $errors[] = "Your password do not match!";
        }

//error
        if (!empty($errors)) {
            foreach ($errors as $error) {
                display_error($error);
            }
        } else if (register_user($first_name, $last_name, $username, $email, $password)) {
            set_message("<p class='bg-success text-center'>Please check your email for activation link</p>");
            redirect("index.php");
            echo "REGISTRATION SUCCESSFUL";
        } else {
            set_message("<p class='bg-danger text-center'>Sorry we couldn't process the request</p>");
            redirect("index.php");
        }
    }
}

function register_user($first_name, $last_name, $username, $email, $password)
{
    $first_name = escape($first_name);
    $last_name = escape($last_name);
    $username = escape($username);
    $email = escape($email);
    $password = escape($password);

    $password = md5($password);
    $validation = md5($username . microtime());
    $sql = "INSERT INTO users(first_name, last_name, username, email, password, validation_code, active)"
        . " values('$first_name','$last_name','$username','$email','$password','$validation', 0)";
    $result = query($sql);

    $subject = 'Activate Account';
    $message = 'Please click the link below to activate your account 

	http://localhost/login/activate.php?email=$email&code=$validation';
    if (send_email_success($email, $subject, $message)) {
        return true;
    } else {
        $row = fetch_array($result);
        $id = $row['id'];
        $sql = "DELETE users WHERE id='" . $id . "'";
        query($sql);
        return false;
    }
}

function activate_user()
{
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (isset($_GET['email'])) {
            $email = clean($_GET['email']);
            $code = clean($_GET['code']);
            $sql = "SELECT id FROM users WHERE email='" . escape($email) . "' and validation_code='" . escape($code) . "'";
            $result = query($sql);
            confirm($result);
            if (row_count($result) == 1) {
                $sql = "UPDATE users SET active = 1, validation_code= 0 WHERE email = '" . escape($email) . "'";
                $result = query($sql);
                set_message("<p class='bg-success'>Your account has been activated. Please login.</p>");
                redirect('login.php');
            } else {
                set_message("<p class='bg-danger'>Sorry your account couldn't be activated.</p>");
                redirect('index.php');
            }
        }
    }
}


// ***************************Login Functions*******************************

function validate_user_login()
{
    $errors = [];
    $min = 3;
    $max = 20;
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = clean($_POST['email']);
        $password = clean($_POST['password']);
        if (isset($_POST['remeber'])) {
            $remember_me = clean($_POST['remeber']);
        } else {
            $remember_me = 'off';
        }

//email
        if (empty($email)) {
            $errors[] = "Email can't be empty";
        } elseif (strlen($email) < $min) {
            $errors[] = "Email can't be lesser than {$min} characters";
        }

//password
        if (strlen($password) < $min) {
            $errors[] = "Password can't be lesser than {$min} characters";
        } elseif (strlen($password) > $max) {
            $errors[] = "Password can't be greater than {$max} characters";
        }


//errors
        if (!empty($errors)) {
            foreach ($errors as $error) {
                display_error($error);
            }
        } elseif (is_login_successful($email, $password, $remember_me)) {
            redirect('admin.php');
        } else {
            display_error("Your login credentials are not correct");
        }
    }
}

function is_login_successful($email, $password, $remember_me)
{
    $sql = "SELECT id FROM users WHERE email = '" . escape($email) . "' AND password = '" . md5(escape($password)) . "'";
    $result = query($sql);
    if (row_count($result) == 1) {
        $_SESSION['email'] = $email;
        if ($remember_me = 'on') {
            setcookie('email', $email, time() + 604800);
        }
        return true;
    }
    return false;
}

function is_logged_in()
{
    if (isset($_SESSION['email']) or isset($_COOKIE['email'])) {
        return true;
    }
    return false;
}

// **********************************Recver Password***********************************

function recover_password()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_SESSION['token']) and $_POST['token'] === $_SESSION['token']) {
            $email = clean($_POST['email']);
            if (email_exists($email)) {

                $code = md5($email . time());
                setcookie('tem_access_code', $code, time() + 60);
                $sql = "UPDATE users SET validation_code='" . escape($code) . "' WHERE email='" . escape($email) . "'";
                $result = query($sql);
                confirm($result);

                $subject = 'Reset Password';
                $message = 'Here is your password reset code {$code} 
				Click to reset the password: http://localhost/Login/code.php?email=$email&code=$code';
                send_email_success($email, $subject, $message);
            } else {
                display_error('Email does not exist!');
            }

        }
    }
}

function validate_code()
{
    if (isset($_COOKIE['tem_access_code'])) {
        if (isset($_GET['email']) && isset($_GET['code'])) {
            $email = clean($_GET['email']);
            $code = clean($_GET['code']);
            $sql = "SELECT id FROM users WHERE email='" . escape($email) . "' AND validation_code='" . escape($code) . "'";
            $result = query($sql);
            confirm($result);
            if (row_count($result) == 1) {
                $validation_code = md5($email . time());
                $sql = "UPDATE users SET validation_code='" . escape($validation_code) . "' WHERE email='" . escape($email) . "'";
                $result = query($sql);
                if (row_count($result) == 1) {
                    redirect('reset.php?email=$email&code=$code');
                } else {
                    display_error('Server error!');
                }
            } else {
                display_error('Wrong validation!');
            }
        }
    } else {
        set_message("<p class='bg-danger text-center'>Validation Timout!</p>");
        redirect('recover.php');
    }
}

function password_reset()
{
    if (isset($_GET['email']) and isset($_GET['code'])) {
        $email = clean($_GET['email']);
        $code = clean($_GET['code']);
        $sql = "SELECT id FROM users WHERE email='" . escape($email) . "' AND validation_code='" . escape($code) . "'";
        $result = query($sql);
        if (row_count($result) == 1) {

        }
    }
}

function test()
{
    $sql = "SELECT * FROM users WHERE first_name='Nabil'";
    $result = query($sql);
    $rows = [];
    echo row_count($result);
    while ($row = fetch_array($result)) {
        $rows[] = $row;
    }
    echo '<pre>';
    print_r($rows);
    echo '</pre>';
}

?>