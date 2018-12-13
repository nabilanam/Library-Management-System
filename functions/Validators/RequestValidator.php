<?php
require_once __DIR__ . '/../Enums/Status.php';
require_once __DIR__ . '/../Utilities/Database.php';
require_once __DIR__ . '/../Repositories/BooksRepository.php';
require_once __DIR__ . '/../Repositories/UsersRepository.php';
require_once __DIR__ . '/../Repositories/RequestsRepository.php';

$req_repo = new RequestsRepository();

if (isset($_POST['request_id'])
    && isset($_POST['issue_date'])
    && isset($_POST['return_date'])) {

    updateRequest($req_repo);

} elseif (isset($_POST['user_id'])
    && isset($_POST['book_id'])
    && isset($_POST['status_id'])
    && isset($_POST['issue_date'])
    && isset($_POST['return_date'])) {

    if ($_POST['status_id'] == Status::APPROVED){
        $redirect_url = '/issue/';
    } else{
        $redirect_url = '/circulation/history.php';
    }

    if (empty($_POST['user_id'])) {
        setAlert('Please enter user id', 'danger');
        redirectTo(APP_URL_BASE . $redirect_url);
    }
    if (empty($_POST['book_id'])) {
        setAlert('Please enter book id', 'danger');
        redirectTo(APP_URL_BASE . $redirect_url);
    }
    if (empty($_POST['status_id'])) {
        setAlert('Request status missing', 'danger');
        redirectTo(APP_URL_BASE . $redirect_url);
    }
    if ($_POST['status_id'] == Status::APPROVED && empty($_POST['issue_date'])) {
        setAlert('Please enter Issue Date', 'danger');
        redirectTo(APP_URL_BASE . $redirect_url);
    }
    if ($_POST['status_id'] == Status::APPROVED && empty($_POST['return_date'])) {
        setAlert('Please enter Return Date', 'danger');
        redirectTo(APP_URL_BASE . $redirect_url);
    }

    $book_repo = new BooksRepository();
    $user_repo = new UsersRepository();

    $book = $book_repo->findById($_POST['book_id']);
    $user = $user_repo->findById($_POST['user_id']);

    if (!$book) {
        setAlert('No book found with id ' . $_POST['book_id'] . ' !', 'danger');
        redirectTo(APP_URL_BASE . $redirect_url);
    }
    if (!$user) {
        setAlert('No book found with id ' . $_POST['user_id'] . ' !', 'danger');
        redirectTo(APP_URL_BASE . $redirect_url);
    }

    $status = new DTO($_POST['status_id'], null);
    $issue_date = $_POST['issue_date'];
    $return_date = $_POST['return_date'];

    createRequest($req_repo, $book_repo, $book, $user, $status, $issue_date, $return_date, $redirect_url);
}

/**
 * @param RequestsRepository $req_repo
 */
