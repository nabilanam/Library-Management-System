<?php

require_once __DIR__ . "/../../vendor/autoload.php";
require_once __DIR__ . "/../Repositories/MailsRepository.php";
require_once __DIR__ . "/../Repositories/MailSettingsRepository.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    /* @var \PHPMailer\PHPMailer\PHPMailer */
    private $phpmailer;
    private $settings;
    private $repo;

    public function __construct()
    {
        $settings_repo = new MailSettingsRepository();
        $this->settings = $settings_repo->find();
        $this->phpmailer = new PHPMailer(true);
        $this->repo = new MailsRepository();
    }

    /**
     * @param Mail $mail
     * @return bool
     */
    public function send($mail)
    {
        if (!$this->settings){
            setAlert('Please configure mail settings!','danger');
            return false;
        }
        try {
            $this->phpmailer->isSMTP();
            $this->phpmailer->Host = $this->settings->getHost();
            $this->phpmailer->SMTPAuth = true;
            $this->phpmailer->Username = $this->settings->getUsername();
            $this->phpmailer->Password = $this->settings->getPassword();
            $this->phpmailer->SMTPSecure = 'tls';
            $this->phpmailer->Port = $this->settings->getPort();

            $this->phpmailer->setFrom($this->settings->getUsername(), 'Library System');
            $this->phpmailer->addAddress($mail->getAddress());

            $this->phpmailer->isHTML(true);
            $this->phpmailer->Subject = $mail->getSubject();
            $this->phpmailer->Body = $mail->getMessage();

            if ($this->phpmailer->send()) {
                $this->repo->add($mail);
                return true;
            }
            return false;

        } catch (Exception $e) {
            return false;
        }
    }
}