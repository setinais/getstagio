<?php 
/**
* 
*/
class LogVaga extends \HXPHP\System\Model
{
	static $belongs_to = [
		['vaga'],
		['usuraio']
	];
}