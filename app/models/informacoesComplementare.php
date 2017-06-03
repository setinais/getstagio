<?php 

class InformacoesComplementare extends HXPHP\System\Model{
	static $belongs_to = [
		['estudante']
		];
	static $validates_presence_of = [
		[
			'disponibilidade_jornada',
			'message' => '<strong>Disponibilidade jornada</strong> é um campo obrigatório.'
		],
		[
			'disponibilidade_ch_diaria',
			'message' => '<strong>Carga horária diária</strong> é um campo obrigatório.'
		]
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