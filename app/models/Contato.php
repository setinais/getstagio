<?php 

class contato extends \HXPHP\System\Model
{
	static $has_many = [
		['conhecimentoSistema']
	];
	public static function cadastrar($post)
	{
		$callback = new \stdClass;
		$callback->status = false;
		$callback->cadastro = null;
		$callback->errors = [];
  			
			$cadastrar = self::create(array(
				'telefone'=>(isset($post['telefoneEstudante']) && $post['telefoneEstudante']!="")?$post['telefoneEstudante']:"",
				'celular'=>(isset($post['celularEstudante']) && $post['celularEstudante']!="")?$post['celularEstudante']:"",
				'site'=>(isset($post['site']) && $post['site']!="")?$post['site']:"",
				));
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
}