<?php

/**
* 
*/
class Usuario extends \HXPHP\System\Model
{
	static $belongs_to = [
			['funcoe'],
			['estado']
			];

	static $has_many = [
		['recuperar_senhas'],
		['tentativas_logons'],
		['estudantes'],
		['instituicaos'],
		['cadastros']
	];
	static $validates_presence_of = [
			[
				'nome',
				'message' => '<strong>Nome</strong> é um campo obrigatório.'
			],
			[
				'email',
				'message' => '<strong>Email</strong> é um campo obrigatório.'
			],			
			[
				'telefone',
				'message' => '<strong>Telefone</strong> é um campo obrigatório.'
			],
			[
				'senha',
				'message' => '<strong>Senha</strong> é um campo obrigatorio.'
			],
			[
				'endereco',
				'message' => '<strong>Endereço</strong> é um campo obrigatorio.'
			],
			[
				'numero',
				'message' => '<strong>Número</strong> é um campo obrigatorio.'
			],
			[
				'cep',
				'message' => '<strong>Cep</strong> é um campo obrigatório.'
			],
			[
				'estado_id',
				'message' => '<strong>Estado</strong> é um campo obrigatório.'
			],
			[
				'cidade',
				'message' => '<strong>Cidade</strong> é um campo obrigatório.'
			]

			
	];
	static $validates_uniqueness_of  = [
			[
				['email'],
				'message' => 'Já existe um usuário cadastrado com este <strong>email</strong>.'
			]
	];
	static $validates_size_of = [
	  ['cep', 'is' => 8, 'wrong_length' => 'O tamanho do <strong>CEP</strong> deve ser de 8 caracteres.'],
	  ['telefone', 'maximum' => 11, 'too_long' => '<strong>Telefone</strong> inválido!'],
	  ['telefone', 'minimum' => 10, 'too_short' => '<strong>Telefone</strong> inválido!']
   ];
	public static function cadastrar($post)
	{
		$callback = new \stdClass;
		$callback->status = false;
		$callback->user = null;
		$callback->errors = [];
  
		if(!empty($post['senha'])){
			$senha = \HXPHP\System\Tools::hashHX($post['senha']);
			$post["senha"] = $senha['password'];
			$post['salt'] = $senha['salt'];
			$post['status'] = 1;
			$post['funcoe_id'] = 3;
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
		}
		else
		{
			array_push($callback->errors ,'Campo <strong>Senha</strong> está vazio');
		}

		return $callback;
	}
			
	public static function logar($dados)
	{
		$callBack_logar = new \stdClass;
		$callBack_logar->alert = '';
		$callBack_logar->status = false;
		$callBack_logar->user = null;
		if(!empty($dados['email'])){
			if(!empty($dados['senha'])){

				$user = self::find_by_email($dados['email']);
				if(!is_null($user))
				{
					$password = \HXPHP\System\Tools::hashHX($dados['senha'],$user->salt);
					if($user->status === 1)
					{

						if(TentativasLogon::CheckTentativa($user->id))
						{
							if($password['password'] === $user->senha)
							{
								TentativasLogon::LimparTentativas($user->id);
								$callBack_logar->status = true;
								$callBack_logar->user = $user;

							}
							else
							{
								TentativasLogon::ArmazenarTentativa($user->id);
								$total_tentativas =5 - TentativasLogon::CountTentativa($user->id);
								$callBack_logar->alert = '<strong>Senha</strong> incorreta, você ainda tem '.$total_tentativas.' tentativas!';

							}
						}
						else
						{
							$user->status = 0;
							$user->save(false);
							$callBack_logar->alert = '<strong>Usuario</strong> bloqueado, devido a tentativas excessivas!';

						}
					}else{
						$callBack_logar->alert = '<strong>Usuario</strong> bloqueado, devido a tentativas excessivas!';

					}
				}
				else
				{
					$callBack_logar->alert = 'Este <strong>Usuário</strong> não existe!';

				}
			}
			else
			{
				$callBack_logar->alert = "Campo <strong>senha</strong> está vazio.";
			}
		}
		else
		{
			$callBack_logar->alert = "Campo <strong>e-mail</strong> está vazio.";
		}
		return $callBack_logar;
	}

	public static function editar($atributos,$id)
	{
		$callback = new \stdClass;
		$callback->user = null;
		$callback->status = false;
		$callback->errors = [];

		if(!empty($atributos)){
			$editar = self::find($id);
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