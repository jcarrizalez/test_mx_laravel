<?php
declare( strict_types = 1 );
namespace Zebrands\Catalogue\Laravel;

use Illuminate\Http\Response;

class Exception extends \Exception
{
    protected $data;

    public function __construct($error, $cause)
    {
        $this->error   = $error;
        $this->cause   = $cause;
        $code          = $error;
        $message = 'Exception NO Message';
        foreach ($this->errors as $code_http => $data_error) {
            
            if(array_key_exists($error, $data_error)){
                $message = $data_error[$error];
                $code = $code_http;
            }
        }
        unset($this->errors);
        parent::__construct($message, $code);
    }
    public function getError()
    {
        return $this->error;
    }

    public function getCause()
    {
        return $this->cause? $this->cause : null;
        #return $this->cause? ['cause'=>$this->cause] : null;
    }

    public static function extractInfo($e)
    {
        $code = method_exists($e, 'getCode')? $e->getCode() : null;
        $code = ($code==0)? 500 : $code;

        if(env('APP_DEBUG')!==true){

            if($code==500){
                return jsend_error(
                    'Ocurrio un Error, comunicate con soporte', 
                    null, 
                    'https://www.youtube.com/watch?v=RfiQYRn7fBg&ab_channel=PhilWylie',
                    500
                 );
            }
            return jsend_error(
                method_exists($e, 'getMessage')? $e->getMessage() : null, 
                $e->getError()??null, 
                method_exists($e, 'getCause')? $e->getCause() : null,
                $code
             );
        }

        $line  = method_exists($e, 'getLine')? $e->getLine() : null;
        return (object)[
            'code'    => $code,
            'error'   => method_exists($e, 'getError')? $e->getError() : null,
            'file'    => method_exists($e, 'getFile')? $e->getFile().':'.$line : null,
            'message' => method_exists($e, 'getMessage')? $e->getMessage() : null,
            'cause'   => method_exists($e, 'getCause')? $e->getCause() : null,
        ];
    }

    public static function debugger($request, \Exception $e)
    {   
        $data = self::extractInfo($e);

        if(env('APP_DEBUG')!==true){
            return $data;
        }

        if(env('APP_DEBUG_BYDD')!==true){
            return $request;
        }

        if(method_exists($e, 'getPrevious')){
            $e = $e->getPrevious();
            if($e instanceof \ParseError){
                $data = self::extractInfo($e);
            }
        }
        dd($data);
    }
}