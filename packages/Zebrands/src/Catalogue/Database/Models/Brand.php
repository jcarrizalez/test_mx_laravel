<?php
declare( strict_types = 1 );
namespace Zebrands\Catalogue\Database\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
 	protected $fillable = [
		'name',
		'description',
		'updated_at',
	];

    protected $hidden = [
    	'created_at', 
    	'updated_at',
    ];

    public function scopeSearch($query, array $filters)
    {
    	foreach ($filters as $column => $value) {
            $query->where($column, $value);
        }
        return $query;
    }
}