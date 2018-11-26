<?php
require_once '../Models/Institute.php';
require_once '../Repositories/InstitutesRepository.php';

if (isset($_POST['save']) && getUser()['access'] == 'admin') {
    $fields_count = 0;
    $institute = new Institute();

    if (isset($_POST['name'])) {
        $name = trim($_POST['name']);
        if (isStringValidLength($name, 5, 40)) {
            $institute->setName($name);
            $fields_count++;
        }
    }
    if (isset($_POST['address'])) {
        $address = trim($_POST['address']);
        if (isStringValidLength($address, 10, 100)) {
            $institute->setAddress($address);
            $fields_count++;
        }
    }
    if (isset($_POST['email'])) {
        $email = trim($_POST['email']);
        if (isStringValidLength($email, 5, 50)) {
            $institute->setEmail($email);
            $fields_count++;
        }
    }
    if (isset($_POST['phone'])) {
        $phone = trim($_POST['phone']);
        if (isStringValidLength($phone, 10, 20)) {
            $institute->setPhone($phone);
            $fields_count++;
        }
    }

    $logo = $_FILES['logo'];
    if ($fields_count == 4 && $logo['error'] == UPLOAD_ERR_OK) {
        $dir = dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . "logos";
        if (!file_exists($dir)) {
            mkdir($dir);
        }
        if (is_uploaded_file($logo['tmp_name']) || file_exists($logo['tmp_name'])) {
            $extensions = array(
                'jpg' => 'image/jpeg',
                'png' => 'image/png'
            );
            if (isset($logo['error']) && $logo['error'] == UPLOAD_ERR_OK) {

                if ($logo['size'] < 100000) {

                    $file_info = new finfo(FILEINFO_MIME_TYPE);

                    if (false !== $ext = array_search($file_info->file($logo['tmp_name']), $extensions, true)) {

                        $sha1 = sha1_file($logo['tmp_name']);
                        $path = sprintf($dir . DIRECTORY_SEPARATOR . "%s.%s", $sha1, $ext);

                        move_uploaded_file($logo['tmp_name'], $path);

                        $institute->setLogoPath($sha1 . '.' . $ext);
                        $repo = new InstitutesRepository();

                        if ($repo->add($institute)) {
                            redirectTo(APP_BASE_URL . '/institutes/add.php');
                        }
                    }
                }
            }
        }
    }
    setAlert('Institute couldn\'t be added!','danger');
    redirectTo(APP_BASE_URL . '/institutes/add.php');
}

function isStringValidLength($str, $min, $max, $is_optional = false)
{
    $len = strlen($str);
    if ($is_optional) {
        return ($len == 0 || ($len >= $min && $len <= $max));
    }
    return ($len >= $min && $len <= $max);
}