<?php
require_once ('Usuario.php');
require_once ('Pago.php');
		
	/*for ($i = 1; $i <= 20; $i++) {
		$usuario = new Usuario;
		//$usuario->codigoUsuario = 35;
		$usuario->usuario = 'Usuario-' . $i;
		$usuario->clave = md5('123456');
		$usuario->edad = rand(19, 60); 
		$usuario->save();				
	}*/

	//print("\n----------------");
	//print_r($usuarios[0]);
	//die();
	/*for ($i = 0; $i < 60; $i++) {
		$pago = new Pago;
		$pago->importe = rand(1, 10000);
		$pago->fecha = '2016-' . rand(8, 12) . '-' . rand(1, 28);
		$pago->save();		
	}*/

	/*$usuario = new Usuario;
	$usuarios = $usuario->all();	
	$pago = new Pago;
	$pagos = $pago->all();
	print("\nUsuarios: " . count($usuarios));
	print("\nPagos: " . count($pagos));
	$pagosList = array_chunk($pagos, 3);*/
	//print("\n-------------------------------------\n");
	//print_r($pagos);
	//print("\n-------------------------------------\n");
	//die;
	/*$i = 0;
	foreach ($pagosList as $pagos) {
		//print("\n");
		//print_r($pagos);
		foreach ($pagos as $pago) 
			$usuarios[$i]->pagos($pago);
		$i++;
	}*/
	//die();

	/*$usuario = new Usuario;
	$usuario->findByPk(rand(397, 435));
	print("\n- Pagos de " . $usuario->usuario . "-----------------\n");
	print("	Importe	|	Fecha	\n");
	foreach ($usuario->pagos() as $pago) {
		print("	" . $pago->importe . "	|	" . $pago->fecha . "	\n");
	}*/
	
/*	$usuario = new Usuario;
	$usuario->findByPk(rand(397, 435));
	print("\n----------------------------");
	print_r($usuario);
	$usuario->usuario = 'Paul Lenyn';
	$usuario->update();
	$usuario->findByPk($usuario->codigoUsuario);
	print("\n----------------------------");
	print_r($usuario);*/

	/*$usuario = new Usuario;
	$pk = rand(397, 435);
	$usuario->findByPk($pk);
	print("\n---------------Usuario a borrar-------------\n");
	print_r($usuario);
	$usuario->delete();*/

	/*$parent = new Usuario;
	$parent->findByPk(397);
	$usuario = new Usuario;
	for ($i=398; $i < 404; $i++) { 
		$favoritos = [];
		$usuario->findByPk($i);
		$favoritos[] = $usuario;
		$parent->favoritos($favoritos);
	}*/

	/*$usuario = new Usuario;
	$usuario->findByPk(397);
	print("\n---------------Favoritos-------------\n");
	print_r($usuario->favoritos());*/

	$usuario = new Usuario;
	$usuario->findByPk(397);
	$favorito = new Usuario;
	$favorito->findByPk(398);
	$favoritos[] = $favorito;
	$usuario->favoritos($favoritos);





	