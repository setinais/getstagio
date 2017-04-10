<?php
	class Requisito extends \HXPHP\System\Model{
		static $belongs_to = [
			['vaga']
		];		

		public static function cadastrar($value,$cad_id)
		{
			self::create(array('requisito'=>$value,"vaga_id"=>$cad_id));
		}

		public static function excluir($id)
		{
			self::table()->delete(['vaga_id' => $id]);
		}

		public static function search($id)
		{
			$vagas = Vaga::search($id);
			$requisitos = [];
			if(!is_null($vagas))
				foreach ($vagas as $key => $value) {
					$all = self::find('all',['conditions' => ['vaga_id = ?',$value->id]]);
					$requisitos[$value->id] = $all;
				}
			return $requisitos;
		}

		public static function searchRequisitos($id)
		{
			$all = self::find('all',['conditions' => ['vaga_id = ?',$id]]);
			return $all;
		}
	}