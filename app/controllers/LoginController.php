<?php
/**
* 
*/
class LoginController extends \HXPHP\System\Controller
{
	
	 function __construct($configs){
    		parent::__construct($configs);
            $this->load(
                    'Services\Auth',
                    $this->configs->auth->after_login,
                    $this->configs->auth->after_logout,
                    true,
                    $this->request->subfolder
                );
    	}
        public function indexAction(){

            $this->auth->redirectCheck(true);

        }
        public function logarAction(){

    		$post = $this->request->post();
            
            $callback = Usuario::logar($post);
            if ($callback->status) 
            {

                 $this->auth->login($callback->user->id, $callback->user->email, $callback->user->funcoe->tipo);
            }
            else
            {
                 $this->load('Helpers\Alert',[
                    'danger',
                    'Atenção!',
                    $callback->alert
                    ]);
                   
            }
            $this->view->setFile('index');
           
            
        }
        public function sairAction(){
        	$this->auth->logout();
        }
}