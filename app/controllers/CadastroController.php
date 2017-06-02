<?php
/**
* 
*/
class CadastroController extends \HXPHP\System\Controller
{
	function __construct($configs)
	{
		parent::__construct($configs);
		$this->load(
                    'Services\Auth',
                    $this->configs->auth->after_login,
                    $this->configs->auth->after_logout,
                    $this->request->subfolder
                );
		$this->auth->redirectCheck(true);
	}
	public function indexAction()
	{
		$this->redirectTo($this->configs->baseURI.'cadastro/cadastrousuario/');
	}
	public function cadastroUsuarioAction()
	{
        $post = $this->request->post();
		if(!empty($post))
		{
			$callback = Usuario::cadastrar($post);
			if($callback->status === true)
			{
					 $this->auth->login($callback->user->id, $callback->user->nome, $callback->user->funcoe->tipo);
			}
			else
			{
				 $this->load('Helpers\Alert',[
                    'danger',
                    'Ops! Não foi possível efetuar seu cadastro. Verifique os erros abaixo:',
                    $callback->errors
                    ]);

			}
		}
		$this->view->setAssets('js',[$this->configs->baseURI.'public/js/jquery.js',$this->configs->baseURI.'public/js/cadastro/cadastroUsuario.js',$this->configs->baseURI.'public/js/jquery.1.7.7.mask.min.js'])->setVars(["request" => $post, "estados" => Estado::getEstados()]);
        $this->view->setAssets('css',[$this->configs->baseURI.'public/css/cadastro/cadastro.css']);
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
}