<?php 
/**
* 
*/
class Vaga extends \HXPHP\System\Model
{
	static $has_many = [
		['log_vagas'],
		['requisitos']
		];

	static $belongs_to = [
		['cargo_has_instituicao']
	];

	public static function search($id_user = null,$filters = null)
	{
		$vagas = null;
		$op = Usuario::find($id_user)->funcoe->tipo;
		$id_inst = Instituicao::find_by_usuario_id($id_user)->id;
		switch($op)
		{		
			case "Estudante" :
				$vagas = self::all();
				break;
			case "Instituicao":
				$all = self::all();
				$vagas=null;
				foreach ($all as $key) {
					if($key->cargo_has_instituicao->instituicao->id == $id_inst){
						$vagas[] = $key;
					}
				}
				break;
		}
		return $vagas;
	}

	public static function cadastrar($post,$id_user)
	{

		$callback = new \stdClass;
		$callback->status = false;
		$callback->user = null;
		$callback->errors = [];

		$id_inst = Instituicao::find_by_usuario_id($id_user)->id;
		$id_cargo = $post['cargo_id'];

		unset($post['cargo_id']);
		$chi = CargoHasInstituicao::cadastrar($id_inst,$id_cargo);
		$post['cargo_has_instituicao_id'] = $chi->id;
		$post['status'] = true;

		$cadastrar = self::create($post);
		if($cadastrar->is_valid())
		{
			$callback->status = true;
			$callback->user = $cadastrar;
		}
		else
		{
			$errors = $cadastrar->errors->get_raw_errors();
			foreach ($errors as $campo => $messagem) {
				array_push($callback->errors, $messagem[0]);
			}
		}

		return $callback;
	}
}