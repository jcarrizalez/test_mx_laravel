<?php
declare( strict_types = 1 );
namespace Zebrands\Catalogue\Database\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Zebrands\Catalogue\Database\Models\TypeUser;

class User extends Model
{
    use SoftDeletes;
 
    protected $dates = ['deleted_at'];

 	protected $fillable = [
        'name',
		'username',
		'password',
		'type_user_id',
		'updated_at',
	];

    protected $hidden = [
		'type_user_id',
		'password',
    	'created_at',
    	'updated_at',
		'deleted_at',
    ];

    protected $with = ['type_user'];

    public function type_user()
    {
        return $this->belongsTo(TypeUser::class, 'type_user_id', 'id');
    }
}