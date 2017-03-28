<?php
/**
* 
*/
class Estado extends \HXPHP\System\Model
{
	static $has_many = array(
		array('usuarios')
		);

	public static function getEstados()
	{
		return self::all();
	}
}