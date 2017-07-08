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
	public static function editar($atributos,$id)
	{
		$callback = new \stdClass;
		$callback->user = null;
		$callback->status = false;
		$callback->errors = [];

		if(!empty($atributos)){
			$editar = self::find(Estudante::find_by_usuario_id($id)->contato_id);
			$editar->update_attributes($atributos);
			$editar->save();
			if($editar->is_valid())
			{
				$callback->status = true;
				$callback->user = $editar;
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