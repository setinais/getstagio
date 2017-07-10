
<?php

/**
* 
*/
class Estudante extends \HXPHP\System\Model
{
	static $belongs_to = [
	['usuario'],
	['stcargo'],
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
	static $validates_uniqueness_of  = [
	[
	['cpf'],
	'message'=>'Já existe um estudante cadastrado com este <strong>CPF</strong>.'
	]
	];

	public static function cadastrar($post,$id_user)
	{
		$callback = new \stdClass;
		$callback->status = false;
		$callback->user = null;
		$callback->errors = [];
		$post['usuario_id'] = $id_user;
		$insert['cargo'] = Stcargo::cadastrar($post);
		$insert['contato'] = Contato::cadastrar(array(
			'telefone'=>(isset($post['telefoneEstudante']) && $post['telefoneEstudante']!="")?preg_replace("/[^0-9]/", "", $post['telefoneEstudante']):"",
			'celular'=>(isset($post['celularEstudante']) && $post['celularEstudante']!="")?preg_replace("/[^0-9]/", "", $post['celularEstudante']):"",
			'site'=>(isset($post['site']) && $post['site']!="")?$post['site']:"",
			));
		if($insert['cargo']->status && $insert['contato']->status){
			$cadastrar = self::create(array(
				'nome'=>$post['nome'],
				'data_nasc'=>$post['dataNascimento'],
				'sexo'=>$post['sexo'],
				'cpf'=>preg_replace("/[^0-9]/", "", $post['cpf']),
				'deficiencia'=>$post['def'],
				'especificacao_deficiencia'=>($post['def']=="nao")?"":$post['espDeficiencia'],
				'usuario_id'=>$post['usuario_id'],
				'stcargo_id'=>$insert['cargo']->cadastro->id,
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
				$usuario->complemento = isset($post['complemento'])?$post['complemento']:"";
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


	public static function editar($post,$id){
		$callback = new \stdClass;
		$callback->status = false;
		$callback->est = null;
		$callback->errors = [];
		$insert['user'] = Usuario::editar(array(
		'cidade_id'=> $post['cidade'],
		'endereco'=> $post['endereco'],
		'cep'=> preg_replace("/[^0-9]/", "", $post['cep']),
		'numero'=> $post['numero'],
		'complemento'=> $post['complemento'],
		'bairro'=> $post['bairro'],
		'email'=>$post['email'] ),
		$id );
		$insert['contato'] = Contato::editar(array(
			'telefone'=>(isset($post['telefone']) && $post['telefone']!="")?preg_replace("/[^0-9]/", "", $post['telefone']):"",
			'celular'=>(isset($post['celular']) && $post['celular']!="")?preg_replace("/[^0-9]/", "", $post['celular']):"",
			'site'=>(isset($post['site']) && $post['site']!="")?$post['site']:"",
			),$id);
		$editar = self::find_by_usuario_id($id);
		$editar->update_attributes(array(
			'nome'=>$post['nome'],
			'cpf'=>preg_replace("/[^0-9]/", "", $post['cpf']),
			'sexo'=>$post['sexo'],
			'data_nasc'=>$post['data_nasc']
			));
		$editar->save();
		foreach($insert as $key => $val){
			if($val->status == false){
				$errors = $val->errors;
				foreach ($errors as $campo => $messagem) {
					array_push($callback->errors, $messagem);
				}
			}
		}
		if($editar->is_valid()){
			$callback->status = true;
			$callback->est = $editar;
		}else{
			$errors = $editar->errors->get_raw_errors();
			foreach ($errors as $campo => $messagem){
				array_push($callback->errors, $messagem[0]);
			}
		}
		return $callback;
	}


	public static function mostrarPerfil($id_usuario)
	{
		$estudante = self::find_by_usuario_id($id_usuario);
		$contato = "E-mail: ".$estudante->usuario->email."<br>";
		if(!empty($estudante->contato->telefone))
			$contato .= "Telefone: ".$estudante->contato->telefone."<br>";
		if(!empty($estudante->contato->celular))
			$contato .= "Celular: ".$estudante->contato->celular."<br>";
			$layout = " 
		<div class='panel-heading'>
			<h3 class='panel-title'>
			<button class='btn btn-warning' onclick='window.history.back()'>Voltar</button><br><br>".$estudante->nome."</h3>
		</div>
		<div class='panel-body'>
			<div class='col-sm-6'>
				<div class='form-group'>
					<label>CPF:</label>
					<p> ".$estudante->cpf." </p>
				</div>
				<div class='form-group'>
					<label>Data de nascimento:</label>
					<p> ".$estudante->data_nasc->format("d/m/Y")." </p>
				</div>
				<div class='form-group'>
					<label>Sexo:</label>
					<p> ".$estudante->sexo." </p>
				</div>
				<div class='form-group'>
					<label for='email'>Contato:</label>
					<p> ".$contato."</p>
				</div>
				<div class='form-group'>
					<label for='email'>Estado:</label>
					<p> ".$estudante->usuario->cidade->estado->estados." - ".$estudante->usuario->cidade->estado->sigla." </p>
				</div>
				</div>
				<div class='col-sm-6'>
				<div class='form-group'>
					<label for='email'>Cidade:</label>
					<p> ".$estudante->usuario->cidade->nome." </p>
				</div>
				<div class='form-group'>
					<label for='email'>CEP:</label>
					<p> ".$estudante->usuario->cep." </p>
				</div>
				<div class='form-group'>
					<label for='email'>Bairro:</label>
					<p> ".$estudante->usuario->bairro." </p>
				</div>
				<div class='form-group'>
					<label for='email'>Endereço:</label>
					<p> ".$estudante->usuario->endereco." Nº ".$estudante->usuario->numero."</p>
				</div>
				<div class='form-group'>
					<label for='email'>Complemento:</label>
					<p> ".$estudante->usuario->complemento."</p>
				</div>
			</div>
		</div>
		";
		return $layout;
	}
	public static function updateEst($estudante,$post){
		//var_dump($post);
		$estudante->update_attributes(array(
      		'nome' => !empty($post['nome']) && isset($post['nome'])?$post['nome']:'',
      		'data_nasc' => !empty($post['dataNascimento']) && isset($post['dataNascimento'])?$post['dataNascimento']:'',
      		'sexo' => !empty($post['sexo']) && isset($post['sexo'])?$post['sexo']:'',
      		'cpf' => !empty($post['cpf']) && isset($post['cpf'])?$post['cpf']:'',
      		'deficiencia'=>$post['def']=='sim'?1:0,
			'especificacao_deficiencia'=>($post['def']=="1")?"":$post['espDeficiencia'],
			));
		$estudante->contato->update_attributes(array(
			'telefone' => !empty($post['telefoneEstudante']) && isset($post['telefoneEstudante'])?$post['telefoneEstudante']:'',
  			'celular' => !empty($post['celularEstudante']) && isset($post['celularEstudante'])?$post['celularEstudante']:'',
		));
		$estudante->usuario->update_attributes(array(
			'endereco' => !empty($post['logradouroEstudante']) && isset($post['logradouroEstudante'])?$post['logradouroEstudante']:'',
      		'cep' => !empty($post['cep']) && isset($post['cep'])?$post['cep']:'',
      		'numero' => !empty($post['numeroEstudante']) && isset($post['numeroEstudante'])?$post['numeroEstudante']:'',
      		'bairro' =>!empty($post['bairro']) && isset($post['bairro'])?$post['bairro']:'',
      		'cidade_id' => !empty($post['cidade_id']) && isset($post['cidade_id'])?$post['cidade_id']:'',
		));
		if(isset($post['chIngles'])){
			$test = true;
			foreach($estudante->idiomas as $val){
				if($val->idioma == "ingles"){
					$val->update_attributes(array(
						'le'=> $post[$val->idioma."Le"],
						'fala'=> $post[$val->idioma."Fala"],
						'escreve'=> $post[$val->idioma."Escreve"]
						));
					$test = false;
				}
			}
			if($test){
				Idioma::cadastrar(array(
					"idioma"=>'ingles',
					'le'=>$post['inglesLe'],
					'fala'=>$post['inglesEscreve'],
					'escreve'=>$post['inglesFala'],
					'estudante_id'=>$estudante->id
				));
			}
		}
		if(isset($post['chEspanhol'])){
			$test = true;
			foreach($estudante->idiomas as $val){
				if($val->idioma == "espanhol"){
					$val->update_attributes(array(
						'le'=> $post[$val->idioma."Le"],
						'fala'=> $post[$val->idioma."Fala"],
						'escreve'=> $post[$val->idioma."Escreve"]
						));
					$test = false;
				}
			}
			if($test){
				Idioma::cadastrar(array(
					"idioma"=>'espanhol',
					'le'=>$post['espanholLe'],
					'fala'=>$post['espanholEscreve'],
					'escreve'=>$post['espanholFala'],
					'estudante_id'=>$estudante->id
				));
			}
		}
		if(isset($post['idioma']) && !empty($post['idioma'])){
			$test = true;
			foreach($estudante->idiomas as $val){
				if($val->idioma == $post['idioma']){
					$val->update_attributes(array(
						'le'=> $post["idiomaLe"],
						'fala'=> $post["idiomaFala"],
						'escreve'=> $post["idiomaEscreve"]
						));
					$test = false;
				}
			}
			if($test){
				foreach($estudante->idiomas as $val){
					if($val->idioma != "ingles" && $val->idioma != 'espanhol'){
						$val->delete();
					}
				}
				Idioma::cadastrar(array(
					"idioma"=>$post['idioma'],
					'le'=>$post['espanholLe'],
					'fala'=>$post['espanholEscreve'],
					'escreve'=>$post['espanholFala'],
					'estudante_id'=>$estudante->id
				));
			}
		}
		$estudante->formacoes_complementares[0]->update_attributes(array(
			'instituicao' => !empty($post['nomeIstituicao']) && isset($post['nomeIstituicao'])?$post['nomeIstituicao']:'',
          	'curso' => !empty($post['cursoInstituicao']) && isset($post['cursoInstituicao'])?$post['cursoInstituicao']:'',
          	'carga_horaria' => !empty($post['cargaHInstituicao']) && isset($post['cargaHInstituicao'])?$post['cargaHInstituicao']:'',
        ));
		foreach ($post as $key => $value) {
			if(preg_replace( '/\d+$/', null, $key ) == "escritorio"){
				if(Escritorio::existe($value)){
					$test = ConhecimentoEscritorio::find('all',array('conditions'=>array('estudante_id = ? and escritorio_id = ?',$estudante->id,Escritorio::find_by_nome($value)->id)));
					if(empty($test)){
						ConhecimentoEscritorio::cadastrar($estudante->id,Escritorio::find_by_nome($value)->id);
					}
				}
				$escritorio[] = $value;
			}
		}
		foreach ($estudante->conhecimento_escritorios as $val) {
			if(!in_array($val->escritorio->nome,$escritorio)){
				$val->delete();
			}
		}
		foreach ($post as $key => $value) {
			if(preg_replace( '/\d+$/', null, $key ) == "sistema"){
				if(Sistema::existe($value)){
					$test = ConhecimentoSistema::find('all',array('conditions'=>array('estudante_id = ? and sistema_id = ?',$estudante->id,Sistema::find_by_nome($value)->id)));
					if(empty($test)){
						ConhecimentoSistema::cadastrar($estudante->id,Sistema::find_by_nome($value)->id);
					}
				}
				$sistema[] = $value;
			}
		}
		foreach ($estudante->conhecimento_sistemas as $val) {
			if(in_array($val->sistema->nome,$sistema)){
				$val->delete();
			}
		}
		$estudante->informacoes_complementares[0]->update_attributes(array(
			'disponibilidade_jornada'=>$post['disponibilidadeTurno'],
			'disponibilidade_ch_diaria'=>$post['cargaHDiaria'],
			'area_interesse'=>$post['areaDeInteresse'],
			'desc_objetivos'=>$post['descricaoEobjetivo']
			));
		if($estudante->stcargo->nome != $post['cargo']){
			$estudante->stcargo->delete();
			Stcargo::cadastrar(array('cargo'=>$post['cargo']));
		}
		
		if(isset($post['ensinoMedio'])){
			$test = true;
			foreach($estudante->formacos as $val){
				if($val->formacao == "Ensino Medio"){
					$val->update_attributes(array(
						'formacao'=>'Ensino Medio',
						'instituicao_ensino'=>$post['instEM'],
						'situacao_curso'=>$post['sitEM'],
						'serie_modulo_periodo'=>(isset($post['serieEM']))?$post['serieEM']:"",
						'ano_inicio'=>(isset($post['iniEM']))?$post['iniEM']:"",
						'ano_termino'=>(isset($post['fimEM']))?$post['fimEM']:"",
					));
					$test = false;
				}
			}
			if($test){
				Formaco::cadastrar( array(
					'formacao'=>'Ensino Medio',
					'instituicao_ensino'=>$post['instEM'],
					'situacao_curso'=>$post['sitEM'],
					'serie_modulo_periodo'=>(isset($post['serieEM']))?$post['serieEM']:"",
					'ano_inicio'=>(isset($post['iniEM']))?$post['iniEM']:"",
					'ano_termino'=>(isset($post['fimEM']))?$post['fimEM']:"",
					'estudante_id'=>$estudante->id
					));
			}
		}


		if(isset($post['ensinoTec'])){
			$test = true;
			foreach($estudante->formacos as $val){
				if($val->formacao == "Ensino Tecnico"){
					$val->update_attributes(array(
						'formacao'=>'Ensino Tecnico',
						'instituicao_ensino'=>$post['instituicaoTec'],
						'curso'=>$post['cursoTec'],
						'situacao_curso'=>$post['situacaoTec'],
						'serie_modulo_periodo'=>(isset($post['serieTec']))?$post['serieTec']:"",
						'ano_inicio'=>(isset($post['anoTecInicio']))?$post['anoTecInicio']:"",
						'ano_termino'=>(isset($post['anoTecTermino']))?$post['anoTecTermino']:""
						));
					$test = false;
				}
			}
			if($test){
				Formaco::cadastrar(array(
						'formacao'=>'Ensino Tecnico',
						'instituicao_ensino'=>$post['instituicaoTec'],
						'curso'=>$post['cursoTec'],
						'situacao_curso'=>$post['situacaoTec'],
						'serie_modulo_periodo'=>(isset($post['serieTec']))?$post['serieTec']:"",
						'ano_inicio'=>(isset($post['anoTecInicio']))?$post['anoTecInicio']:"",
						'ano_termino'=>(isset($post['anoTecTermino']))?$post['anoTecTermino']:"",
						'estudante_id'=>$estudante->id
						));
			}
		}

		if(isset($post['ensinoSup'])){
			$test = true;
			foreach($estudante->formacos as $val){
				if($val->formacao == "Ensino Superior"){
					$val->update_attributes(array(
						'formacao'=>'Ensino Superior',
						'instituicao_ensino'=>$post['instituicaoSup'],
						'curso'=>(isset($post['cursoSup']))?$post['cursoSup']:"",
						'situacao_curso'=>$post['situacaoSup'],
						'serie_modulo_periodo'=>(isset($post['periodoSup']))?$post['periodoSup']:"",
						'ano_inicio'=>(isset($post['anoSupInicio']))?$post['anoSupInicio']:"",
						'ano_termino'=>(isset($post['anoSupTermino']))?$post['anoSupTermino']:""
						));
					$test = false;
				}
			}
			if($test){
				Formaco::cadastrar(array(
						'formacao'=>'Ensino Superior',
						'instituicao_ensino'=>$post['instituicaoSup'],
						'curso'=>$post['cursoSup'],
						'situacao_curso'=>$post['situacaoSup'],
						'serie_modulo_periodo'=>(isset($post['periodoSup']))?$post['periodoSup']:"",
						'ano_inicio'=>(isset($post['anoSupInicio']))?$post['anoSupInicio']:"",
						'ano_termino'=>(isset($post['anoSupTermino']))?$post['anoSupTermino']:"",
						'estudante_id'=>$estudante->id
						));
			}
		}

	}
}