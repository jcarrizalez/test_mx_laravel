<?php
declare( strict_types = 1 );
namespace Zebrands\Catalogue\Repositories;

use Zebrands\Catalogue\Database\Models\Product;

use Zebrands\Catalogue\Contracts\ProductContract;
use Zebrands\Catalogue\Database\Paginator\EloquentPaginator;

class ProductRepository implements ProductContract
{
    private $model;
    private $paginator;

    public function __construct(
        Product $product,
        EloquentPaginator $paginator
    )
    {
        $this->model     = $product;
        $this->paginator = $paginator;
    }

    private function pad_code($code) :string
    {
        return str_pad("$code", 13, "0", STR_PAD_LEFT);;
    }

    public function getAll(int $page, int $count,  $filter) :array
    {   
        $model = $this->model::query();

        if($filter!=''){
            $model->where('name', 'like', "%$filter%")
                ->orWhere('code', 'like', "%$filter%")
                ->orWhere('color', 'like', "%$filter%")
                ->orWhere('description', 'like', "%$filter%")
                ->orWhereIn('products.brand_id',function($query) use ($filter)
                {
                    $query->select('id')->from('brands')
                    ->where('name', 'LIKE', "%{$filter}%");
                })
                ->orWhereIn('products.country_id',function($query) use ($filter)
                {
                    $query->select('id')->from('countries')
                    ->where('name', 'LIKE', "%{$filter}%");
                });
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

    /**
    * Metodo que trae por OrWhere todos los filters seteados
    */
    public function firstByFilters(array $filters) :?object
    {   
        if(isset($filters['code'])){
            $filters['code'] = $this->pad_code($filters['code']);
        }
        if(null === $model = $this->model::firstByFilters($filters)->first()){
            return null;
        }
        return (object) $model->toArray();
    }


    public function getByName(string $name) :?object
    {
        if(null === $model = $this->model::where('name', $name)->first()){
            return null;
        }
        return (object) $model->toArray();
    }

    public function update($id, array $data) :object
    {
        $model = $this->model::findOrFail($id);

        if($data['country_id']??null) $model->makeVisible('country_id');
        if($data['brand_id']??null) $model->makeVisible('brand_id');

        foreach ($model->toArray() as $key => $value) {
            
            if($key=='id') continue;
            
            if(array_key_exists($key, $data)){
                $model->{$key} = $data[$key];
            }
        }
        $model->save();
        if($data['country_id']??null) $model->makeHidden('country_id');
        if($data['brand_id']??null) $model->makeHidden('brand_id');
        $model->refresh();
        return (object) $model->toArray();
    }

    public function create(string $name, string $code, int $brand_id, int $country_id, int $stock, float $price, $description, $color) :object
    {
        $model                  = new $this->model;
        $model->name            = ucfirst($name);
        $model->code            = $this->pad_code($code);
        $model->brand_id        = $brand_id;
        $model->country_id      = $country_id;
        $model->description     = $description;
        $model->color           = is_null($color)? null: ucfirst($color);
        $model->stock           = is_null($stock)? 0 : $stock;
        $model->price           = is_null($price)? 0 : $price;
        $model->updated_at      = null;
        $model->save();
        return (object) $model->toArray();
    }

    public function delete(int $id) :bool
    {
        if(null === $model = $this->model::find($id)){
            return false;
        }
        $model->delete();
        return true;
    }
}