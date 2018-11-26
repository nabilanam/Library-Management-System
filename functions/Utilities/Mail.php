<?php

require_once __DIR__."/../../vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mail
{
    /* @var \PHPMailer\PHPMailer\PHPMailer*/
    private $mail;
    private $host;
    private $username;
    private $password;
    private $port;

    public function __construct()
    {
        $this->host = 'smtp.mail.com';
        $this->username = 'librarysys@mail.com';
        $this->password = 'LMS@sys123';
        $this->port = 587;
        $this->mail = new PHPMailer(true);
    }

    public function send_email($address, $subject, $message)
    {
        try {
            $this->mail->isSMTP();
            $this->mail->Host = $this->host;
            $this->mail->SMTPAuth = true;
            $this->mail->Username = $this->username;
            $this->mail->Password = $this->password;
            $this->mail->SMTPSecure = 'tls';
            $this->mail->Port = $this->port;

            $this->mail->setFrom($this->username, 'Library System');
            $this->mail->addAddress($address);

            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body = $message;

            $this->mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}