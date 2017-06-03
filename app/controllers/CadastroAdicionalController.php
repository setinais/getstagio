<?php
/**
* 
*/
class CadastroAdicionalController extends HXPHP\System\Controller
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
            $usuario = Usuario::find_by_id($this->auth->getUserId());
           	$this->load(
				'Helpers\Menuget',
				$usuario,
				$configs,
				$this->request->controller
			);
    		$this->auth->redirectCheck();
	}
	public function ajaxAction($type=""){
		$this->view->setTemplate(false);$txt="";
		if($type == "carregaCidade"){
			if(!empty($this->request->post()['cidade_id'])){
				foreach(Cidade::find_all_by_estado_id($this->request->post()['cidade_id']) as $val){
					$txt .= "<option value='".$val->id."'>".$val->nome."</option>";
				}
			}
			echo $txt;
		}
		echo $txt;
	}
	public function indexAction($acao=null)
	{
			$post = $this->request->post();
			
			if(!is_null($acao))
			{
				switch ((int) $acao) {
					case 1:
							$post['bairro'] = "alto pso";
							$callback = Estudante::cadastrar($post,$this->auth->getUserId());
							if($callback->status === true){
									 $this->redirectTo($this->configs->baseURI."login/sair");
							}else{
								 $this->load('Helpers\Alert',[
				                    'danger',
				                    'Ops! Não foi possivel efetuar seu cadastro. Verifique os erros abaixo',
				                    $callback->errors
				                    ]);
							}
						break;
					case 2: 
							$callback = Instituicao::cadastrar($post,$this->auth->getUserId());
							if($callback->status === true)
							{
									 $this->redirectTo($this->configs->baseURI."login/sair");
							}
							else
							{
								 $this->load('Helpers\Alert',[
				                    'danger',
				                    'Ops! Não foi possivel efetuar seu cadastro. Verifique os erros abaixo',
				                    $callback->errors
				                    ]);

							}
						break;
					default:
						$this->redirectTo($this->configs->baseURI.'cadastroadicional/');
						break;
				}
			}
			$post['email'] = Usuario::find($this->auth->getUserId())->email;
			$this->view->setVar('request',$post)->setAssets('js',[$this->configs->baseURI.'public/js/jquery.js',$this->configs->baseURI.'public/js/cadastro/cadastro.js',$this->configs->baseURI.'public/js/jquery.1.7.7.mask.min.js',$this->configs->baseURI.'public/js/validaCpfCnpj.js']);

		
		
	}
}

