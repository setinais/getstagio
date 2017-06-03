<?php 

class Idioma extends \HXPHP\System\Model
{
	static $belongs_to = [
		['estudante']
	];
	static $validates_presence_of = [
		[
			'idioma',
			'message' => '<strong>Idioma</strong> é um campo obrigatório.'
		],
		[
			'le',
			'message' => '<strong>le</strong> é um campo obrigatório.'
		],
		[
			'escreve',
			'message' => '<strong>Escreve</strong> é um campo obrigatório.'
		],
		[
			'fala',
			'message' => '<strong>Fala</strong> é um campo obrigatório.'
		],
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