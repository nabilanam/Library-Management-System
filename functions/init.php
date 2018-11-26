<?php
session_start();
ob_start();

require_once 'Utilities/Utils.php';
require_once 'Utilities/Database.php';

DEFINE('APP_ROOT',dirname(__DIR__));
DEFINE('APP_UPLOAD_DIR_COVERS',APP_ROOT.'/uploads/covers');
DEFINE('APP_UPLOAD_DIR_EBOOKS',APP_ROOT.'/uploads/ebooks');

DEFINE('APP_BASE_URL','http://localhost/lms');
//DEFINE('APP_BASE_URL','http://43.225.150.252/lms');
DEFINE('APP_COVER_URL',APP_BASE_URL.'/uploads/covers');
DEFINE('APP_EBOOK_URL',APP_BASE_URL.'/uploads/ebooks');