<?php

interface Pagination
{
    public function getPaginated($to, $limit);
}