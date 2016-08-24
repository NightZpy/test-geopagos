<?php
require_once('Figura.php');
require_once('iFigura.php');

class Circulo extends Figura implements iFigura
{
	private $radio = 0;

	function __construct($radio)
	{
		$this->radio = $radio;
		parent::__construct('Circulo', null, null);
	}

    public function getSuperficie() {
    	return null;
    }

    public function getDiametro() {
    	return $this->radio * 2;
    }

    public function getRadio()
    {
    	return $this->radio;
    }
}