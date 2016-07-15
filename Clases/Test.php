<?php
require_once ('Usuario.php');
require_once ('Pago.php');
	
	$usuarios = [];
	for ($i = 1; $i <= 20; $++) {
		$usuario = new Usuario;
		//$usuario->codigoUsuario = 35;
		$usuario->usuario = 'Usuario-' . $i;
		$usuario->clave = md5('123456');
		$usuario->edad = rand(19, 60); 
		$usuario->save();		
		$usuarios[] = $usuario;
	}

	$pagos = [];
	for ($i = 0; $i < 60; $++) {
		$pago = new Pago;
		$pago->importe = rand(1, 10000);
		$pago->fecha = '2016-' . rand(1, 12) . '-' . rand(1, 28);
		$pago->save();
		$pagos[] = $pago;
	}

	$pagos = array_chunk($pagos, 3);
	$i = 0;
	foreach ($pagos as $pago) {
		$usuarios[$i].pagos($pago);
		$i++;
	}

	$usuario->findBy('codigo_usuario', rand(1,20), 'usuarios');
	print("\nPagos de " . $usuario->usuario . "\n");
	print_r($usuario->pagos());


	//$usuario->delete();


