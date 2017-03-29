<?php

/**
* 
*/
class Instituicao extends \HXPHP\System\Model
{
	static $belongs_to = [
		['usuario']
	];
	static $validates_presence_of = [
			[
				'razao_social',
				'message' => 'O <strong>Razão Social</strong> é um campo obrigatório.'
			],
			[
				'cnpj',
				'message' => 'O <strong>CNPJ</strong> é um campo obrigatório.'
			]
			
	];
	static $validates_uniqueness_of  = [
			[
				['cnpj'],
				'message' => 'Este CNPJ ja consta em nosso cadastro.'
			]
	];

	public static function cadastrar($post,$id_user)
	{
		$callback = new \stdClass;
		$callback->status = false;
		$callback->user = null;
		$callback->errors = [];

		$post['ofertas'] = false;
		$post['usuario_id'] = $id_user;
		$cadastrar = self::create($post);
		if($cadastrar->is_valid())
		{
			$callback->status = true;
			$callback->user = $cadastrar;

			$usuario = Usuario::find($id_user);
			$usuario->funcoe_id = 2;
			$usuario->save();
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
	
	public static function editar($post,$id)
	{
		$callback = new \stdClass;
		$callback->status = false;
		$callback->ins = null;
		$callback->errors = [];

		if(!empty($post))
		{

		}
		else
		{
			$callback->errors = "";
		}
		return $callback;
	} 
}