<?php 
declare( strict_types = 1 );
namespace Zebrands\Catalogue\Domain;

use Zebrands\Catalogue\Contracts\BrandContract;
use Zebrands\Catalogue\Domain\Shrared\Exception;

class BrandDomain
{
    private $brand;

    public function __construct(BrandContract $brand)
    {
        $this->brand = $brand;
    }

    public function all(int $page, int $count, $filter) :array
    {
        return $this->brand->getAll($page, $count, $filter);
    }

    public function show(int $id) :?object
    {
        return $this->brand->getById($id);
    }

    public function create(object $data) :object
    {
        return $this->brand->create(
            $data->name,                #name
            $data->description??null    #description
        );
    }

    public function update(int $id, array $data) :object
    {
        #valido que no exista el mismo nombre
        if(null !== $brand = $this->brand->firstByFilters([
            'name' => $data['name']
        ])){
            if($id != $brand->id){
                throw new Exception(4041);
            }
        }

        #actualizo
        return $this->brand->update($id, $data);
    }
}