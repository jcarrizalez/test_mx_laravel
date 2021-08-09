<?php
declare( strict_types = 1 );
namespace Zebrands\Catalogue\Database\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
 	protected $fillable = [
		'updated_at',
		'deleted_at',
	];

    protected $hidden = [
    	'created_at', 
    	'updated_at',
    ];
}