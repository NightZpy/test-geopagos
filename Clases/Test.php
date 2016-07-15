<?php
require_once ('Usuario.php');

try {
	
	$usuario = new Usuario;
/*
	$usuario->codigoUsuario = 20;
	$usuario->usuario = 'Lenyn';
	$usuario->clave = md5('123456');
	$usuario->edad = 5; 
	$usuario->save();
	*/

	$usuario->findBy('codigo_usuario', 20, 'usuarios');
	print("\nVars: ");
	print_r(get_object_vars($usuario));
	print("\nUsuario: " . $usuario->usuario);	
	$usuario->usuario = 'Paul';
	print("\nUsuario: " . $usuario->usuario);
	$usuario->update();
} catch (ValidationNotNullException $e) {
    $e->showMessage();
}

