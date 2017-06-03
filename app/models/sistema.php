<?php 

<<<<<<< HEAD
class sistema extends \HXPHP\System\Model
{
=======
class Sistema extends HXPHP\System\Model{
>>>>>>> 3ce8657d060a9f532b3987e7a754820b5d04b314
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