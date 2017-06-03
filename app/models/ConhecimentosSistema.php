<?php 

<<<<<<< HEAD:app/models/ConhecimentoSistema.php
class conhecimentoSistema extends \HXPHP\System\Model{
=======
class ConhecimentosSistema extends HXPHP\System\Model{
>>>>>>> 3ce8657d060a9f532b3987e7a754820b5d04b314:app/models/ConhecimentosSistema.php
	static $belongs_to = [
		['estudante'],
		['sistema']
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