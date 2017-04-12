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
	}