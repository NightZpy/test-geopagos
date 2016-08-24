<?php
require_once('Figura.php');
require_once('iFigura.php');

class Triangulo extends Figura implements iFigura
{
	
	function __construct($base, $altura)
	{
		parent::__construct('Triangulo', $base, $altura);
	}

    public function getSuperficie() {
    	return ($this->getBase() * $this->getAltura()) / 2;
    }

    public function getDiametro() {
    	return null;
    }
}