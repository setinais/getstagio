<?php 

class Formaco extends HXPHP\System\Model{
	static $belongs_to = [
		['estudante']
	];
	public static function cadastrar($post,$idestu)
	{
		$callback = new \stdClass;
		$callback->status = false;
		$callback->cadastro = null;
		$callback->errors = [];
  			
			$cadastrar = self::create(array(
				'formacao'=>$post['formacao'],
				'instituicao_ensino'=>$post['instituicao_ensino'],
				'situacao_curso'=>$post['situacao_curso'],
				'serie_modulo_periodo'=>$post['serie_modulo_periodo'],
				'ano_inicio'=>$post['ano_inicio'],
				'ano_termino'=>$post['ano_termino'],
				'estudante_id'=>$idestu
				));
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