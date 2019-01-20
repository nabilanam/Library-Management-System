<?php
require_once __DIR__ . '/../init.php';
require_once __DIR__ . '/../Models/Mail.php';
require_once __DIR__ . '/../Utilities/Mailer.php';
require_once __DIR__ . '/../../functions/Repositories/RequestsRepository.php';

/**
 * only for windows
 */


if (!empty($argv) && $argv[1] == 'scheduler') {
    $mailer = new Mailer();
    $repo = new RequestsRepository();
    $records = $repo->getRequestsWhereReturnDateExpiredToday();

    foreach ($records as $req) {
        $user = $req->getUser();
        $details = $user->getUserDetails();
        $book_title = $req->getBook()->getTitle();
        $fine = $user->getUserType()->getFinePerDay();
        $username = $details->getFirstName() . ' ' . $details->getLastName();

        $subject = 'Book Return Time Expired!';

        $message = $username . ' your return period for "' . $book_title . '" is expired. '
            . 'You will be fined BDT ' . $fine . '/= each day starting from today. '
            . 'Please try to return the book as soon as possible.';

        $mail = new Mail(
            null,
            $req->getUser()->getEmail(),
            $subject,
            $message
        );
        if ($mailer->send($mail)) {
            echo $mail->getAddress() . ' success';
        } else {
            echo $mail->getAddress() . ' failure';
        }
    }
}

class MailAlertTask
{
    private $name = "LMSMailTask";

    public function create()
    {
        $cmd = 'schtasks /create /tn ' . $this->name . ' /tr "php ' . __FILE__ . ' scheduler" /sc DAILY /st 00:00';
        exec($cmd, $output, $return_var);
        if (strpos($output[0],'SUCCESS:') === 0){
            return true;
        }
        if (strpos($output[0],'WARNING:') === 0){
            return true;
        }
        return false;
    }

    public function delete()
    {
        $cmd = 'schtasks /delete /f /tn ' . $this->name;
        exec($cmd, $output, $return_var);
    }

    public function exists()
    {
        $cmd = 'schtasks /query /tn ' . $this->name;
        exec($cmd, $output, $return_var);
        return $return_var === 0;
    }
}