<?php
/**
* 
*/
class Cargo extends \HXPHP\System\Model
{
	static $has_many = [
		['cargo_has_instituicaos']
	];

	static $validates_presence_of = [
			[
				'nome',
				'message' => '<strong>Nome do cargo</strong> é um campo obrigatório.'
			]
	];

	public static function cadastrar($post)
	{
		$callback = new \stdClass;
		$callback->status = false;
		$callback->user = null;
		$callback->errors = [];
  			
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

