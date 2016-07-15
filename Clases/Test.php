<?php
require_once ('Usuario.php');

try {
	
	$usuario = new Usuario;
	$usuario->codigoUsuario = 20;
	$usuario->usuario = 'Lenyn';
	$usuario->clave = md5('123456');
	$usuario->edad = 5; 
	$usuario->save();

	$usuario->findBy('codigo_usuario', 10, 'usuarios');
	$usuario->delete();
} catch (ValidationNotNullException $e) {
    $e->showMessage();
}

