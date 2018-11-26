<?php
require_once __DIR__ . '/../init.php';

interface Repository
{
    public function add($object);

    public function remove($id);

    public function find($object);

    public function findById($id);

    public function findOrInsert($object);

    public function update($object);

    public function getAll();

    public function getNextAutoIncrement();
}