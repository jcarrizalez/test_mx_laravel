<?php 
declare( strict_types = 1 );
namespace Zebrands\Catalogue\Domain;

use Zebrands\Catalogue\Contracts\ProductContract;
use Zebrands\Catalogue\Contracts\UserContract;
use Zebrands\Catalogue\Domain\Shrared\Exception;
use Zebrands\Catalogue\Domain\Mails\NotifyUsersRootMail;
use Zebrands\Catalogue\Domain\UserDomain;

class ProductDomain
{
    private $product;
    private $user;
    private $notify_users_root;

    public function __construct(
        ProductContract $product,
        UserDomain $user,
        NotifyUsersRootMail $notify_users_root
    )
    {
        $this->product              = $product;
        $this->user                 = $user;
        $this->notify_users_root    = $notify_users_root;
    }

    public function all(int $page, int $count, $filter) :array
    {
        return $this->product->getAll($page, $count, $filter);
    }

    public function show(int $id) :?object
    {
        return $this->product->getById($id);
    }

    public function create(object $data) :object
    {
        $this->isAdmin();

        #valido que no exista el mismo code, pasar esto a cache
        if(null !== $product = $this->product->firstByFilters([
            'code' => $data->code,
            'name' => $data->name,
        ])){
            #si no es el codigo
            if((int)$data->code == (int)$product->code){
                throw new Exception(4044);
            }

            #si no es el nombre en un pais y marca
            if(ucfirst($data->name) == $product->name && 
                ucfirst($data->color) == $product->color &&
                $data->brand_id == $product->brand['id'] &&
                $data->country_id == $product->country['id']
            ){
                throw new Exception(4042);
            }
        }
       
        return $this->product->create(
            $data->name,                 #name
            (string) $data->code,        #code
            (int) $data->brand_id,       #brand_id
            (int) $data->country_id,     #country_id
            $data->stock??0,             #stock
            (double) ($data->price??0),  #price
            $data->description??null,    #description
            $data->color??null           #color
        );
    }

    public function update(int $id, array $data) :object
    {
        $this->isAdmin();

        #valido que no exista el mismo code, pasar esto a cache
        if(null !== $product = $this->product->firstByFilters([
            'name' => $data['name'],
            'code' => $data['code'],
        ])){
            #si no es el codigo
            if($id != $product->id && (int)$data->code == (int)$product->code){
                throw new Exception(4044);
            }

            #si no es el nombre en un pais y marca
            if($id != $product->id && 
                ucfirst($data->name) == $product->name && 
                ucfirst($data->color) == $product->color &&
                $data->brand_id == $product->brand['id'] &&
                $data->country_id == $product->country['id']
            ){
                throw new Exception(4042);
            }
        }

        #actualizo
        $product =  $this->product->update($id, $data);

        #consulto usuarios admin
        $users = $this->user->getAllRoot();
        
        #consulto usuario current
        $info = $this->user->infoUser();

        foreach ($users as $user) {
            $user = (object) $user;
            if(strpos($user->username, '@') === false) continue;
            $this->notify_users_root->make(
                $user->name, 
                $user->username, 
                $info, 
                (object) $data
            );
        }

        return $product;
    }

    public function delete(int $id) :bool
    {
        $this->isAdmin();

        #si no es Administrador valido la accion
        if(!$this->product->delete($id)){
            throw new Exception(4045);
        }

        return true;
    }

    private function isAdmin() :void
    {
        #si no es Administrador valido la accion
        if(!app('Context')->isAdmin()){
            throw new Exception(4034);
        }
    }
}