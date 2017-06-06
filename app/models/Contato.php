<?php 

class contato extends \HXPHP\System\Model
{
	static $has_many = [
		['estudantes'],
		['instituicao']
	];
	static $validates_presence_of = [
		[
			'telefone',
			'message' => '<strong>Telefone</strong> é um campo obrigatório.'
		]
		];
	public static function cadastrar($post)
	{
		$callback = new \stdClass;
		$callback->status = false;
		$callback->cadastro = null;
		$callback->errors = [];
  			
			$cadastrar = self::create($post);
			if($cadastrar->is_valid()){
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