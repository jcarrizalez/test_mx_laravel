<?php
declare( strict_types = 1 );
namespace Zebrands\Catalogue\Repositories;

use Zebrands\Catalogue\Database\Models\User;

use Zebrands\Catalogue\Contracts\UserContract;
use Zebrands\Catalogue\Database\Paginator\EloquentPaginator;

class UserRepository implements UserContract
{
    private $model;
    private $paginator;

    public function __construct(
        User $user,
        EloquentPaginator $paginator
    )
    {
        $this->model     = $user;
        $this->paginator = $paginator;
    }

    private function encryt($str)
    {
        return md5($str);
    }

    public function getAll(int $page, int $count,  $filter) :array
    {   
        $model = $this->model::query();

        if($filter!=''){
            $model->where('username', 'like', "%$filter%")
                ->orWhereIn('users.type_user_id',function($query) use ($filter)
                {
                    $query->select('id')->from('type_users')
                    ->where('name', 'LIKE', "%{$filter}%");
                });
        }
        return $this->paginator->paginate($model, $count, $page);
    }

    public function getAllRoot() :array
    {   
        return $this->model::where('type_user_id', 1)->get()->toArray();
    }

    public function getById(int $id) :?object
    {
        if(null === $model = $this->model::find($id)){
            return null;
        }
        return (object) $model->toArray();
    }

    public function update($id, array $data) :object
    {
        $model = $this->model::findOrFail($id);
        
        if($data['password']??null) $model->makeVisible('password');
        if($data['type_user_id']??null) $model->makeVisible('type_user_id');
        
        foreach ($model->toArray() as $key => $value) {
            
            if($key=='id') continue;

            if(array_key_exists($key, $data)){

                $model->{$key} = ($key=='password')? md5($data[$key]): $data[$key];
            }
        }
        $model->save();
        if($data['password']??null) $model->makeHidden('password');
        if($data['type_user_id']??null) $model->makeHidden('type_user_id');
        $model->refresh();
        return (object) $model->toArray();
    }

    public function create(string $name, string $username, int $type_user_id, $password) :object
    {
        $model                  = new $this->model;
        $model->name            = ucfirst($name);
        $model->username        = $username;
        $model->type_user_id    = $type_user_id;
        $model->password        = md5($password);
        $model->updated_at      = null;
        $model->save();
        return $this->getById($model->id);
    }

    public function delete(int $id) :bool
    {
        if(null === $model = $this->model::find($id)){
            return false;
        }
        $model->delete();
        return true;
    }

    public function login(string $username, $password) :?object
    {
        if(null === $model = $this->model::where('username', $username)
            ->where('password', $this->encryt($password))->first()){
            return null;
        }
        return (object) $model->toArray();
    }
}