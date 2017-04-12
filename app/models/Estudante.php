<?php

/**
* 
*/
class Estudante extends \HXPHP\System\Model
{
	static $belongs_to = [
		['usuario']
	];
	static $has_many = array(
		array('cadastros')
		);
	static $validates_presence_of = [
			[
				'matricula',
				'message' => '<strong>Matrícula</strong> é um campo obrigatório.'
			],
			[
				'curso',
				'message' => '<strong>Curso</strong> é um campo obrigatório.'
			],
			[
				'serie_modulo',
				'message' => '<strong>Série / Modúlo / Período</strong> é um campo obrigatório.'
			],
			[
				'data_nasc',
				'message' => '<strong>Data de nascimento</strong> é um campo obrigatorio.'
			]
			
	];
	static $validates_uniqueness_of  = [
			[
				['matricula'],
				'message' => 'Já existe um estudante cadastrado com esta <strong>matricula</strong>.'
			]
	];

	public static function cadastrar($post,$id_user)
	{
		$callback = new \stdClass;
		$callback->status = false;
		$callback->user = null;
		$callback->errors = [];

		$post['usuario_id'] = $id_user;
		$cadastrar = self::create($post);
		if($cadastrar->is_valid())
		{
			$callback->status = true;
			$callback->user = $cadastrar;

			$usuario = Usuario::find($id_user);
			$usuario->funcoe_id = 1;
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
	
	public static function editar($atributos,$id)
	{
		$callback = new \stdClass;
		$callback->status = false;
		$callback->est = null;
		$callback->errors = [];

		if(!empty($atributos)){
			$editar = self::find($id);
			$editar->update_attributes($atributos);
			$editar->save();
			if($editar->is_valid())
			{
				$callback->status = true;
				$callback->est = $editar;
			}
			else
			{
				$errors = $editar->errors->get_raw_errors();
				foreach ($errors as $campo => $messagem) 
				{
					array_push($callback->errors, $messagem[0]);
				}
			}
		}
		else
		{
			$callback->errors = "Todos os campos estão vazios!";
		}
		return $callback;
	} 
}