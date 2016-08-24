<?php
require_once ('Model.php');
//require_once ('Usuario.php');

/**
* 
*/
class Favorito extends Model
{
	protected $attributes = ['codigo_usuario', 'codigo_usuario_favorito'];
	protected $tableName = 'favoritos';

	/*protected $relations = [
		'favoritos' => ['hasMany' => ['Favorito' => 'codigo_usuario', 'pivot' => 'favoritos']],
		'pagos' => ['hasMany' => ['Pago' => 'codigo_usuario', 'pivot' => 'pago_usuario']]
	];*/

	protected $validationRules = [
	];	

	public function usuarioFavorito($usuario = null)
	{
		$usuarioFavorito = new Usuario;
		$usuarioFavorito->findByPk($this->codigoUsuarioFavorito);
		return $usuarioFavorito;		
	}	

	public function usuario($usuario = null)
	{
		$usuario = new Usuario;
		$usuario->findByPk($this->codigoUsuario);
		return $usuario;	
	}	

	/*public function delete($field = null)
	{
		parent::delete();		
	}	*/

	public function save()
	{
		$attributes = $this->attributesToArray();
		$codigoUsuario = $attributes['codigo_usuario'];
		$codigoUsuarioFavorito = $attributes['codigo_usuario_favorito'];
		if ($this->count($attributes)) 
			throw new Exception("El usuario $codigoUsuario ya tiene como favorito a $codigoUsuarioFavorito!", 1);
		parent::save();
	}
}