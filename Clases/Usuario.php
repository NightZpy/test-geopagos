<?php
require_once ('Model.php');

/**
* 
*/
class Usuario extends Model
{
	protected $attributes = ['codigo_usuario', 'usuario', 'clave', 'edad'];
	protected $tableName = 'usuarios';
	protected $primaryKey = 'codigo_usuario';

	protected $validationRules = [
		'usuario' => 'required',
		'edad' => 'min:19'
	];

	protected $relations = [
		'favoritos' => ['hasMany' => ['Favorito' => 'codigo_usuario', 'pivot' => 'favoritos']],
		'pagos' => ['hasMany' => ['Pago' => 'codigo_usuario', 'pivot' => 'pago_usuario']]
	];
}