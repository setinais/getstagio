<?php

/**
* 
*/
class Instituicao extends \HXPHP\System\Model
{
	static $belongs_to = [
		['usuario'],
		['contato']
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
				'message' => 'Este CNPJ já está cadastrado.'
			]
	];

	public static function cadastrar($post,$id_user)
	{
		$callback = new \stdClass;
		$callback->status = false;
		$callback->user = null;
		$callback->errors = [];
		$contato = contato::cadastrar(array(
			'telefone'=>(isset($post['telefone']) && $post['telefone']!="")?preg_replace("/[^0-9]/", "", $post['telefone']):"",
			'celular'=>(isset($post['celular']) && $post['celular']!="")?preg_replace("/[^0-9]/", "", $post['celular']):"",
			'site'=>(isset($post['site']) && $post['site']!="")?$post['site']:"",
			'email'=>(isset($post['email']) && $post['email']!="")?$post['email']:""
			));
		$cadastrar = self::create(array(
			'razao_social'=>$post['razao_social'],
			'cnpj'=>$post['cnpj'],
			'ofertas' => false,
			'usuario_id' => $id_user,
			'instituicaoscol' => $post['areaAtuacao'],
			'contato_id'=>$contato->cadastro->id,
			));
		if($cadastrar->is_valid() && $contato->status){
			$callback->status = true;
			$callback->user = $cadastrar;
			$usuario = Usuario::find($id_user);
				$usuario->funcoe_id = 2;
				$usuario->cidade_id = $post['cidade_id2'];
				$usuario->endereco = $post['logradouro'];
				$usuario->cep = preg_replace("/[^0-9]/", "", $post['cep']);
				$usuario->numero = $post['numero'];
				$usuario->complemento = $post['complemento'];
				$usuario->bairro = $post['bairro'];
				$usuario->save();
		}else{
			$errors = $cadastrar->errors->get_raw_errors();
			foreach ($errors as $campo => $messagem) {
				array_push($callback->errors, $messagem[0]);
			}
			if(!$contato->status){
				$errors = $contato->errors->get_raw_errors();
				foreach ($errors as $campo => $messagem) {
					array_push($callback->errors, $messagem[0]);
				}
			}else{
				$contato->delete();
			}
		}

		return $callback;
	}
	
	public static function editar($atributos,$id)
	{
		$callback = new \stdClass;
		$callback->status = false;
		$callback->ins = null;
		$callback->errors = [];

		if(!empty($atributos)){
			$editar = self::find_by_usuario_id($id);
			$editar->update_attributes($atributos);
			$editar->save();
			if($editar->is_valid())
			{
				$callback->status = true;
				$callback->ins = $editar;
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

	public static function mostrarPerfil($id_usuario)
	{
		$string_cargos = null;
		$vagas = null;
		$ofertante = null;
		$layout = null;


		$ofertante = self::find_by_usuario_id($id_usuario);
		$vagas = self::find_by_sql("select 
									c.nome as Cargo
									from
									getstagio.instituicaos as i 
									inner join getstagio.cargo_has_instituicaos as ci on i.id = ci.instituicao_id
									inner join getstagio.cargos as c on ci.cargo_id = c.id
									inner join getstagio.vagas as v on ci.id = v.cargo_has_instituicao_id
									where v.status = 1 AND i.id = $ofertante->id
									group by Cargo
									order by Cargo");
		$total = self::find_by_sql("select 
									sum(v.qnt) as QNTD
									from
									getstagio.instituicaos as i 
									inner join getstagio.cargo_has_instituicaos as ci on i.id = ci.instituicao_id
									inner join getstagio.cargos as c on ci.cargo_id = c.id
									inner join getstagio.vagas as v on ci.id = v.cargo_has_instituicao_id
									where v.status = 1 AND i.id = $ofertante->id
									");
		foreach ($vagas as $key => $value) {
			$string_cargos .= $value->cargo.", ";	
		}
		$layout = " 
				<div class='tab-pane active' id='tab_default_1'>
						Ofertante
										<p>
											A empresa <strong>".$ofertante->razao_social."</strong> de CNPJ <strong>".$ofertante->cnpj."</strong> está ofetanto vagas nas areás de $string_cargos somando um total de ".$total[0]->qntd." vagas dentre os cargos informados.
										</p>
										
									</div>
									<div class='tab-pane' id='tab_default_2'>
										<p>
											Detalhes
										</p>
										<div class='row'>
											<div class='col-sm-6'>
												<div class='form-group'>
													<label for='email'>Estado:</label>
													<p> "           .$ofertante->usuario->cidade->estado->sigla." - ".$ofertante->usuario->cidade->estado->estados." </p>
												</div>
												<div class='form-group'>
													<label for='email'>Cidade:</label>
													<p> ".$ofertante->usuario->cidade->nome." </p>
												</div>
												<div class='form-group'>
													<label for='email'>Telefone:</label>
													<p> ".$ofertante->telefone."</p>
												</div>
												<div class='form-group'>
													<label for='email'>Endereço:</label>
													<p> ".$ofertante->usuario->endereco." Nº ".$ofertante->usuario->numero."</p>
												</div>
											</div>
											<div class='col-sm-6'>
												<div class='form-group'>
													<label for='email'>CEP:</label>
													<p> ".$ofertante->usuario->cep."</p>
												</div>
											</div>
										</div>
									</div>
		";
		return $layout;
	}
}