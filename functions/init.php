<?php
session_start();
ob_start();

require_once 'Utilities/Utils.php';
require_once 'Utilities/Database.php';

DEFINE('APP_DIR_ROOT', dirname(__DIR__));
DEFINE('APP_DIR_COVERS', APP_DIR_ROOT . '/uploads/covers');
DEFINE('APP_DIR_EBOOKS', APP_DIR_ROOT . '/uploads/ebooks');
DEFINE('APP_DIR_LOGOS', APP_DIR_ROOT . '/uploads/logos');
DEFINE('APP_DIR_PRO_PICS', APP_DIR_ROOT . '/uploads/profile_pics');

DEFINE('APP_URL_BASE', 'http://localhost/lms');
DEFINE('APP_URL_COVERS', APP_URL_BASE . '/uploads/covers');
DEFINE('APP_URL_EBOOKS', APP_URL_BASE . '/uploads/ebooks');
DEFINE('APP_URL_LOGOS', APP_URL_BASE . '/uploads/logos');
DEFINE('APP_URL_PRO_PICS', APP_URL_BASE . '/uploads/profile_pics');

DEFINE('APP_ASSETS_JS', APP_URL_BASE . '/assets/js');

DEFINE('APP_MAX_SIZE_IMAGE', '2147483648');
DEFINE('APP_MAX_SIZE_FILE', '32212254720');

date_default_timezone_set('Asia/Dhaka');