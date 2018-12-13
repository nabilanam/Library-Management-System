<?php
require_once __DIR__.'/../Repositories/RequestsRepository.php';

$repo = new RequestsRepository();
if (isset($_POST['calculate1'])
    && isset($_POST['request_id'])) {

    if (empty($_POST['request_id'])){
        setAlert('Please enter request id!','danger');
        redirectTo(APP_URL_BASE . '/issue/return.php');
    }

    $_SESSION['req_id'] = $id = $_POST['request_id'];
    $request = $repo->findById($id);
    if ($request) {
        if ($request->getStatus()->getId() == Status::APPROVED) {
            $fine = 0;
            $return_date = $request->getReturnDate();
            $receive_date = getToday();
            $diff = subtractDays($receive_date, $return_date);
            if ($diff > 0) {
                $fine_per_day = $request->getUser()->getUserType()->getFinePerDay();
                $fine = $diff * $fine_per_day;
            }
            $_SESSION['fine'] = $fine;
            redirectTo(APP_URL_BASE . '/issue/return.php');
        } else {
            setAlert('Request is not successful! Previous status: ' . $request->getStatus()->getName() . '!', 'warning');
            redirectTo(APP_URL_BASE . '/issue/return.php');
        }
    } else {
        setAlert('Record not found!', 'warning');
        redirectTo(APP_URL_BASE . '/issue/return.php');
    }
} elseif (isset($_POST['calculate2'])
    && isset($_POST['user_id'])
    && isset($_POST['book_id'])) {

    if (empty($_POST['user_id'])) {
        setAlert('Please enter User ID', 'danger');
        redirectTo(APP_URL_BASE . '/issue/return.php');
    }
    if (empty($_POST['book_id'])) {
        setAlert('Please enter Book ID', 'danger');
        redirectTo(APP_URL_BASE . '/issue/return.php');
    }
    if (empty($_POST['issue_date'])) {
        setAlert('Please enter Issue Date', 'danger');
        redirectTo(APP_URL_BASE . '/issue/return.php');
    }

    $request = $repo->findByUserIdBookIdIssueDate($_POST['user_id'], $_POST['book_id'], $_POST['issue_date']);
    if ($request) {
        if ($request->getStatus()->getId() == Status::APPROVED) {
            $fine = 0;
            $return_date = $request->getReturnDate();
            $receive_date = getToday();
            $diff = subtractDays($receive_date, $return_date);
            if ($diff > 0) {
                $fine_per_day = $request->getUser()->getUserType()->getFinePerDay();
                $fine = $diff * $fine_per_day;
            }
        } else {
            setAlert('Request is not successful! Previous status: ' . $request->getStatus()->getName() . '!', 'warning');
            redirectTo(APP_URL_BASE . '/issue/return.php');
        }
    } else {
        setAlert('Record not found!', 'warning');
        redirectTo(APP_URL_BASE . '/issue/return.php');
    }
}

if (isset($_POST['completed']) && isset($_POST['fee']) && isset($_POST['request_id'])) {
    $fee = $_POST['fee'];
    if ($fee != 0 && empty($fee)) {
        setAlert('Fee undefined!', 'danger');
        redirectTo(APP_URL_BASE . '/issue/return.php');
    }
    $request = $repo->findById($_POST['request_id']);
    if ($request) {
        $request->setTotalFine($fee);
        $request->setStatus(new DTO(Status::RETURNED, null));
        $request->setReceiveDate(getToday());
        if ($repo->update($request)) {
            setAlert('Success!', 'success');
            redirectTo(APP_URL_BASE . '/issue/return.php');
        }
        setAlert('Database error! Please try later!', 'danger');
        redirectTo(APP_URL_BASE . '/issue/return.php');
    }
    setAlert('Record not found!', 'warning');
    redirectTo(APP_URL_BASE . '/issue/return.php');
} elseif(isset($_POST['cancelled'])){
    redirectTo(APP_URL_BASE . '/issue/return.php');
}