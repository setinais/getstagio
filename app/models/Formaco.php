<?php 

class Formaco extends \HXPHP\System\Model
{
	static $belongs_to = [
		['estudante']
	];
	static $validates_presence_of = [
		[
			'formacao',
			'message' => '<strong>Formação</strong> é um campo obrigatório.'
		],
		[
			'instituicao_ensino',
			'message' => '<strong>Instituição ensino</strong> é um campo obrigatório.'
		],
		[
			'situacao_curso',
			'message' => '<strong>Situação do curso</strong> é um campo obrigatório.'
		],
		[
			'serie_modulo_periodo',
			'message' => '<strong>Série / Periodo</strong> é um campo obrigatório.'
		],
		[
			'ano_inicio',
			'message' => '<strong>Ano de início</strong> é um campo obrigatório.'
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