<?php 

class ConhecimentoSistema extends HXPHP\System\Model{
	static $belongs_to = [
		['estudante'],
		['sistema']
		];
	static $validates_presence_of = [
			[
				'sistema_id',
				'message' => '<strong>O sistema não cadastrado</strong> é um campo obrigatório.'
			]
		];
	public static function cadastrar($idu,$idesc)
	{
		$callback = new \stdClass;
		$callback->status = false;
		$callback->cadastro = null;
		$callback->errors = [];
			$cadastrar = self::create(array('sistema_id'=>$idesc,'estudante_id'=>$idu));
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