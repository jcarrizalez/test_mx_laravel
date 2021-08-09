<?php
declare( strict_types = 1 );
namespace Zebrands\Catalogue\Database\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Zebrands\Catalogue\Database\Models\Brand;
use Zebrands\Catalogue\Database\Models\Country;

class Product extends Model
{
    use SoftDeletes;
 
    protected $dates = ['deleted_at'];

 	protected $fillable = [
		'code', 
		'name', 
		'description',
		'stock',
		'color',
		'price',
		'country_id',
		'brand_id',
		'updated_at',
	];

    protected $hidden = [
    	'created_at', 
    	'updated_at',
    	'deleted_at',
    	'country_id',
		'brand_id',
    ];

    protected $with = ['brand', 'country'];

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    public function scopeFirstByFilters($query, array $filters)
    {
        $init = true;
    	foreach ($filters as $column => $value) {

            if($init){
                $query->where($column, $value);
            }
            else{
                $query->orWhere($column, $value);
            }
            $init = false;
        }
        return $query;
    }
}