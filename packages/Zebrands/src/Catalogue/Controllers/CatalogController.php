<?php
declare( strict_types = 1 );
namespace Zebrands\Catalogue\Controllers;

use Illuminate\Routing\Controller;

use Zebrands\Catalogue\Requests\CatalogRequest;
use Zebrands\Catalogue\Domain\ProductDomain;
use Zebrands\Catalogue\Domain\BrandDomain;
use Zebrands\Catalogue\Domain\UserDomain;

class CatalogController extends Controller
{
    protected $request;
    protected $product;
    protected $brand;
    protected $user;

    public function __construct(
        CatalogRequest $request,
        ProductDomain $product,
        BrandDomain $brand,
        UserDomain $user
    ){
        $this->request  = $request;
        $this->page     = (int) ($request->get('page')??1);
        $this->count    = (int) ($request->get('count')??20);
        $this->filter   = (string) $request->get('filter');
        
        $this->product  = $product;
        $this->brand    = $brand;
        $this->user     = $user;
    }

    /**
    * Productos
    */

    public function index_products()
    {   
        $this->request->validator(__FUNCTION__);

        return jsend_success(
            $this->product->all(
                $this->page,   #page
                $this->count,  #count 
                $this->filter  #filter
            )
        );
    }

    public function show_product(int $id)
    {
        return jsend_success($this->product->show($id));
    }

    public function create_product()
    {
        $this->request->validator(__FUNCTION__);

        return jsend_success( $this->product->create($this->request->toJson()) );
    }

    public function update_product(int $id)
    {
        $this->request->validator(__FUNCTION__);

        return jsend_success($this->product->update(
            $id, 
            $this->request->toArray())
        );
    }

    public function delete_product(int $id)
    {
        return jsend_success($this->product->delete($id));
    }



    /**
    * Marcas
    */

    public function index_brands()
    {   
        $this->request->validator(__FUNCTION__);

        return jsend_success(
            $this->brand->all(
                $this->page,   #page
                $this->count,  #count 
                $this->filter  #filter
            )
        );
    }

    public function show_brand(int $id)
    {
        return jsend_success($this->brand->show($id));
    }

    public function create_brand()
    {
        $this->request->validator(__FUNCTION__);

        return jsend_success( $this->brand->create($this->request->toJson()) );
    }

    public function update_brand(int $id)
    {
        $this->request->validator(__FUNCTION__);

        return jsend_success($this->brand->update(
            $id, 
            $this->request->toArray())
        );
    }

    public function delete_brand(int $id)
    {
        return jsend_success($this->brand->delete($id));
    }

    /**
    * Usuarios
    */

    public function index_users()
    {   
        $this->request->validator(__FUNCTION__);

        return jsend_success(
            $this->user->all(
                $this->page,   #page
                $this->count,  #count 
                $this->filter  #filter
            )
        );
    }

    public function show_user(int $id)
    {
        return jsend_success($this->user->show($id));
    }

    public function create_user()
    {
        $this->request->validator(__FUNCTION__);

        return jsend_success( $this->user->create($this->request->toJson()) );
    }

    public function update_user(int $id)
    {
        $this->request->validator(__FUNCTION__);

        return jsend_success($this->user->update(
            $id, 
            $this->request->toArray())
        );
    }

    public function delete_user(int $id)
    {
        return jsend_success($this->user->delete($id));
    }



}