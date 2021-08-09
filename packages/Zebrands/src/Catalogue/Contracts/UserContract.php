<?php
declare( strict_types = 1 );

namespace Zebrands\Catalogue\Contracts;

interface UserContract
{
    public function getAll(int $page, int $count,  $filter) :array;
    
    public function getAllRoot() :array;

    public function getById(int $id) :?object;

    public function update(int $id, array $data) :object;

    public function create(string $name, string $username, int $type_user_id, $password) :object;
    
    public function login(string $username, $password) :?object;

    public function delete(int $id) :bool;
}