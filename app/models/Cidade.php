<?php 
class Cidade extends \HXPHP\System\Model
{
	static $belongs_to = [
		['estado']
	];
	public static function getCidades()
	{
		return self::all();
	}
}