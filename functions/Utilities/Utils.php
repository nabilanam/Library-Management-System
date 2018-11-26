<?php
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Repositories/UsersRepository.php';

function redirectIfNotLoggedIn()
{
    if (!isset($_SESSION['user_id'])) {
        redirectTo(APP_BASE_URL . '/auth/login.php');
    }
}

function createDirectory($directory)
{
    return !is_dir($directory) ? mkdir($directory, 0777, true) : false;
}

function prettyPrint($object)
{
    print "<pre>";
    print print_r($object);
    print "</pre>";
}

function redirectTo($location)
{
    header("Location: {$location}");
    exit();
}

function setUser($id, $name, $user_type)
{
    $_SESSION['user_id'] = $id;
    $_SESSION['user_name'] = $name;
    $_SESSION['user_type'] = $user_type;
}

function getUser()
{
    return [
        'id' => $_SESSION['user_id'],
        'name' => $_SESSION['user_name'],
        'access' => $_SESSION['user_type']
    ];
}

function getHashedPassword($password)
{
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * @param $message
 * @param $type
 */
function setAlert($message, $type)
{
    $_SESSION['alert'] = $message;
    $_SESSION['alert_type'] = $type;
}

function alertBox()
{
    if (isset($_SESSION['alert']) && isset($_SESSION['alert_type'])) {
        echo '<div class="text-center alert alert-' . $_SESSION['alert_type'] . '" role="alert">'
            . $_SESSION['alert']
            . '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
               </button></div>';
        unset($_SESSION['alert']);
        unset($_SESSION['alert_type']);
    }
}

function isAdmin()
{
    return $_SESSION['user_type'] == 'Admin';
}

function get_unique_token()
{
    return md5(uniqid(mt_rand(), true));
}


