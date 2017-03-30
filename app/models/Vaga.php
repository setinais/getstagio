<?php 
/**
* 
*/
class Vaga extends \HXPHP\System\Model
{
	static $has_many = [
		['log_vagas']
		];

	static $belongs_to = [
		['cargo_has_instituicao']
	];
}