<?php
declare( strict_types = 1 );

namespace Zebrands\Catalogue\Contracts;

interface BrandContract
{
    public function getAll(int $page, int $count,  $filter) :array;

    public function getById(int $id) :?object;

    public function firstByFilters(array $filters) :?object;

    public function update($id, array $data) :object;

    public function create(string $name, $description) :object;
}