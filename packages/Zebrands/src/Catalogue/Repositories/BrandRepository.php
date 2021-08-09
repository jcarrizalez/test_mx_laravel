<?php
declare( strict_types = 1 );
namespace Zebrands\Catalogue\Repositories;

use Zebrands\Catalogue\Database\Models\Brand;

use Zebrands\Catalogue\Contracts\BrandContract;
use Zebrands\Catalogue\Database\Paginator\EloquentPaginator;

class BrandRepository implements BrandContract
{
    private $model;
    private $paginator;

    public function __construct(
        Brand $brand,
        EloquentPaginator $paginator
    )
    {
        $this->model     = $brand;
        $this->paginator = $paginator;
    }

    public function getAll(int $page, int $count,  $filter) :array
    {   
        $model = $this->model::query();

        if($filter!=''){
            $model->where('name', 'like', "%$filter%")
                ->orWhere('description', 'like', "%$filter%");
        }
        return $this->paginator->paginate($model, $count, $page);
    }

    public function getById(int $id) :?object
    {
        if(null === $model = $this->model::find($id)){
            return null;
        }
        return (object) $model->toArray();
    }

    public function firstByFilters(array $filters) :?object
    {
        if(null === $model = $this->model::search($filters)->first()){
            return null;
        }
        return (object) $model->toArray();
    }

    public function update($id, array $data) :object
    {
        $model = $this->model::findOrFail($id);

        foreach ($model->toArray() as $key => $value) {
            
            if($key=='id') continue;
            
            if(array_key_exists($key, $data)){
                $model->{$key} = $data[$key];
            }
        }
        $model->save();
        return (object) $model->toArray();
    }

    public function create(string $name, $description) :object
    {
        $model                  = new $this->model;
        $model->name            = $name;
        $model->description     = $description;
        $model->updated_at      = null;
        $model->save();
        return (object) $model->toArray();
    }
}