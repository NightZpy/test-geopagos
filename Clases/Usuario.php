<?php
require_once ('Model.php');
require_once ('Pago.php');

/**
* 
*/
class Usuario extends Model
{
	protected $attributes = ['codigo_usuario', 'usuario', 'clave', 'edad'];
	protected $tableName = 'usuarios';
	protected $primaryKey = 'codigo_usuario';

	protected $validationRules = [
		'usuario' => 'required',
		'edad' => 'min:19'
	];

	/*protected $relations = [
		'favoritos' => ['hasMany' => ['Favorito' => 'codigo_usuario', 'pivot' => 'favoritos']],
		'pagos' => ['hasMany' => ['Pago' => 'codigo_usuario', 'pivot' => 'pago_usuario']]
	];*/

	public function pagos($pagos = null)
	{
		/*if (isset($this->relations)) {
			$relationTypes = key($this->relations);
			if (in_array('manyToMany', $relationTypes)) {
				$relation = $this->relations['manyToMany'];
				if (is_a($pagos, 'Usuario')) {
				} elseif (is_array($pagos)) {
					
				}				
			}
		}*/

		if ($pagos) {
			if (is_a($pagos, 'Pago')) {
				$this->savePagoUsuario($pagos);
			} elseif (is_array($pagos)) {
				foreach ($pagos as $pago) {
					$this->savePagoUsuario($pago);
				}
			} 			
			return 1;
		} 

		$pagoUsuario = new PagoUsuario();
		$pagosUsuario = $pagoUsuario->findBy('codigo_usuario', $this->codigoUsuario);

		$pagos = [];
		foreach ($pagosUsuario as $pagoUsuario) {
			$pago = $pagoUsuario->pago();
			$pagos[] = $pago;
		}
		return $pagos;
	}

	private function savePagoUsuario($pago)
	{
		$pagoUsuario = new PagoUsuario;
		$pagoUsuario->codigoUsuario = $this->codigoUsuario;
		$pagoUsuario->codigoPago = $pago->codigoPago;		
		$pagoUsuario->save();		
	}	
}