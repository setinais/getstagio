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
	}