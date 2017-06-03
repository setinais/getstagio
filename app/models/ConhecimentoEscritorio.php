<?php 

class ConhecimentoEscritorio extends \HXPHP\System\Model{
	static $belongs_to = [
		['estudante'],
		['conheciemnto']
		];

	public static function cadastrar($idu,$idesc)
	{
		$callback = new \stdClass;
		$callback->status = false;
		$callback->cadastro = null;
		$callback->errors = [];
			$cadastrar = self::create(array('conhecimento_id'=>$idesc,'estudante_id'=>$idu));
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