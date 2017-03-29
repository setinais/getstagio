<?php 

class IndexController extends \HXPHP\System\Controller
{
	private $requestpag;

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

	public function informacoesBasicasAction()
	{
		$post = $this->request->post();
		if(!empty($post))
		{
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
		$this->view->setVars(['request' => $this->requestpag, 'estados' => Estado::getEstados()]);
	}

	public function perfilAction()
	{
		$post = $this->request->post();
		if(!empty($post))
		{
			($this->auth->getUserRole() === "Estudante" ? $callback = Estudante::editar($post,$this->auth->getUserId()) : $callback = Instituicao::editar($post,$this->auth->getUserId()) );
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
		($this->auth->getUserRole() == "Estudante" ? $this->requestpag = Estudante::find_by_usuario_id($this->auth->getUserId()) : $this->requestpag = Instituicao::find_by_usuario_id($this->auth->getUserId()) );
		$this->view->setFile('perfil'.$this->auth->getUserRole())->setVar('request', $this->requestpag);
	}
}