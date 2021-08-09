<?php
declare( strict_types = 1 );

namespace Zebrands\Catalogue\Contracts;

interface ProductContract
{
    public function getAll(int $page, int $count,  $filter) :array;

    public function getById(int $id) :?object;

    public function firstByFilters(array $filters) :?object;

    public function update($id, array $data) :object;

    public function create(string $name, string $code, int $brand_id, int $country_id, int $stock, float $price, $description, $color) :object;

    public function delete(int $id) :bool;
}