<?php
declare( strict_types = 1 );
namespace Zebrands\Catalogue\Requests;

#use Base\Request;
use Zebrands\Catalogue\Laravel\Request;

class CatalogRequest extends Request
{
    public function index_products() :bool
    {
        return $this->validate([
            'page' => 'string|min:1',
            'count' => 'string|min:1',
            'filter' => 'string|max:100',
        ]);
    }
    
    public function create_product() :bool
    {   
        return $this->validate([
            'name' => 'required|string|min:4|max:20',
            'code' => 'required|string|min:4|max:13|unique:products,code',
            'description' => 'string|max:200',
            'country_id' => 'required|integer|exists:countries,id',
            'brand_id' => 'required|integer|exists:brands,id',
            'color' => 'string|max:20',
            'stock' => 'integer',
            'price' => 'numeric',
        ]);
    }

    public function update_product() :bool
    {   
        return $this->validate([
            'name' => 'required|string|min:4|max:20',
            'description' => 'string|max:200',
            'country_id' => 'required|integer|exists:countries,id',
            'brand_id' => 'required|integer|exists:brands,id',
            'color' => 'string|max:20',
            'stock' => 'integer',
        ]);
    }

    public function index_brands() :bool
    {
        return $this->validate([
            'page' => 'string|min:1',
            'count' => 'string|min:1',
            'filter' => 'string|max:100',
        ]);
    }

    public function create_brand() :bool
    {   
        return $this->validate([
            'name' => 'required|string|min:4|unique:brands,name',
            'description' => 'string|max:200',
        ]);
    }

    public function update_brand() :bool
    {   
        return $this->validate([
            'name' => 'required|string|min:4',
            'description' => 'string|max:200',
        ]);
    }


    public function index_users() :bool
    {
        return $this->validate([
            'page' => 'string|min:1',
            'count' => 'string|min:1',
            'filter' => 'string|max:100',
        ]);
    }

    public function create_user() :bool
    { 
        return $this->validate([
            'username' => 'required|email:rfc,dns|min:4|max:100|unique:users,username',
            'name' => 'required|string|min:4|max:20',
            'type_user_id' => 'required|exists:type_users,id',
            'password' => 'required|min:6',
            'password_confirmation' => 'required_with:password|same:password|min:6'
        ]);
    }

    public function update_user() :bool
    {   
        return $this->validate([
            'type_user_id' => 'required|exists:type_users,id',
            'name' => 'string|min:4|max:100',
            'password' => 'min:6',
            'password_confirmation' => 'required_with:password|same:password|min:6'
        ]);
    }
}