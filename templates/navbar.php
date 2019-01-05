<?php
require_once '../functions/init.php';
redirectIfNotLoggedIn();
?>
<!doctype html>
<html lang="en">
<head>
    <title><?php echo 'LMS - ' . $page_title ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.3/semantic.min.css">
    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">

</head>
<body style="overflow-x: scroll">

<div class="ui grid">
    <div class="three wide column">
        <div class="ui left fixed vertical menu" style="">
            <a class="ui item" href="<?php echo APP_URL_BASE . '/dashboard' ?>">
                <i class="icon chart pie left"></i>Dashboard
            </a>

            <div class="ui dropdown item">
                <i class="icon book left"></i>Book<i class="dropdown icon"></i>
                <div class="menu">
                    <?php
                    if (isAdmin()) {
                        echo '<a class="item" href="' . APP_URL_BASE . '/books/add.php' . '">Add</a>';
                    }
                    ?>
                    <a class="item" href="<?php echo APP_URL_BASE . '/books/browse.php' ?>">Browse</a>
                    <a class="item" href="<?php echo APP_URL_BASE . '/books/by_category.php' ?>">By Category</a>
                    <a class="item" href="<?php echo APP_URL_BASE . '/books/by_shelf.php' ?>">By Shelf</a>
                    <a class="item" href="<?php echo APP_URL_BASE . '/books/search.php' ?>">Search</a>
                </div>
            </div>

            <?php
            if (isAdmin()) {
                echo '<div class="ui dropdown item">
                        <i class="icon address book left"></i>Member<i class="dropdown icon"></i>
                        <div class="menu">
                            <a class="item" href="' . APP_URL_BASE . '/members/add.php">Add</a>
                            <a class="item" href="' . APP_URL_BASE . '/members/browse.php">Browse</a>
                            <a class="item" href="'.APP_URL_BASE . '/members/search.php">Search</a>
                        </div>
                      </div>
                      <div class="ui dropdown item">
                        <i class="icon building left"></i>Institute<i class="dropdown icon"></i>
                        <div class="menu">
                            <a class="item" href="' . APP_URL_BASE . '/institutes/add.php">Add</a>
                            <a class="item" href="' . APP_URL_BASE . '/institutes/browse.php">Browse</a>
                            <a class="item" href="'.APP_URL_BASE . '/institutes/search.php">Search</a>
                        </div>
                      </div>
                      <div class="ui dropdown item">
                        <i class="icon shopping cart left"></i>Issue & Return<i class="dropdown icon"></i>
                        <div class="menu">
                            <a class="item" href="' . APP_URL_BASE . '/issue/">Issue</a>
                            <a class="item" href="' . APP_URL_BASE . '/issue/return.php">Return</a>
                        </div>
                      </div>
            ';
            } ?>

            <div class="ui dropdown item">
                <i class="icon sync left"></i>Circulation<i class="dropdown icon"></i>
                <div class="menu">
                    <a class="item" href="<?php echo APP_URL_BASE . '/circulation/delays.php' ?>">Delays</a>
                    <a class="item" href="<?php echo APP_URL_BASE . '/circulation/history.php' ?>">History</a>
                    <?php
                    if (isAdmin()){
                        echo '<a class="item" href="'.APP_URL_BASE . '/circulation/settings.php">Settings</a>';
                    }
                    ?>
                </div>
            </div>

            <div class="ui dropdown item">
                <i class="icon paper plane left"></i>Request<i class="dropdown icon"></i>
                <div class="menu">
                    <a class="item" href="<?php echo APP_URL_BASE . '/requests'?>">Requests</a>
                </div>
            </div>
            <?php if (isAdmin()) {
                echo '<div class="ui dropdown item">
                    <i class="icon mail left"></i>Email<i class="dropdown icon"></i>
                    <div class="menu">
                        <a class="item" href="' . APP_URL_BASE . '/mail/send.php">Send</a>
                        <a class="item" href="' . APP_URL_BASE . '/mail/history.php">History</a>
                        <a class="item" href="' . APP_URL_BASE . '/mail/settings.php">Settings</a>
                    </div>
                </div>';
            }
            ?>

            <div class="ui dropdown item">
                <i class="icon user left"></i><?php echo getUser()['name'] ?><i class="dropdown icon"></i>
                <div class="menu">
                    <a class="item" href="<?php echo APP_URL_BASE . '/profile/index.php' ?>">View Profile</a>
                    <a class="item" href="<?php echo APP_URL_BASE . '/profile/edit.php' ?>">Edit Details</a>
                </div>
            </div>

            <a class="item" href="<?php echo APP_URL_BASE . '/auth/login.php' ?>"><i class="icon sign-out left"></i>Log
                Out</a>
        </div>
    </div>
    <div class="thirteen wide column" style="float: left;">
        <div class="ui grid container center aligned">
            <div class="row"></div>