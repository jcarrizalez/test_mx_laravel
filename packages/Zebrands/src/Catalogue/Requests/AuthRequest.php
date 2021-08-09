<?php
declare( strict_types = 1 );
namespace Zebrands\Catalogue\Requests;

#use Base\Request;
use Zebrands\Catalogue\Laravel\Request;

class AuthRequest extends Request
{
    public function auth() :bool
    {
        return $this->validate([
            'api_key' => 'required|string|min:4',
            'api_secret' => 'required|string|min:4',
        ]);
    }
    
    public function login() :bool
    {   
        $rule = ($this->request->get('username')=='root')?'string' :'email:rfc,dns';

        return $this->validate([
            'username' => "required|{$rule}|min:4|max:100",
            'password' => 'required|string|min:4',
        ]);
    }
}