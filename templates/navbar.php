<?php
require_once '../functions/init.php';
redirectIfNotLoggedIn();
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

    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">

</head>
<body>


<div class="container-fluid display-table">
    <div class="row display-table-row">

        <!--Side menu-->
        <div class="col-md-2 display-table-cell valign-top" id="side-menu">
            <h2>Menu</h2>
            <div id='cssmenu'>
                <ul>
                    <!--Dashboard-->
                    <li>
                        <a href="<?php echo APP_BASE_URL . '/dashboard' ?>">
                            <span class="glyphicon glyphicon-th" aria-hidden="true"></span>
                            <span style="font-weight:bold">Dashboard</span>
                        </a>
                    </li>

                    <!--Book-->
                    <li class='link'>
                        <a href="#collapse-book" data-toggle="collapse" aria-controls="collapse-post">
                            <span class="glyphicon glyphicon-book" aria-hidden="true"></span>
                            <span style="font-weight:bold">Book</span>
                        </a>

                        <ul class="collapse collapseable" id="collapse-book">
                            <?php
                            if (isAdmin()) {
                                echo '
                                        <li>
                                            <a href="' . APP_BASE_URL . '/books/add.php' . '">
                                                <span class="glyphicon glyphicon-book" aria-hidden="true"></span>
                                                <span>Add</span>
                                            </a>
                                        </li>
                                    ';
                            }
                            ?>
                            <li>
                                <a href="<?php echo APP_BASE_URL . '/books/browse.php' ?>">
                                    <span class="glyphicon glyphicon-indent-left" aria-hidden="true"></span>
                                    <span>Browse</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo APP_BASE_URL . '/books/by_category.php' ?>">
                                    <span class="glyphicon glyphicon-book" aria-hidden="true"></span>
                                    <span>By Category</span>
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo APP_BASE_URL . '/books/by_shelf.php' ?>">
                                    <span class="glyphicon glyphicon-book" aria-hidden="true"></span>
                                    <span>By Shelf</span>
                                </a>
                            </li>
                        </ul>
                    </li>


                    <!--Member-->
                    <?php
                    if (isAdmin()) {
                        echo '
                            <li class=\'link\'>
                                <a href="#collapse-member" data-toggle="collapse" aria-controls="collapse-post">
                                    <span class="glyphicon glyphicon-book" aria-hidden="true"></span>
                                    <span style="font-weight:bold">Member</span>
                                </a>
        
                                <ul class="collapse collapseable" id="collapse-member">
                                    <li>
                                        <a href="' . APP_BASE_URL . '/members/add.php' . '">
                                            <span class="glyphicon glyphicon-book" aria-hidden="true"></span>
                                            <span>Add</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="' . APP_BASE_URL . '/members/browse.php' . '">
                                            <span class="glyphicon glyphicon-indent-left" aria-hidden="true"></span>
                                            <span>Browse</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        ';
                    }
                    ?>


                    <!--Institute-->
                    <?php
                    if (isAdmin()) {
                        echo '
                            <li class=\'link\'>
                                <a href="#collapse-institute" data-toggle="collapse" aria-controls="collapse-post">
                                    <span class="glyphicon glyphicon-book" aria-hidden="true"></span>
                                    <span style="font-weight:bold">Institute</span>
                                </a>
        
                                <ul class="collapse collapseable" id="collapse-institute">
                                    <li>
                                        <a href="' . APP_BASE_URL . '/institutes/add.php' . '">
                                            <span class="glyphicon glyphicon-book" aria-hidden="true"></span>
                                            <span>Add</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="' . APP_BASE_URL . '/institutes/browse.php' . '">
                                            <span class="glyphicon glyphicon-indent-left" aria-hidden="true"></span>
                                            <span>Browse</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        ';
                    }
                    ?>

                    <!--others-->
                    <?php
                    if (isAdmin()) {
                        echo '
                            <li class="link">
                                <a href="' . APP_BASE_URL . '/circulation' . '">
                                    <span class="glyphicon glyphicon-retweet" aria-hidden="true"></span>
                                    <span style="font-weight:bold">Circulation</span>
                                </a>
                            </li>
                        ';
                    }
                    ?>

                    <li class="link">
                        <a href="<?php echo APP_BASE_URL . '/notifications' ?>">
                            <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                            <span style="font-weight:bold">Notification</span>
                        </a>
                    </li>
                    <li class="link">
                        <a href="<?php echo APP_BASE_URL . '/requests' ?>">
                            <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                            <span style="font-weight:bold">Requested Books</span>
                        </a>
                    </li>
                    <li class="link">
                        <a href="<?php echo APP_BASE_URL . '/report' ?>">
                            <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                            <span style="font-weight:bold">Report</span>
                        </a>
                    </li>

                    <!-- Settings-->
                    <li>
                        <a href="<?php echo APP_BASE_URL . '/settings' ?>">
                            <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                            <span style="font-weight:bold">Settings</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>


        <!--header-->
        <div class="col-md-10 display-table-cell valign-top  box">
            <header id="nav-header" class="clearfix">

                <div class="row">
                    <div class="col-md">
                        <ul class="pull-right">
                            <li>
                                <span><?php echo getUser()['name'] ?></span>
                            </li>
                            <li>
                                <div class="notification-button">
                                    <a href="#"><i class="fa fa-bell"></i></a>
                                    <span class="notification-badge">2</span>
                                </div>
                            </li>
                            <li>
                                <a href="<?php echo APP_BASE_URL . '/auth/login.php' ?>">Log Out</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>