<?php

/**
* 
*/
class Pago extends Model
{
	protected $attributes = ['codigopago', 'importe', 'fecha'];
	protected $tableName = 'pagos';

	protected $validation = [
		'importe' => 'min:1',
		'fecha' => 'date_min:today'
	];

	protected $relations = [
		'usuarios' => ['hasMany' => ['Usuario' => 'codigousuario']]
	];	
}