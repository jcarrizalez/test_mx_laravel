<?php 
declare( strict_types = 1 );
namespace Zebrands\Catalogue\Domain;

use Illuminate\Http\Request;
use Firebase\JWT\JWT;

use Zebrands\Catalogue\Database\Models\Application;
use Zebrands\Catalogue\Domain\Shrared\Exception;

class ContextDomain {

	public $api_key;
	public $api_secret;
	public $logged;
	public $user_id;
	public $name;
	public $username;
	public $type_user_id;
	public $type_user_name;


	public function __construct(Request $request){

		\Log::debug('Context created');

		$parameters = $request->all();

		foreach ($parameters as $parameter => $value) {
			if(property_exists($this, $parameter)){
				$this->{$parameter} = $value;
			}
		}
	}

	public function getAll() {
		return array_filter(get_object_vars($this));
	}

	public function isAdmin() {
		return ($this->type_user_id==1)? true: false;
	}

	public function isLogged() {
		return $this->logged;
	}

	public function setLogged() {
		$this->logged =true;
		return $this;
	}

	public function requireLogin() {
		if(!$this->isLogged()){
			throw new Exception(4032);
		}
		return true;
	}

	public function addJwt($jwt){

		try{
			//Convert Error 500-401 per token expired
			$jwtValues = JWT::decode($jwt, config('jwt.secret'), array('HS256'));
		}
		catch(\Exception $e){
			throw new Exception(4033);
		}

		foreach ($jwtValues as $property => $value) {
			if(property_exists($this, $property)){
				$this->{$property} = $value;
			}
		}
		$this->validate();
	}

	public function validate() {

		\Log::debug('Validating context');

	    $api_id = $this->getApiKey();
	    $api_secret = $this->getApiSecret();

	  	//APP PARA VALIDAR CONEXION
	  	$app = \Cache::rememberForever('jc1apk_'.$api_id.$api_secret, function() use($api_id, $api_secret){
			return Application::where('apk_id', $api_id )->where('apk_secret', $api_secret)->first();
		});

		if(!$app){
			throw new Exception(4013);
		}

		$this->setApiKey($app->apk_id);
		$this->setApiSecret($app->apk_secret);

		// Validar ApiKey
		if($this->getApiKey() != $app->apk_id) {
			throw new Exception(4014);
		}
		// Validar Secret
		if($this->getApiSecret() != $app->apk_secret){
		
			throw new Exception(4015);
		}
		
		return true;
	}

	public function getJWT($override = []){

		$payload = [
            'iss' => "zebrands", // Issuer of the token
            'iat' => time(), // Time when JWT was issued. 
            'exp' => time() + 60*60 // Expiration time
        ];

        $properties = get_object_vars($this);
		foreach ($properties as $property => $value) {
			if(isset($override[$property])) {
				$payload[$property] = $override;
			} else {
				$payload[$property] = $value;
			}
		}

        // As you can see we are passing `JWT_SECRET` as the second parameter that will 
        // be used to decode the token in the future.
        return JWT::encode($payload, config('jwt.secret'));    
        
	}

	public function getApiKey(){
		return $this->api_key;
	}

	public function setApiKey($api_key){
		$this->api_key = $api_key;
		return $this;
	}

	public function getApiSecret(){
		return $this->api_secret;
	}

	public function setApiSecret($api_secret){
		$this->api_secret = $api_secret;
		return $this;
	}

	public function getTypeUserId(){
		return $this->type_user_id;
	}

	public function setTypeUserId($type_user_id){
		$this->type_user_id = $type_user_id;
		return $this;
	}

	public function getTypeUserName(){
		return $this->type_user_name;
	}

	public function setTypeUserName($type_user_name){
		$this->type_user_name = $type_user_name;
		return $this;
	}

	public function getUserId(){
		return $this->user_id;
	}

	public function setUserId($user_id){
		$this->user_id = $user_id;
		return $this;
	}

	public function getName(){
		return $this->name;
	}

	public function setName($name){
		$this->name = $name;
		return $this;
	}
	
	public function getUsername(){
		return $this->username;
	}

	public function setUsername($username){
		$this->username = $username;
		return $this;
	}

	public function logout(){

		$not_property = ['api_key', 'api_secret'];

		foreach (get_class_vars(get_class($this)) as $property => $value) {

			if(!in_array($property, $not_property)){

				$this->{$property} = null;
			}
		}
	}
}