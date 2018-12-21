<?php
/**
 * @param string $str
 * @param int $min
 * @param int $max
 * @param bool $is_optional
 * @return bool
 */
function isStringValidLength($str, $min, $max, $is_optional = false)
{
    $len = strlen($str);
    if ($is_optional) {
        return ($len == 0 || ($len >= $min && $len <= $max));
    }
    return ($len >= $min && $len <= $max);
}

/**
 * @param $number
 * @return bool
 */
function isPositiveInteger($number)
{
    if (is_numeric($number)) {
        if (is_string($number) && strpos($number, '.') === true) {
            return false;
        }
        $number = (int)$number;
        if ($number > 0) {
            return true;
        }
    }
    return false;
}

/**
 * @param $number
 * @return false|int
 */
function isValidCurrency($number)
{
    return preg_match("/^[\d+]{1,5}([\.]{1,1}[\d+]{1,2})?$/", $number);
}

/**
 * @param $file
 * @return bool|string
 */
function uploadEbook($file)
{
    if (is_uploaded_file($file['tmp_name']) || file_exists($file['tmp_name'])) {
        $extensions = array(
            'pdf' => 'application/pdf',
            'epub' => 'application/epub+zip',
            'chm' => 'application/vnd.ms-htmlhelp',
            'djvu' => 'image/vnd.djvu',
            'mobi' => 'application/x-mobipocket-ebook'
        );
        return upload($file, $extensions, APP_MAX_SIZE_FILE, APP_DIR_EBOOKS);
    }
    return false;
}


/**
 * @param $file
 * @return bool|string
 */
function uploadImage($file)
{
    if (is_uploaded_file($file['tmp_name']) || file_exists($file['tmp_name'])) {
        $extensions = array(
            'jpg' => 'image/jpeg',
            'png' => 'image/png'
        );
        return upload($file, $extensions, APP_MAX_SIZE_IMAGE, APP_DIR_COVERS);
    }
    return false;
}

/**
 * @param $file
 * @return bool|string
 */
function uploadProfilePicture($file)
{
    if (is_uploaded_file($file['tmp_name']) || file_exists($file['tmp_name'])) {
        $extensions = array(
            'jpg' => 'image/jpeg',
            'png' => 'image/png'
        );
        return upload($file, $extensions, APP_MAX_SIZE_IMAGE, APP_DIR_PRO_PICS);
    }
    return false;
}

/**
 * @param $file
 * @return bool|string
 */
function uploadLogo($file)
{
    if (is_uploaded_file($file['tmp_name']) || file_exists($file['tmp_name'])) {
        $extensions = array(
            'jpg' => 'image/jpeg',
            'png' => 'image/png'
        );
        return upload($file, $extensions, APP_MAX_SIZE_IMAGE, APP_DIR_LOGOS);
    }
    return false;
}

/**
 * @param $file
 * @param $extensions
 * @param $max_size
 * @param $directory
 * @return bool|string
 */
function upload($file, $extensions, $max_size, $directory)
{
    if (!file_exists($directory)) {
        mkdir($directory);
    }
    if (isset($file['error']) && !is_array($file['error']) && $file['error'] == UPLOAD_ERR_OK) {
        if ($file['size'] < $max_size) {
            $file_info = new finfo(FILEINFO_MIME_TYPE);
            if (false !== $ext = array_search($file_info->file($file['tmp_name']), $extensions, true)) {
                $sha1 = sha1_file($file['tmp_name']);
                $destination_path = sprintf($directory . DIRECTORY_SEPARATOR . "%s.%s", $sha1, $ext);
                if (file_exists($destination_path) || move_uploaded_file($file['tmp_name'], $destination_path)) {
                    return $sha1 . '.' . $ext;
                }
            }
        }
    }
    return false;
}