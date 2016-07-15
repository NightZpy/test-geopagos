<?php

/**
* 
*/
class Pago extends Model
{
	protected $attributes = ['codigo_pago', 'importe', 'fecha'];
	protected $tableName = 'pagos';
	protected $primaryKey = 'codigo_pago';

	protected $validation = [
		'importe' => 'min:1',
		'fecha' => 'date:min:today'
	];

	protected $relations = [
		'usuarios' => ['hasMany' => ['Usuario' => 'codigousuario']]
	];	
}