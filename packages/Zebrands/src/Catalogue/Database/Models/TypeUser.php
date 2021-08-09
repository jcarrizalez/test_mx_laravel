<?php
declare( strict_types = 1 );
namespace Zebrands\Catalogue\Database\Models;

use Illuminate\Database\Eloquent\Model;

class TypeUser extends Model
{
 	protected $fillable = [
	];

    protected $hidden = [
    	'created_at', 
    	'updated_at',
    ];
}