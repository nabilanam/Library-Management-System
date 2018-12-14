<?php
require_once __DIR__ . '/../Repositories/UsersRepository.php';
require_once __DIR__ . '/../Repositories/BooksRepository.php';
require_once __DIR__ . '/../Repositories/RequestsRepository.php';
require_once __DIR__ . '/../Repositories/MailsRepository.php';

class Statistics
{
    private $users_repo;
    private $books_repo;
    private $mails_repo;
    private $request_repo;

    public function __construct()
    {
        $this->users_repo = new UsersRepository();
        $this->books_repo = new BooksRepository();
        $this->mails_repo = new MailsRepository();
        $this->request_repo = new RequestsRepository();
    }

    function totalBooksBorrowed()
    {
        return $this->request_repo->totalApprovedReturnedLostBooks();
    }

    function totalPendingRequests()
    {
        return $this->request_repo->totalPendingBooks();
    }

    function totalNonReturnedBooks()
    {
        return $this->request_repo->totalNonReturnedBooks();
    }

    function totalUsers(){
        return $this->users_repo->totalRecords();
    }

    function totalUsersActivatedThisMonth()
    {
        return $this->users_repo->totalActivatedRecordsThisMonth();
    }

    function totalBooks(){
        return $this->books_repo->totalRecords();
    }

    function totalBooksBorrowedThisMonth(){
        return $this->request_repo->totalApprovedReturnedLostBooksThisMonth();
    }

    function totalEmailsSentThisMonth(){
        return $this->mails_repo->totalRecordsThisMonth();
    }

    function totalRegistrationThisMonth(){

    }

    public function totalBooksBorrowedByUser()
    {
        return $this->request_repo->totalApprovedReturnedLostBooksByUser(getUser()['id']);
    }

    public function totalPendingRequestsByUser()
    {
        return $this->request_repo->totalPendingBooksByUserId(getUser()['id']);
    }

    public function totalNonReturnedBooksByUser()
    {
        return $this->request_repo->totalNonReturnedBooksByUserId(getUser()['id']);
    }
}