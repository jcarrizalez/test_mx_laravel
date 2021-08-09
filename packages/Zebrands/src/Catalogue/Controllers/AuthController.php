<?php
declare( strict_types = 1 );
namespace Zebrands\Catalogue\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;

use Zebrands\Catalogue\Requests\AuthRequest;
use Zebrands\Catalogue\Domain\UserDomain;

/**
 * @group Application Auth
 *
 * Autenticación de la aplicación, necesaria para empezar a interactuar con la api.
 */
class AuthController extends Controller
{

    protected $request;
    protected $user;

    public function __construct(AuthRequest $request, UserDomain $user)
    {
        $this->request  = $request;
        $this->user     = $user;
    }

    /**
     * @bodyParam api_key string required key de la aplicación.
     * @bodyParam api_secret string required El secret de la aplicación.
     * @bodyParam versión integer required Versión definida de la aplicación.
     */
    public function auth()
    {
        $this->request->validator(__FUNCTION__);
        app('Context')->validate();

        return jsend_success([
            'token' => app('Context')->getJwt(), 
            'timestamp' => time()
        ]);

    }

    public function ping()
    {
        return jsend_success([
            'token' => app('Context')->getJwt()
        ]);
    }

    /**
    * @bodyParam username string required Username del usuario.
    * @bodyParam password string required Password del usuario..
    */
    public function login()
    { 
        $this->request->validator(__FUNCTION__);

        return jsend_success( $this->user->login($this->request->toJson()) );
    }

    /**
     * @return {Array} JTW con un token sin datos de usuarios
    */
    public function logout(Request $request) {
        
        $context = app('Context');
        $context->logout();

        $request->replace([
            'api_key' => $context->getApiKey(),
            'api_secret' => $context->getApiSecret()
        ]);

        return $this->auth($request);
    }
}