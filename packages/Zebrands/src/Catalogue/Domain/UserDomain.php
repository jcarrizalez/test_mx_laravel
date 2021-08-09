<?php 
declare( strict_types = 1 );
namespace Zebrands\Catalogue\Domain;

use Firebase\JWT\JWT;

use Zebrands\Catalogue\Contracts\UserContract;
use Zebrands\Catalogue\Domain\Shrared\Exception;

class UserDomain
{
    private $user;

    public function __construct(UserContract $user)
    {
        $this->user = $user;
    }

    public function all(int $page, int $count, $filter) :array
    {   
        return $this->user->getAll($page, $count, $filter);
    }

    public function getAllRoot() :array
    {   
        return $this->user->getAllRoot();
    }

    public function infoUser() :object
    {   
        return (object) app('Context')->getAll();
    }

    public function show(int $id) :?object
    {   
        return $this->user->getById($id);
    }

    public function create(object $data) :object
    {   
        $type_user_id = (int) $data->type_user_id;
        
        #si no es Administrador valido la accion
        if(!app('Context')->isAdmin() && $type_user_id == 1 ){
            throw new Exception(4040);
        }
        
        return $this->user->create(
            $data->name,                #name
            $data->username,            #username
            $type_user_id,              #type_user_id
            $data->password             #password
        );
    }

    public function update(int $id, array $data) :object
    {   
        #si no es Administrador valido la accion
        if(!app('Context')->isAdmin()){

            if(app('Context')->getUserId() != $id){
                throw new Exception(4034);
            }
            if($data['type_user_id'] == 1){
                throw new Exception(4040);
            }
        }

        #actualizo
        return $this->user->update($id, $data);
    }

    public function delete(int $id) :bolean
    {
        $this->isAdmin();

        #si no es Administrador valido la accion
        if(!$this->product->delete($id)){
            throw new Exception(4046);
        }

        return true;
    }

    public function login(object $data) :object
    {
        $user =  $this->user->login(
            $data->username,    #username
            $data->password     #password
        );
        if(null === $user){
            throw new Exception(4031);
        }
        $type_user = (object) $user->type_user;

        #asigno el nuevo context
        app('Context')->setUserId($user->id);
        app('Context')->setName($user->name);
        app('Context')->setUsername($user->username);
        app('Context')->setTypeUserId($type_user->id);
        app('Context')->setTypeUserName($type_user->name);
        app('Context')->setLogged(true);

        return $user;
    }
}