<?php
require_once 'JoinedRepository.php';

class MailsUsersRepository extends JoinedRepository
{

    public function __construct()
    {
        parent::__construct('mails_users');
    }

    public function join($first_table_id, $second_table_id)
    {
        // TODO: Implement join() method.
    }

    public function remove($first_table_id, $second_table_id)
    {
        // TODO: Implement remove() method.
    }

    public function isJoinExist($first_table_id, $second_table_id)
    {
        // TODO: Implement isJoinExist() method.
    }

    public function findFirst($second_table_id)
    {
        // TODO: Implement findFirst() method.
    }

    public function findSecond($first_table_id)
    {
        // TODO: Implement findSecond() method.
    }
}