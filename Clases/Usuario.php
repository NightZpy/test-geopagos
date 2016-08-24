<?php
require_once ('Model.php');
require_once ('Pago.php');
require_once ('Favorito.php');

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

		$pagoUsuario = new PagoUsuario;
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

	public function favoritos($favoritos = null)
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

		if ($favoritos) {
			if (is_a($favoritos, 'Favorito')) {
				$this->saveUsuarioFavorito($favoritos);
			} elseif (is_array($favoritos)) {
				foreach ($favoritos as $favorito) {
					$this->saveUsuarioFavorito($favorito);
				}
			} 			
			return 1;
		} 

		$favorito = new Favorito;
		$favoritos = $favorito->findBy('codigo_usuario', $this->codigoUsuario);

		$favs = [];
		foreach ($favoritos as $favorito) {
			$fav = $favorito->usuarioFavorito();
			$favs[] = $fav;
		}
		return $favs;
	}

	private function saveUsuarioFavorito($usuario)
	{
		$favorito = new Favorito;
		$favorito->codigoUsuario = $this->codigoUsuario;
		$favorito->codigoUsuarioFavorito = $usuario->codigoUsuario;		
		$favorito->save();		
	}

	public function delete($field = null)
	{
		$pagoUsuario = new PagoUsuario;
		$pagosUsuario = $pagoUsuario->findBy('codigo_usuario', $this->codigoUsuario);
		foreach ($pagosUsuario as $pagoUsuario) 
			$pagoUsuario->delete('codigo_usuario');
		parent::delete();		
	}	
}