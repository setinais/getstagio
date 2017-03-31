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
		$this->view->setFile('vagas'.$this->auth->getUserRole())->setVar('vagas', Vaga::search($id_user,$filtros,$this->auth->getUserRole()));
	}

	public function criarAction()
	{
		$post = $this->request->post();
		$this->view->setVars(['request' => $post]);
	}g
}