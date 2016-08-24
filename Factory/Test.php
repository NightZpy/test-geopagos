<?php 
require_once ('FiguraFactory.php');

$cuadrado 	= FiguraFactory::crear(Figura::CUADRADO,  	5);
$triangulo 	= FiguraFactory::crear(Figura::TRIANGULO, 	5, 	4);
$circulo   	= FiguraFactory::crear(Figura::CIRCULO, 	3);


print("\n------------- Figuras -----------------");
print("\n-------- (" . $cuadrado->getTipo() . "):");
print("\nBase: " . $cuadrado->getBase());
print("\nSuperficie: " . $cuadrado->getSuperficie());
print("\n-------- (" . $triangulo->getTipo() . "):");
print("\nBase: " . $triangulo->getBase());
print("\nAltura: " . $triangulo->getAltura());
print("\nSuperficie: " . $triangulo->getSuperficie());
print("\n-------- (" . $circulo->getTipo() . "):");
print("\nDiametro: " . $circulo->getDiametro());
print("\nRadio: " . $circulo->getRadio());
