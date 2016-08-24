<?php  

abstract class Figura
{

	const CUADRADO  = 0;
	const CIRCULO   = 1;
	const TRIANGULO = 2;

	private $base   = 0;
	private $altura = 0;
	private $tipo 	= '';
	
	function __construct($tipo = '', $base = 0, $altura = 0)
	{
		$this->base   = $base;
		$this->altura = $altura;
		$this->tipo   = $tipo;
	}

    public function getBase() {
    	return $this->base;
    }

    public function getAltura() {
    	return $this->altura;
    }	

	public function getTipo() {
    	return $this->tipo;
    }    
}