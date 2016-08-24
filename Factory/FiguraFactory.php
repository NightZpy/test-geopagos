<?php
require_once('Figura.php');
require_once('Cuadrado.php');
require_once('Circulo.php');
require_once('Triangulo.php');

class FiguraFactory {

	public static function crear($tipo, $base, $altura = null) {
		switch($tipo) {
			case Figura::CUADRADO:
				return new Cuadrado($base);
				break;
			case Figura::CIRCULO:
				return new Circulo($base);
				break;
			case Figura::TRIANGULO:
				return new Triangulo($base, $altura);
				break;
		}
	}	

}