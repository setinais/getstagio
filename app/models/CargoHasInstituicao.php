<?php
/**
* 
*/
class CargoHasInstituicao extends \HXPHP\System\Model
{
	static $has_many = [
		['vagas']
	];

	static $belongs_to = [
		['cargo'],
		['instituicao']
	];

	public static function cadastrar($id_ins,$id_cargo)
	{
		$post = ['cargo_id' => $id_cargo,'instituicao_id' => $id_ins];
		$callback = self::create($post);
		return $callback;
	}
}