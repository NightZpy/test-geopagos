<?php
require_once ('Model.php');
require_once ('Usuario.php');
require_once ('PagoUsuario.php');
/**
* 
*/
class Pago extends Model
{
	protected $attributes = ['codigo_pago', 'importe', 'fecha'];
	protected $tableName = 'pagos';
	protected $primaryKey = 'codigo_pago';

	protected $validationRules = [
		'importe' => 'min:1',
		'fecha' => 'date:min:today',
	];

	/*protected $relations = [
		'usuarios' => ['manyToMany' => [
								'Usuario' => 'codigo_usuario', 
								'pivot' => 'pago_usuario', 
								'pivo_class' => 'PagoUsuario'
								]
						]
	];*/	

	public function usuarios($usuarios = null)
	{
		/*if (isset($this->relations)) {
			$relationTypes = key($this->relations);
			if (in_array('manyToMany', $relationTypes)) {
				$relation = $this->relations['manyToMany'];
				if (is_a($usuarios, 'Usuario')) {
				} elseif (is_array($usuarios)) {
					
				}				
			}
		}*/

		if ($usuarios) {
			if (is_a($usuarios, 'Usuario')) {
				$this->savePagoUsuario($usuarios);
			} elseif (is_array($usuarios)) {
				foreach ($usuarios as $usuario) {
					$this->savePagoUsuario($usuario);
				}
			} 			
		}
		$pagoUsuario = new PagoUsuario();
		$pagoUsuarios = $pagoUsuario->findBy('codigo_pago', $this->codigoPago);
		$usuarios = [];
		foreach ($pagoUsuarios as $pagoUsuario) {
			$usuarios[] = $pagoUsuario->usuario();
		}
		return $usuarios;
	}

	private function savePagoUsuario($usuario)
	{
		$pagoUsuario = new PagoUsuario;
		$pagoUsuario->codigoUsuario = $usuarios->codigoUsuario;
		$pagoUsuario->codigoPago = $this->codigoPago;
		$pagoUsuario->save();		
	}

	public function delete($field = null)
	{
		$pagoUsuario = new PagoUsuario;
		$pagosUsuario = $pagoUsuario->findBy('codigo_pago', $this->codigoPago);
		foreach ($pagosUsuario as $pagoUsuario) 
			$pagoUsuario->delete('codigo_pago');
		parent::delete();		
	}		
}