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

	public function listAction($filtros = null)
	{
		$this->view->setFile('vagas'.$this->auth->getUserRole())->setVar('vagas', ($this->auth->getUserRole() == "Instituicao" ? Vaga::search($this->auth->getUserId(),$filtros) : Vaga::search($filtros)));
	}

	public function criarAction($acao=null)
	{
		$post = $this->request->post();
		if(!is_null($acao))
		{
			switch ($acao) {
				case '1':
						//$cad_vaga = Vaga::cadastrar(,$this->auth->getUserId());
						var_dump($post);
						$i=1;
						foreach ($post as $key => $value) {
							if($key == "requisito-".$i){
								//aqui faz a inserção no BD
								requisito::create(array('requisito'=>$value,"vaga_id"=>$cad_vaga->id));
							}
						}
						/*if($cad_vaga->status === true)
						{
							$this->load('Helpers\Alert',[
								'success',
								'Salvo',
								'Vaga criado com sucesso.'
								]);
							$post = null;
						}
						else
						{
							$this->load('Helpers\Alert',[
								'danger',
								'Não foi possivel Cadastrar, devido aos erros abaixo:',
								$cad_vaga->errors
								]);
						}*/
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
		$this->view->setAssets('js',[$this->configs->baseURI.'public/js/jquery.js',$this->configs->baseURI.'public/js/vaga/cadastroVaga.js']);
		$this->view->setAssets('css',[$this->configs->baseURI."public/css/vaga/vaga.css"]);
		
		$this->view->setVars(['request' => $post, 'cargos' => Cargo::all()]);
	}
}