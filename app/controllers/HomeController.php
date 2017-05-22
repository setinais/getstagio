<?php 

class HomeController extends \HXPHP\System\Controller
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
            $usuario = Usuario::find_by_id($this->auth->getUserId());
           	$this->load(
				'Helpers\Menuget',
				$usuario,
				$configs,
				$this->request->controller,
				$this->request->action
			);
    		$this->auth->redirectCheck();

    		if($this->auth->getUserRole() == 'default')
    		{
    			$this->redirectTo($this->configs->baseURI.'cadastroadicional');
    		}

	}
	public function indexAction()
	{

	}
}