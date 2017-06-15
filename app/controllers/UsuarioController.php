<?php
/**
* 
*/
class UsuarioController extends \HXPHP\System\Controller
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
	public function informacoesBasicasAction()
	{
		$post = $this->request->post();
		if(!empty($post))
		{
			$post['cep'] = str_replace('-', "", $post['cep']);
			$callback = Usuario::editar($post,$this->auth->getUserId());
			if($callback->status === true)
			{
				$this->load('Helpers\Alert',[
	                    'success',
	                    'Salvo',
	     				'Dados alterados com sucesso.'
	                    ]);

			}
			else
			{
	            $this->load('Helpers\Alert',[
	                    'danger',
	                    'Atenção',
	     				$callback->errors
	                    ]);
			}
		}
		$this->requestpag = Usuario::find_by_id($this->auth->getUserId());
		$this->view->setVars(['request' => $this->requestpag, 'estados' => Estado::getEstados()])->setAssets('js',[$this->configs->baseURI."public/js/jquery.js",$this->configs->baseURI."public/js/home/informacoesbasicas.js",$this->configs->baseURI."public/js/jquery.1.7.7.mask.min.js"]);
	}

	public function perfilAction()
	{
		$post = $this->request->post();
		if(!empty($post)){
			if($callback->status === true){
				$this->load('Helpers\Alert',[
	                    'success',
	                    'Salvo',
	     				'Dados alterados com sucesso.'
	                    ]);

			}else{
	            $this->load('Helpers\Alert',[
	                    'danger',
	                    'Atenção',
	     				$callback->errors
	                    ]);
			}
		}
		if($this->auth->getUserRole() == "Estudante"){
			$callback = Estudante::editar($post,$this->auth->getUserId());
			$this->view->setVar('estudante',Estudante::find_by_usuario_id($this->auth->getUserId()));
		}else{
			$callback = Instituicao::editar($post,$this->auth->getUserId()) ;
			$this->view->setVar('instituicao',Estudante::find_by_usuario_id($this->auth->getUserId()));
		}
			
		($this->auth->getUserRole() == "Estudante" ? $this->requestpag = Estudante::find_by_usuario_id($this->auth->getUserId()) : $this->requestpag = Instituicao::find_by_usuario_id($this->auth->getUserId()) );
		$this->view->setFile('perfil'.$this->auth->getUserRole())->setVar('request', $this->requestpag)->setAssets('js',
			[$this->configs->baseURI.'public/js/jquery.js',$this->configs->baseURI.'public/js/perfil/perfil.js',$this->configs->baseURI.'public/js/jquery.1.7.7.mask.min.js',$this->configs->baseURI.'public/js/validaCpfCnpj.js'
			]);
	}
}