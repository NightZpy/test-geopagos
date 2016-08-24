<?php
require_once('Figura.php');
require_once('Cuadrado.php');
require_once('Circulo.php');
require_once('Triangulo.php');

class FiguraFactory {

	public static function crear($tipo) {
			switch($tipo) {
				case Figura::CUADRADO:
					return new Cuadrado($cilindros);
					break;
				case Figura::CIRCULO:
					return new Circulo($cilindros);
					break;
				case Figura::TRIANGULO:
					return new Triangulo($cilindros);
					break;
			}
		}	

}