function updateRequest($req_repo)
{

    if (isset($_POST['approve'])) {

        if (empty($_POST['request_id'])) {
            setAlert('Invalid request!', 'danger');
            redirectTo(APP_URL_BASE . '/requests/');
        }

        if (empty($_POST['issue_date'])) {
            setAlert('Please enter Issue Date', 'danger');
            redirectTo(APP_URL_BASE . '/requests/');
        }

        if (empty($_POST['return_date'])) {
            setAlert('Please enter Return Date', 'danger');
            redirectTo(APP_URL_BASE . '/requests/');
        }

        $issue_date = $_POST['issue_date'];
        $return_date = $_POST['return_date'];

        if (subtractDays($issue_date, null) < 0) {
            setAlert('Issue Date can\'t be before Today!', 'danger');
            redirectTo(APP_URL_BASE . '/requests');
        }

        $request = $req_repo->findById($_POST['request_id']);
        if ($request) {

            $book = $request->getBook();
            $day_diff = subtractDays($return_date,$issue_date);
            $day_limit = $request->getUser()->getUserType()->getDayLimit();

            if ($book->getAvailableCopies() == 0) {
                setAlert('Currently book is not available!', 'danger');
                redirectTo(APP_URL_BASE . '/requests');
            }

            if ($day_diff == 0) {
                setAlert('Issue Date and Return Date can\'t be same day!', 'danger');
                redirectTo(APP_URL_BASE . '/requests');
            } elseif ($day_diff < 0) {
                setAlert('Issue Date can\'t be greater than Return Date!', 'danger');
                redirectTo(APP_URL_BASE . '/requests');
            }
            if ($day_diff > $day_limit) {
                setAlert('Date limit exceeded', 'danger');
                redirectTo(APP_URL_BASE . '/requests');
            }
            if ($req_repo->countNonReturnedBooksByUserId($request->getUser()->getId()) >= $request->getUser()->getUserType()->getBookLimit()) {
                setAlert('Sorry! user id ' . $request->getUser()->getId() . ' has reached maximum book limit ' . $request->getUser()->getUserType()->getBookLimit() . ' !', 'danger');
                redirectTo(APP_URL_BASE . '/requests');
            }

            $db = Database::getInstance();
            try {
                $db->beginTransaction();
                $request->setStatus(new DTO(Status::APPROVED, null));
                $request->setIssueDate($issue_date);
                $request->setReturnDate($return_date);
                $req_repo->update($request);

                $book_repo = new BooksRepository();
                $book->setAvailableCopies($book->getAvailableCopies() - 1);
                $book_repo->update($book);
                $db->commit();
            } catch (Exception $e) {
                setAlert('Database error!', 'danger');
                $db->rollback();
            }
        }
        setAlert('Done!','success');
        redirectTo(APP_URL_BASE . '/requests');
    } elseif (isset($_POST['reject'])) {
        $request = $req_repo->findById($_POST['request_id']);
        $request->setStatus(new DTO(Status::REJECTED, null));
        $req_repo->update($request);
        setAlert('Done!','success');
        redirectTo(APP_URL_BASE . '/requests');

    } elseif (isset($_POST['clear'])) {
        $request = $req_repo->findById($_POST['request_id']);
        $request->setUserRead(1);
        if ($request->getStatus()->getId() == Status::PENDING) {
            $request->setStatus(new DTO(Status::CANCELLED, null));
            $req_repo->update($request);
            setAlert('Request cancelled! You can access this request later in Circulation->History','success');
            redirectTo(APP_URL_BASE . '/requests');
        }
        $req_repo->update($request);
        setAlert('Successfully cleared! You can access this request later in Circulation->History','success');
        redirectTo(APP_URL_BASE . '/requests');
    }
    redirectTo(APP_URL_BASE . '/requests');
}

/**
 * @param RequestsRepository $req_repo
 * @param BooksRepository $book_repo
 * @param Book $book
 * @param User $user
 * @param DTO $status
 * @param $issue_date
 * @param $return_date
 */
function createRequest($req_repo, $book_repo, $book, $user, $status, $issue_date, $return_date, $redirect_url)
{

    if ($book->getAvailableCopies() == 0) {
        setAlert('Currently book is not available!', 'danger');
        redirectTo(APP_URL_BASE . $redirect_url);
    }

    if (!empty($issue_date) && !empty($return_date)) {

        $day_diff = subtractDays($return_date,$issue_date);
        $day_limit = $user->getUserType()->getDayLimit();
        if ($day_diff == 0) {
            setAlert('Issue Date and Return Date can\'t be same day!', 'danger');
            redirectTo(APP_URL_BASE . $redirect_url);
        } elseif ($day_diff < 0) {
            setAlert('Issue Date can\'t be greater than Return Date!', 'danger');
            redirectTo(APP_URL_BASE . $redirect_url);
        }
        if ($day_diff > $day_limit) {
            setAlert('Date limit exceeded', 'danger');
            redirectTo(APP_URL_BASE . $redirect_url);
        }
    }

    if ($req_repo->countNonReturnedBooksByUserId($user->getId()) >= $user->getUserType()->getBookLimit()) {
        if (isAdmin()){
            $message = 'Sorry! user id ' . $user->getId() . ' has reached maximum book limit ' . $user->getUserType()->getBookLimit() . ' !';
        }else{
            $message = 'Sorry! You already have reached maximum book limit ' . $user->getUserType()->getBookLimit() . ' !';
        }
        setAlert($message, 'danger');
        redirectTo(APP_URL_BASE . '/requests');
    }

    $request = new Request(
        null,
        $book,
        $user,
        $status,
        getToday(),
        $issue_date,
        $return_date,
        null,
        0,
        0
    );
    $db = Database::getInstance();
    try {
        $db->beginTransaction();

        if ($req_repo->add($request)) {

            $book->setAvailableCopies($book->getAvailableCopies() - 1);
            $book_repo->update($book);
            $db->commit();

            setAlert('Success!', 'success');
            redirectTo(APP_URL_BASE . $redirect_url);
        } else {
            setAlert('Database error!', 'danger');
        }
    } catch (Exception $e) {
        setAlert('Database error!', 'danger');
        $db->rollback();
    }
    redirectTo(APP_URL_BASE . $redirect_url);
}