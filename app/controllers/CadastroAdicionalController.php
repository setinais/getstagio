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
            $this->load(
				'Helpers\Menu',
				$this->request,
				$this->configs,
				$this->auth->getUserRole()
			);
    		$this->auth->redirectCheck();
	}
	public function indexAction($acao=null)
	{
			$post = $this->request->post();
			
			if(!is_null($acao))
			{
				switch ((int) $acao) {
					case 1:
							$callback = Estudante::cadastrar($post,$this->auth->getUserId());
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
			$this->view->setVar('request',$post)->setAssets('js',[$this->configs->baseURI.'public/js/jquery.js',$this->configs->baseURI.'public/js/cadastro/cadastro.js',$this->configs->baseURI.'public/js/jquery.1.7.7.mask.min.js' ]);

		
		
	}
}

