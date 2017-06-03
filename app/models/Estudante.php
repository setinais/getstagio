<?php

/**
* 
*/
class Estudante extends \HXPHP\System\Model
{
	static $belongs_to = [
		['usuario'],
		['cargo'],
		['contato']
	];
	static $has_many = [
		['cadastros'],
		['formacos'],
		['conhecimento_escritorios'],
		['conhecimento_sistemas'],
		['informacoes_complementares'],
		['idiomas'],
		['formacoes_complementares']
	];
	static $validates_presence_of = [
			[
				'nome',
				'message' => '<strong>Nome</strong> é um campo obrigatório.'
			],
			[
				'deficiencia',
				'message' => '<strong>Escolha se você tem ou não deficiencia</strong> é um campo obrigatório.'
			],
			[
				'data_nasc',
				'message' => '<strong>Data de nascimento</strong> é um campo obrigatorio.'
			]
			
	];
	/*static $validates_uniqueness_of  = [
			[
				['cpf'],
				'message'=>'Já existe um estudante cadastrado com este <strong>CPF</strong>.'
			]
	];*/

	public static function cadastrar($post,$id_user)
	{
		$callback = new \stdClass;
		$callback->status = false;
		$callback->user = null;
		$callback->errors = [];
		$post['usuario_id'] = $id_user;
		$insert['cargo'] = Cargo::cadastrar($post);
		$insert['contato'] = Contato::cadastrar($post);
		if($insert['cargo']->status && $insert['contato']->status){
			$cadastrar = self::create(array(
				'nome'=>$post['nome'],
				'data_nasc'=>$post['dataNascimento'],
				'sexo'=>$post['sexo'],
				'cpf'=>$post['cpf'],
				'deficiencia'=>$post['def'],
				'especificacao_deficiencia'=>($post['def']=="nao")?"":$post['espDeficiencia'],
				'usuario_id'=>$post['usuario_id'],
				'cargo_id'=>$insert['cargo']->cadastro->id,
				'contato_id'=>$insert['contato']->cadastro->id
				));		
			if($cadastrar->is_valid()){
				//formacoes
				$formacao = array(
					'formacao'=>'Ensino Medio',
					'instituicao_ensino'=>$post['instEM'],
					'situacao_curso'=>$post['sitEM'],
					'serie_modulo_periodo'=>(isset($post['serieEM']))?$post['serieEM']:"",
					'ano_inicio'=>(isset($post['iniEM']))?$post['iniEM']:"",
					'ano_termino'=>(isset($post['fimEM']))?$post['fimEM']:"",
					'estudante_id'=>$cadastrar->id
					);
				$insert['formacoM'] = Formaco::cadastrar($formacao);
				if(isset($post['ensinoTec']) && $post['ensinoTec'] == "on"){
					$formacao = array(
						'formacao'=>'Ensino Tecnico',
						'instituicao_ensino'=>$post['instituicaoTec'],
						'curso'=>$post['cursoTec'],
						'situacao_curso'=>$post['situacaoTec'],
						'serie_modulo_periodo'=>(isset($post['serieTec']))?$post['serieTec']:"",
						'ano_inicio'=>(isset($post['anoTecInicio']))?$post['anoTecInicio']:"",
						'ano_termino'=>(isset($post['anoTecTermino']))?$post['anoTecTermino']:"",
						'estudante_id'=>$cadastrar->id
						);
					$insert['formacoT'] = Formaco::cadastrar($formacao);
				}
				if(isset($post['ensinoSup']) && $post['ensinoSup'] == "on"){
					$formacao = array(
						'formacao'=>'Ensino Superior',
						'instituicao_ensino'=>$post['instituicaoSup'],
						'curso'=>$post['cursoSup'],
						'situacao_curso'=>$post['situacaoSup'],
						'serie_modulo_periodo'=>(isset($post['periodoSup']))?$post['periodoSup']:"",
						'ano_inicio'=>(isset($post['anoSupInicio']))?$post['anoSupInicio']:"",
						'ano_termino'=>(isset($post['anoSupTermino']))?$post['anoSupTermino']:"",
						'estudante_id'=>$cadastrar->id
						);
					$insert['formacoS'] = Formaco::cadastrar($formacao);
				}
				//formaçoes
				//idiomas
				$idioma = array('ingles'=>"",'espanhol'=>"");
				if(isset($post['idioma']) && $post['idioma']!=""){
					$insert['idioma']=Idioma::cadastrar(array(
						"idioma"=>$post['idioma'],
						'le'=>$post['idiomaLe'],
						'fala'=>$post['idiomaEscreve'],
						'escreve'=>$post['idiomaFala'],
						'estudante_id'=>$cadastrar->id
						));
				}
				foreach($idioma as $key => $valad){
					$insert[] = Idioma::cadastrar(array(
						"idioma"=>$key,
						'le'=>$post[$key.'Le'],
						'fala'=>$post[$key.'Fala'],
						'escreve'=>$post[$key.'Escreve'],
						'estudante_id'=>$cadastrar->id
						));
				}
				//idiomas
				//formação complementar
				if(!empty($post['nomeIstituicao']) && !empty($post['cursoInstituicao']) && !empty($post['cargaHInstituicao'])){
					$insert['formacaoC'] = FormacoesComplementare::cadastrar(array(
						'instituicao'=>$post['nomeIstituicao'],
						'curso'=>$post['cursoInstituicao'],
						'carga_horaria'=>$post['cargaHInstituicao'],
						'estudante_id'=>$cadastrar->id
						));
				}
				//formação complementar
				//conhecimentos escritorios
				foreach($post as $key => $valu){
					if(Escritorio::existe($valu)){
						$insert[] = ConhecimentoEscritorio::cadastrar($cadastrar->id,Escritorio::find_by_nome($valu)->id);
					}
				}
				//conhecimentos escritorios
				//conhecimentos sistemas
				foreach($post as $key => $valu){
					if(Sistema::existe($valu)){
						$insert[] = ConhecimentoSistema::cadastrar($cadastrar->id,Sistema::find_by_nome($valu)->id);
					}
				}
				//conhecimentos sistemas
				// infromaçoes complementares
				$ifocomps = array(
					'disponibilidade_jornada'=>$post['disponibilidadeTurno'],
					'disponibilidade_ch_diaria'=>$post['cargaHDiaria'],
					'desc_objetivos'=>$post['descricaoEobjetivo'],
					'estudante_id'=>$cadastrar->id
					);
				$insert[] = informacoesComplementare::cadastrar($ifocomps);
				// infromaçoes complementares
				$callback->status = true;
				$callback->user = $cadastrar;
				$usuario = Usuario::find($id_user);
				$usuario->funcoe_id = 1;
				$usuario->cidade_id = $post['cidade_id'];
				$usuario->endereco = $post['logradouroEstudante'];
				$usuario->cep = preg_replace("/[^0-9]/", "", $post['cep']);
				$usuario->numero = $post['numeroEstudante'];
				$usuario->complemento = $post['complementoEstudante'];
				$usuario->nome = $post['nome'];
				$usuario->bairro = $post['bairro'];
				$usuario->save();

				foreach($insert as $key => $val){
					if($val->status == false){
						$errors = $cadastrar->errors->get_raw_errors();
						foreach ($errors as $campo => $messagem) {
							array_push($callback->errors, $messagem[0]);
						}
					}
				}
				if(!empty($callback->errors)){
					foreach ($insert as $key => $value) {
						$value->delete();
					}
				}
			}else{
				$errors = $cadastrar->errors->get_raw_errors();
				foreach ($errors as $campo => $messagem) {
					array_push($callback->errors, $messagem[0]);
				}
			}
		}else{
			array_push($callback->errors, $insert['contato']->errors);
			array_push($callback->errors, $insert['cargo']->errors);
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
			$editar = self::find_by_usuario_id($id);
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
	public static function mostrarPerfil($id_usuario)
	{
		$estudante = self::find_by_usuario_id($id_usuario);
		$telefone = null;
		if(!is_null($estudante->contato))
			foreach ($estudante->contato as $key => $value) {
				 $telefone = $value->telefone;
			}
		$layout = " 
				<div class='tab-pane active' id='tab_default_1'>
						Estudante
										<p>
											
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
													<p> "           .$estudante->usuario->cidade->estado->sigla." - ".$estudante->usuario->cidade->estado->estados." </p>
												</div>
												<div class='form-group'>
													<label for='email'>Cidade:</label>
													<p> ".$estudante->usuario->cidade->nome." </p>
												</div>
												<div class='form-group'>
													<label for='email'>Telefone:</label>
													<p> ".$telefone."</p>
												</div>
												<div class='form-group'>
													<label for='email'>Endereço:</label>
													<p> ".$estudante->usuario->endereco." Nº ".$estudante->usuario->numero."</p>
												</div>
											</div>
											<div class='col-sm-6'>
												<div class='form-group'>
													<label for='email'>CEP:</label>
													<p> ".$estudante->usuario->cep."</p>
												</div>
											</div>
										</div>
									</div>
		";
		return $layout;
	}
}