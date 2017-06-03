<?php 

<<<<<<< HEAD
class formacoesComplementare extends \HXPHP\System\Model
{
=======
class FormacoesComplementare extends HXPHP\System\Model{
>>>>>>> 3ce8657d060a9f532b3987e7a754820b5d04b314
	static $belongs_to = [
		['estudante']
		];

	public static function cadastrar($post)
	{
		$callback = new \stdClass;
		$callback->status = false;
		$callback->cadastro = null;
		$callback->errors = [];
			$cadastrar = self::create($post);
			if($cadastrar->is_valid())
			{
				$callback->status = true;
				$callback->cadastro = $cadastrar;
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