<?php
require_once 'SimpleRepository.php';

class SimpleRepositoryFacade
{
    public static function getAuthorsRepository()
    {
        return new SimpleRepository('book_authors');
    }

    public static function getCategoriesRepository()
    {
        return new SimpleRepository('book_categories');
    }

    public static function getConditionsRepository()
    {
        return new SimpleRepository('book_conditions');
    }

    public static function getPublishersRepository()
    {
        return new SimpleRepository('book_publishers');
    }

    public static function getShelvesRepository()
    {
        return new SimpleRepository('book_shelves');
    }

    public static function getSourcesRepository()
    {
        return new SimpleRepository('book_sources');
    }

    public static function getUserGendersRepository()
    {
        return new SimpleRepository('user_genders');
    }
}