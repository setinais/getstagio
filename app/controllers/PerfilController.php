<?php

/**
* 
*/
class PerfilController extends \HXPHP\System\Controller
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

	public function indexAction($id_usuario = null)
	{
		if(is_null($id_usuario) || !is_numeric($id_usuario))
			$this->auth->redirectCheck(true);
		$perfil = Usuario::mostrarPerfil($id_usuario);
		$this->view->setVars(["perfil" => $perfil])->setAssets('css',[$this->configs->baseURI.'public/css/perfil/index.css']);
	}
}