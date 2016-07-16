<?php
require_once ('Usuario.php');
require_once ('Model.php');

/**
* 
*/
class PagoUsuario extends Model
{
	protected $attributes = ['codigo_pago', 'codigo_usuario'];
	protected $tableName = 'pago_usuario';
	//protected $primaryKey = 'codigo_pago';

	protected $validationRules = [
		'codigo_usuario' => 'exists:Usuario',
		'codigo_pago' => 'exists:Pago'
	];

	/*protected $relations = [
		'usuarios' => ['hasMany' => ['Usuario' => 'codigousuario']]
	];	*/

	public function usuario()
	{
		return new Usuario()->findByPk($this->codigo_usuario);
	}
}