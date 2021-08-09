<?php
declare( strict_types = 1 );
namespace Zebrands\Catalogue\Laravel;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request AS BaseRequest;

use Zebrands\Catalogue\Laravel\Exception;

class Request
{
    public  $request;
    private $contract;

    public function __construct(RequestRepository $contract)
    {   
        $this->request      = $contract->request;
        $this->contract     = $contract;

        foreach ($this->request->toArray() as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function validator(string $function) :bool
    {
        return $this->{$function}();
    }

    public function validate(array $rules) :bool
    {
        return $this->contract->validate($rules);
    }

    public function get(string $key)
    {
        return $this->request->get($key);
    }

    public function input(string $key)
    {
        return $this->request->input($key);
    }

    public function has(string $key)
    {
        return $this->request->has($key);
    }

    public function all() :array
    {
        return $this->request->all();
    }

    public function toArray() :array
    {
        return $this->all();
    }

    public function toJson() :object
    {
        return (object) $this->all();
    }

    public function header($key=null)
    {
        return $this->request->header($key);
    }
}

class MiRequestException extends Exception
{
    protected $errors   = [
        400 => [
            4000 => 'Params'
        ]
    ];
    public function __construct(int $error=0, $data=null)
    {
        parent::__construct($error, $data);
    }
}

class RequestRepository
{
    public $request;
    public $framework = 'laravel';

    public function __construct(BaseRequest $request)
    {   
        $this->request   = $request;
    }

    public function toArray() :array
    {
        return  $this->request->all();
    }

    public function validate(array $rules) :bool
    {
        $validator = Validator::make($this->request->all(), $rules);

        if(!$validator->fails()) {
            return true;
        }

        #Log::error(json_encode($validator->errors()));

        throw new MiRequestException(4000, $validator->errors() );
    }
}