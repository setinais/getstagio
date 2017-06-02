<?php

namespace HXPHP\System\Helpers\Menuget;
/**
* 
*/
class Menuget
{
	private $url;

	private $usuario;

	private $controller;

	function __construct($usuario,$configs,$controller)
	{
		$this->usuario = $usuario;
		$this->url = $configs->baseURI;
		$this->controller = $controller;

	}

	public function getMenu()
	{
		return $this->menu();
	}

	private function menus($role){
		$nome_usu = explode("@",$this->usuario->email);
			switch ($role) {
				case 'Instituicao':
					$menu=array(
						'Home/home' => 'Home',
						'Minhas vagas/list-alt' => 'Listar'
						
					);
					break;
				case 'Estudante':
					$menu=array(
							'Home/home' => 'Home',
							'Minhas inscrições/list-alt' => 'Listar',
							'Vagas/newspaper-o' => 'Candidatar'
							
					);
					break;
				case 'default' :
					$menu = [
						'Home/home' => 'Home',
						'Complete seu cadastro/folder-open' => 'CadastroAdicional/'
					];
					break;
				case 'user':
						$menu = [
							$nome_usu[0].'/user' => [
								'Perfil/user-plus' => 'Usuario/perfil',
								'Sair/power-off' => 'login/sair'
							]
						];
					break;
			}
			return $menu;
		}
	
		/**
		 * Método responsável por renderizar o menu em HTML
		 * @param  object $user       Usuário logado
		 * @param  string $controller Controller atual
		 * @return string $html       HTML do menu
		 */
		private function render($user,$controller){
			if(is_null($user))
				return;
			$menus=$this->menus($user);
			$controller=str_replace("Controller", "", $controller);
			$html='';
			foreach ($menus as $key => $value) {
				$explode=explode("/",$key);
				$title=$explode[0];
				$icon=$explode[1];
				if(is_array($value)){
					$values=array_values($value);
					$check=explode("/",$values[0]);
					$html.='
					    <li class="dropdown '.(($check[0] == $controller) ? 'active' : '').'">
					      <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-'.$icon.'"></i> <span>'.$title.'</span> <i class="arrow fa fa-angle-down pull-right"></i></a>
					      <ul class="dropdown-menu">
					';
					foreach($value as $titulo => $link){
						$explode_submenu = explode("/",$titulo);
						$titulo2 = $explode_submenu[0];
						$icon2 = $explode_submenu[1];
						if($user == 'user' && $titulo2 == 'Sair')
							$html .= '<li role="separator" class="divider"></li>';
						$html.='<li><a href="'.$this->url.$link.'"><i class="fa fa-'.$icon2.'"></i> '.$titulo2.'</a></li>';
					}
					$html.='</ul></li>';
				}else{
					$html.='<li '.((strpos($value, $controller) !== false) ? 'class="active"' : '').'><a href="'.$this->url.$value.'"><i class="fa fa-'.$icon.'"></i> <span>'.$title.'</span></a></li>';
				}
			}
			return $html;
		}

		private function menu()
		{
			$links = $this->render($this->usuario->funcoe->tipo,$this->controller);
			$user_links = $this->render("user",$this->controller);
			$html = '
			<div id="fundo-externo">
		        <div id="fundo">
		        </div>
		    </div>
		    <nav class="navbar navbar-default bgmenu container-fluid">
		        <div class="col-xs-offset-3 col-xs-6 col-sm-offset-4 col-sm-4 col-md-offset-5 col-md-2">
		            <a href="" class="navbabrand">
		                <img id="gtlogo" class="img-responsive" src="'.$this->url.'public/img/gt.png" alt="">
		            </a>
		        </div>
		    </nav>
			<nav class="navbar navbar-default">
					  <div class="container-fluid">
					    <!-- Brand and toggle get grouped for better mobile display -->
					    <div class="navbar-header">
					      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
					        <span class="sr-only">Toggle navigation</span>
					        <span class="icon-bar"></span>
					        <span class="icon-bar"></span>
					        <span class="icon-bar"></span>
					      </button>
					    </div>

					    <!-- Collect the nav links, forms, and other content for toggling -->
					    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					      <ul class="nav navbar-nav">
					         '.$links.'
					      </ul>
					      
					      <ul class="nav navbar-nav navbar-right">
					      	'.$user_links.'
					        
					      </ul>
					    </div><!-- /.navbar-collapse -->
					  </div><!-- /.container-fluid -->
					</nav>';

			return $html;
		}
}
