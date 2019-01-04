<?php
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Repositories/UsersRepository.php';

function redirectIfNotLoggedIn()
{
    if (!isset($_SESSION['user_id'])) {
        redirectTo(APP_URL_BASE . '/auth/login.php');
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
    if ($type == 'success') $type = 'positive';
    if ($type == 'danger') $type = 'negative';
    $_SESSION['alert'] = $message;
    $_SESSION['alert_type'] = $type;
}

function alertBox()
{
    if (isset($_SESSION['alert']) && isset($_SESSION['alert_type'])) {
        echo '<div class="ui '.$_SESSION['alert_type'].' message">'
                .$_SESSION['alert']
                .'<i class="close icon"></i>
              </div>';
        unset($_SESSION['alert']);
        unset($_SESSION['alert_type']);
    }
}

function isAdmin()
{
    return $_SESSION['user_type'] == 'Admin';
}

function getUniqueToken()
{
    return md5(uniqid(mt_rand(), true));
}

function getToday(){
    return date('Y-m-d');
}

function getNow(){
    return date('Y-m-d H:i:s');
}

/**
 * @param string $subtrahend
 * @param string $minuend
 * @return float|int
 */
function subtractDays($minuend, $subtrahend){

    if ($subtrahend == null){
        $subtrahend = new DateTime();
    }else{
        $subtrahend = new DateTime($subtrahend);
    }

    if ($minuend == null){
        $minuend = new DateTime();
    }else{
        $minuend = new DateTime($minuend);
    }

    return (int)$subtrahend->diff($minuend)->format("%r%a");
}

function printPagination($current_page, $total_pages, $url)
{
    $span = 2;
    $items = '<div class="ui pagination menu">';

    if ($total_pages > 1 && $current_page <= $total_pages) {

        if (1 == $current_page) {
            $items .= '<a class="item active" href="' . $url . 'page=1">1</a>';
        } else {
            $items .= '<a class="item" href="' . $url . 'page=1">1</a>';
        }

        $i = max(2, $current_page - $span);

        if ($i > 2)
            $items .= '<div class="item"> ... </div>';

        for (; $i < min($current_page + $span + 1, $total_pages); $i++) {
            if ($i == $current_page) {
                $items .= '<a class="item active" href="' . $url . 'page=' . $i . '">' . $i . '</a>';
            } else {
                $items .= '<a class="item" href="' . $url . 'page=' . $i . '">' . $i . '</a>';
            }
        }

        if ($i != $total_pages)
            $items .= '<div class="item"> ... </div>';

        if ($total_pages == $current_page) {
            $items .= '<a class="item active" href="' . $url . 'page=' . $total_pages . '">' . $total_pages . '</a></div>';
        } else {
            $items .= '<a class="item" href="' . $url . 'page=' . $total_pages . '">' . $total_pages . '</a></div>';
        }

    } elseif ($total_pages == 1) {
        $items .= '<a class="item active" href="' . $url . 'page=' . $total_pages . '">' . $total_pages . '</a></div>';
    }

    echo $items;
}

