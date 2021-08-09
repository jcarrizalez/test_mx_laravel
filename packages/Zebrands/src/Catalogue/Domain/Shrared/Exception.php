<?php 
declare( strict_types = 1 );
namespace Zebrands\Catalogue\Domain\Shrared;

use Zebrands\Catalogue\Laravel\Exception as ExceptionBase;

class Exception extends ExceptionBase
{
	protected $errors	= [
		401 => [
			4013 =>	'No Autorizado',
			4014 =>	'Error ApiKey',
			4015 =>	'Error ApiSecret',
		],
		403 => [
			4030 =>	'Error de subscription',
			4031 =>	'Error de en username o password',
			4032 =>	'Requiere iniciar session',
			4033 =>	'Session Expirada',
			4034 =>	'Requiere ser Administrador para esta acción',
		],
		404 => [
			4040 =>	'Tipo de Usuario Administrador, solo lo asigna un Administrador',
			4041 =>	'Esta Marca ya existe',
			4042 =>	'Este Nombre Producto ya existe con la misma marca en este pais',
			4043 =>	'Este Usuario ya existe',
			4044 =>	'Este Codigo Producto ya existe',
			4045 =>	'No existe este producto o ya fue eliminado',
			4046 =>	'No existe este usuario o ya fue eliminado',
		]
	];
   	public function __construct(int $error=0, $data=null)
    {
    	parent::__construct($error, $data);
    }
}