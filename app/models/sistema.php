<?php 

class Sistema extends HXPHP\System\Model{
	static $has_many = [
		['conhecimentoSistema']
	];
	public static function existe($val){
		$test = self::find_by_nome($val);
		if(empty($test)){
			return false;
		}else{
			return true;
		}
	}
}