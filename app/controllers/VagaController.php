<?php
/**
* 
*/
class VagaController extends \HXPHP\System\Controller
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
}