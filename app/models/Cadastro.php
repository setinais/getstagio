<?php
	class Cadastro Extends \HXPHP\System\Model{
		static $belongs_to = [
			['estudante'],
			['vaga']
		];
		public static function lists($id){
			$vaga = null;
			$cads = Estudante::find_by_usuario_id($id);
			if(!is_null($cads)){
				foreach($cads->cadastros as $vall){
					$vaga[] = $vall->vaga;
				}
			}
			return $vaga;
		}
		public static function cadastrar($id_vaga,$id_studant)
		{
			$test = self::find('all',array('conditions'=>array('estudante_id = ? AND vaga_id = ?',$id_studant,$id_vaga)));
			if(empty($test)){
				if(Cadastro::create(array('estudante_id'=>$id_studant,'vaga_id'=>$id_vaga))){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}
		public static function descadastrar($id_vaga,$id_studant)
		{
			$test = self::find('all',array('conditions'=>array('estudante_id = ? AND vaga_id = ?',$id_studant,$id_vaga)));
			if(!empty($test)){
				if($test[0]->delete()){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}
		public static function verifica($id_vaga,$id_studant)
		{
			$test = self::find('all',array('conditions'=>array('estudante_id = ? AND vaga_id = ?',$id_studant,$id_vaga)));
			if(empty($test)){
				return true;
			}else{
				return false;
			}
		}
		/*
		* Pegar todos os estudantes que estão inscritos nesta vaga
		*
		*/
		public static function searchEstudantes($id_vaga,$baseURI)
		{
			$estagiarios = null;
			$ids_estudantes = self::all(['conditions' => ['vaga_id = ?',$id_vaga]]);
			if(is_null($ids_estudantes))
			{
				$estagiarios = "<tr>Nenhum candidato para esta vaga</tr>";
			}else{
				foreach ($ids_estudantes as $key => $value) 
				{
					
					$estagiarios .= "
						<tr>
							<th><a href='".$baseURI."Perfil/index/".$value->estudante->usuario->id."'>".$value->estudante->nome."</a></th>
							<td>".$value->estudante->usuario->email."</td>
							
						</tr>
					";
				}
				$layout = "
							<thead>
							<tr>
								<th>Estagiario</th>
								<th>E-mail</th>
								<th>Telefone</th>
							</tr>
						</thead>
						<tbody>
							".$estagiarios."
						</tbody>
					";
			}
			return $layout;
		}
		public static function deleteInscritos($id){
			foreach(Self::find_all_by_vaga_id($id) as $val){
				$val->delete();
			}
			if(Self::find_all_by_vaga_id($id) == null || empty(Self::find_all_by_vaga_id($id))){
				return true;
			}else{
				return false;
			}
		}
	}