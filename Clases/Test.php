<?php
require_once ('Usuario.php');
require_once ('Pago.php');
	
	/*$usuario = new Usuario;
	$usuario->codigoUsuario = 35;
	$usuario->usuario = 'Cuao';
	$usuario->clave = md5('123456');
	$usuario->edad = 20; 
	$usuario->save();
	print("\nGuardado");*/

	$pago = new Pago;
	$pago->importe = 1;
	$pago->fecha = '2016-01-01';
	$pago->save();
	print("\nGuardado");


	//$usuario->findBy('codigo_usuario', 10, 'usuarios');
	//$usuario->delete();


