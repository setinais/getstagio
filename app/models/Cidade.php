<?php 
class Cidade extends \HXPHP\System\Model
{
	static $belongs_to = [
		['estado']
	];
	static $has_many = [
		['usuarios']
	];
	public static function getCidades()
	{
		return self::all();
	}
}