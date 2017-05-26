<?php
/**
* 
*/
class CandidatarController extends \HXPHP\System\Controller
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
		$vagas = Vaga::find('all',array('conditions'=>array('status = ? OR status = ?',1,true)));
		$estrutura_vagas = null;
		$idu = Estudante::find_by_usuario_id($this->auth->getUserId())->id;
		foreach ($vagas as $key => $value) {
			$estrutura_vagas_head = null;
			$estrutura_vagas_section = null;
			$estrutura_vagas_footer = null;

			$estrutura_vagas_head = "<tr>
			<td>
				<h4 class='busca'>".$value->cargo_has_instituicao->cargo->nome."</h4>
			</td>
			<td>";
				$search_requisitos = Requisito::searchRequisitos($value->id);
				if(!is_null($search_requisitos)){
					foreach ($search_requisitos as $indei => $requisitos) {
						$estrutura_vagas_section .= "<span class='label label-default busca'>".$requisitos->requisito."</span>";
					}
				}
				$estrutura_vagas_footer = "</td>
				<td>R$ ".$value->remuneracao.",00</td>
				<td>".$value->duracao." ".$value->definicao_tempo."</td>
				<td>";
				if(Cadastro::verifica($value->id,$idu)){
					$estrutura_vagas_footer .= "<span class='label label-success candidatar' id='".$value->id."' style='cursor: pointer;'><span class='glyphicon glyphicon-log-in'></span>  <span class='troca'>Inscreva-se</span></span></td>";
				}else{
					$estrutura_vagas_footer .= "<span class='label label-info' id='".$value->id."' style='cursor: not-allowed;'><span class='glyphicon glyphicon-log-in'></span>  <span class='troca'> Inscrito</span></span> 
						<span class='label label-danger descandidatar' id='".$value->id."' style='cursor: pointer;'><span class='glyphicon glyphicon-log-in'></span>  <span class='troca'> Cancelar</span></span></td>";
				}
			$estrutura_vagas[] = $estrutura_vagas_head.$estrutura_vagas_section.$estrutura_vagas_footer;
			
		}
		$this->view->setAssets('js',
			[$this->configs->baseURI."public/js/jquery.js",
			$this->configs->baseURI.'public/js/jquery.js',
			$this->configs->baseURI.'public/js/cadastro/candidatar.js',
			$this->configs->baseURI.'public/js/toogle/tablesaw.js',
			$this->configs->baseURI.'public/js/toogle/tablesaw-init.js'
			])->setAssets('css',[
			$this->configs->baseURI."public/css/toogle/tablesaw.css"
			]);
		$this->view->setVars(['vagas' => $estrutura_vagas]);
	}
}