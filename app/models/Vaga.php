<?php 
/**
* 
*/
class Vaga extends \HXPHP\System\Model
{
	static $has_many = [
		['log_vagas'],
		['requisitos']
		];

	static $belongs_to = [
		['cargo_has_instituicao']
	];

	static $validates_presence_of = [
			[
				'qnt',
				'message' => '<strong>Quantidade</strong> é um campo obrigatório.'
			],
			[
				'descricao',
				'message' => '<strong>Descrição</strong> é um campo obrigatório.'
			],			
			[
				'remuneracao',
				'message' => '<strong>Remuneração</strong> é um campo obrigatório.'
			],
			[
				'duracao',
				'message' => '<strong>Duração</strong> é um campo obrigatorio.'
			],
			[
				'idademinima',
				'message' => '<strong>Idade Minima</strong> é um campo obrigatorio.'
			],
			[
				'cargahoraria',
				'message' => '<strong>Carga horaria</strong> é um campo obrigatorio.'
			]
	];

	public static function search($id_user)
	{
		$vagas = null;
		$op = Usuario::find($id_user)->funcoe->tipo;
		switch($op)
		{		
			case "Estudante" :
				$vagas = self::all();
				break;
			case "Instituicao":
				$id_inst = Instituicao::find_by_usuario_id($id_user)->id;
				$all = self::all();
				foreach ($all as $key) {
					if($key->cargo_has_instituicao->instituicao->id == $id_inst){
						$vagas[] = $key;
					}
				}
				break;
		}
		return $vagas;
	}

	public static function cadastrar($post,$id_user)
	{
		$i=1;
		foreach ($post as $key => $value) {
			if($key == "requisito-".$i){
				//aqui faz a inserção no BD
				unset($post['requisito-'.$i]);
				$i++;
			}
		}

		$callback = new \stdClass;
		$callback->status = false;
		$callback->user = null;
		$callback->errors = [];

		$id_inst = Instituicao::find_by_usuario_id($id_user)->id;
		$id_cargo = $post['cargo_id'];

		unset($post['cargo_id']);
		$chi = CargoHasInstituicao::cadastrar($id_inst,$id_cargo);
		$post['cargo_has_instituicao_id'] = $chi->id;
		$post['status'] = true;

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

	public static function finalizarVaga($id)
	{
		$editar = self::find($id);
		$editar->update_attributes(['status' => false]);
		$editar->save();
	}

	public static function reabrirVaga($id)
	{
		$editar = self::find($id);
		$editar->update_attributes(['status' => true]);
		$editar->save();
	}

	public static function eliminarVaga($ids)
	{
		Requisito::excluir($ids);
		self::table()->delete(['id' => $ids]);
	}

	public static function editarVaga($id,$atributes)
	{
		$i=1;
		foreach ($atributes as $key => $value) {
			if($key == "requisito-".$i){
				//aqui faz a inserção no BD
				unset($atributes['requisito-'.$i]);
				$i++;
			}
		}

		$callback = new \stdClass;
		$callback->status = false;
		$callback->user = null;
		$callback->errors = [];

		unset($atributes['cargo_id']);

		$editar = self::find($id);
		$editar->update_attributes($atributes);
		$editar->save();

		if($editar->is_valid())
		{
			$callback->status = true;
			$callback->user = $editar;
		}
		else
		{
			$errors = $editar->errors->get_raw_errors();
			foreach ($errors as $campo => $messagem) {
				array_push($callback->errors, $messagem[0]);
			}
		}

		return $callback;
	}
}