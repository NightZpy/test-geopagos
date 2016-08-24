<?php
require_once('Figura.php');
require_once('iFigura.php');

class Cuadrado extends Figura implements iFigura
{
	
	function __construct($base)
	{
		parent::__construct('Cuadrado', $base);
	}

    public function getSuperficie() {
    	return $this->getBase() * $this->getBase();
    }

    public function getDiametro() {
    	return null;
    }

    public function getAltura() {
    	return $this->getBase();
    }
}