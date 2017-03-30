<?php
/**
* 
*/
class CargoHasInstituicao extends \HXPHP\System\Model
{
	static $has_many = [
		['vagas']
	];

	static $belongs_to = [
		['cargo'],
		['instituicao']
	];
}