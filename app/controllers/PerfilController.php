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
            $usuario = Usuario::find_by_id($this->auth->getUserId());
           	$this->load(
				'Helpers\Menuget',
				$usuario,
				$configs,
				$this->request->controller
			);
    		$this->auth->redirectCheck();
	}

	public function indexAction($id_usuario = null)
	{
		//'https://www.receitaws.com.br/v1/cnpj/22948361000105' url API
		
		if(is_null($id_usuario) || !is_numeric($id_usuario))
			$this->auth->redirectCheck(true);
		$test = Usuario::find_by_id($id_usuario);
		if(!isset($test))
			$this->auth->redirectCheck(true);
		$perfil = Usuario::mostrarPerfil($id_usuario);
		$this->view->setVars(["perfil" => $perfil])->setAssets('css',[$this->configs->baseURI.'public/css/perfil/index.css']);
	}
}