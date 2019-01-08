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
} else {
    $cmd = 'schtasks /create /tn "LMS Mail Alert Task" /tr "php ' . __FILE__ . ' scheduler" /sc DAILY /st 00:00';
    exec($cmd, $output, $return_var);

//    echo "<pre>";
//    print_r([
//        'output' => $output,
//        'return_var' => $return_var
//    ]);
//    echo "</pre>";
}