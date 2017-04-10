<?php
/**
* 
*/
class EstagioController extends \HXPHP\System\Controller
{
	
	function __construct($configs)
	{
			parent::__construct($configs);
            $this->load(
                    'Services\Auth',
                    $configs->auth->after_login,
                    $configs->auth->after_logout,
                    true
      
                );
            $this->load(
				'Helpers\Menu',
				$this->request,
				$this->configs,
				$this->auth->getUserRole()
			);
    		$this->auth->redirectCheck();

	}

	public function listAction()
	{
		$this->view->setAssets('js',[$this->configs->baseURI.'public/js/jquery.js',$this->configs->baseURI.'public/js/estagio/list.js'])
					->setFile('vagas'.$this->auth->getUserRole())
					->setVar('vagas',  Vaga::search($this->auth->getUserId()))
					->setVar('requisitos',Requisito::search($this->auth->getUserId()));
	}

	public function criarAction($acao=null)
	{
		$post = $this->request->post();
		if(!is_null($acao))
		{
			switch ($acao) {
				case '1':
						$cad_vaga = Vaga::cadastrar($post,$this->auth->getUserId());
						
						if($cad_vaga->status === true)
						{
							$this->load('Helpers\Alert',[
								'success',
								'Salvo',
								'Vaga criado com sucesso.'
								]);
							
							$i=1;
							foreach ($post as $key => $value) {
								if($key == "requisito-".$i){
									//aqui faz a inserção no BD
									Requisito::cadastrar($value,$cad_vaga->user->id);
									$i++;
								}
							}
							$post = null;
						}
						else
						{
							$this->load('Helpers\Alert',[
								'danger',
								'Não foi possivel Cadastrar, devido aos erros abaixo:',
								$cad_vaga->errors
								]);
						}
					break;
				case '2':
						$cad_cargo = Cargo::cadastrar($post);
						
						if($cad_cargo->status === true)
						{
							$this->load('Helpers\Alert',[
								'success',
								'Salvo',
								'Cargo criado com sucesso.'
								]);
							$post = null;
						}
						else
						{
							$this->load('Helpers\Alert',[
								'danger',
								'Não foi possivel Cadastrar, devido aos erros abaixo:',
								$cad_cargo->errors
								]);
							$post = null;
						}
					break;
				default:
					$this->redirectTo($this->configs->baseURI.'estagio/criar');
					break;
			}
		}
		$this->view->setAssets('js',[$this->configs->baseURI.'public/js/jquery.js',$this->configs->baseURI.'public/js/estagio/cadastroVaga.js']);
		$this->view->setAssets('css',[$this->configs->baseURI."public/css/vaga/vaga.css"]);
		
		$this->view->setVars(['request' => $post, 'cargos' => Cargo::all()]);
	}

	public function finalizarVagaAction($id)
	{
		$ids = explode("-", $id);
		for ($v = 0; $v < count($ids)-1;$v++) {
			Vaga::finalizarVaga($ids[$v]);
		}
		$this->redirectTo($this->configs->baseURI."estagio/list");
	}

	public function reabrirVagaAction($id)
	{
		$ids = explode("-", $id);
		for ($v = 0; $v < count($ids)-1;$v++) {
			Vaga::reabrirVaga($ids[$v]);
		}
		$this->redirectTo($this->configs->baseURI."estagio/list");
	}

	public function eliminarVagaAction($id)
	{
		$ids = explode("-", $id);
		unset($ids[count($ids)-1]);
		Vaga::eliminarVaga($ids);
		$this->redirectTo($this->configs->baseURI."estagio/list");
	}

	public function editarVagaAction($id,$acao=null)
	{
		$post = $this->request->post();

		if (!is_null($acao)) {
			var_dump($post);
				$alt_vaga = Vaga::editarVaga($id,$post);
						
						if($alt_vaga->status === true)
						{
							$requisitos_del = Requisito::find('all',['conditions' => ['vaga_id = ?',$id]]);
							foreach ($requisitos_del as $key1 => $value1) {
								$requisito = Requisito::find($value1->id);
 								$requisito->delete();
							}
							
							$this->load('Helpers\Alert',[
								'success',
								'Salvo',
								'Vaga criado com sucesso.'
								]);
							$i=1;
							foreach ($post as $key => $value) {
								if($key == "requisito-".$i){
									//aqui faz a inserção no BD
									Requisito::cadastrar($value,$id);
									$i++;
								}
							}
							$this->redirectTo($this->configs->baseURI."estagio/list");
						}
						else
						{
							$post = Vaga::find($id);

							$this->load('Helpers\Alert',[
								'danger',
								'Não foi possivel Cadastrar, devido aos erros abaixo:',
								$alt_vaga->errors
								]);

						}
		}
		else
		{
			$post = Vaga::find($id);
		}

		$this->view->setAssets('js',[$this->configs->baseURI.'public/js/jquery.js',$this->configs->baseURI.'public/js/estagio/cadastroVaga.js'])
		->setAssets('css',[$this->configs->baseURI."public/css/vaga/vaga.css"])
		->setVars(['requisitos' => Requisito::find('all',['conditions' => ['vaga_id = ?',$id]]),'request' => $post, 'cargos' => Cargo::all()]);
	}

	public function candidatarAction()
	{
		$vagas = Vaga::all();
		$estrutura_vagas = null;
		foreach ($vagas as $key => $value) {
			$estrutura_vagas_head = null;
			$estrutura_vagas_section = null;
			$estrutura_vagas_footer = null;

			$estrutura_vagas_head = "<tr>
			<td>
				<h4 class='busca'>".$value->cargo_has_instituicao->cargo->nome."</h4>
			</td>
			<td>";
				$search_requisitos = Requisito::searchRequisitos($value->id);
				if(!is_null($search_requisitos)){
					foreach ($search_requisitos as $indei => $requisitos) {
						$estrutura_vagas_section .= "<span class='label label-default busca'>".$requisitos->requisito."</span>";
					}
				}
				$estrutura_vagas_footer = "</td>
				<td>".$value->remuneracao."</td>
				<td><?=?></td>
				<td><a href=''><span class='label label-success'><span class='glyphicon glyphicon-log-in'></span></span></a></td>
			</tr>";
			$estrutura_vagas[] = $estrutura_vagas_head.$estrutura_vagas_section.$estrutura_vagas_footer;
		}
		$this->view->setAssets('js',[$this->configs->baseURI."public/js/jquery.js",$this->configs->baseURI.'public/js/cadastro/candidatar.js']);
		$this->view->setVars(['vagas' => $estrutura_vagas]);
	}
}