<?php 

class ConhecimentosSistema extends HXPHP\System\Model{
	static $belongs_to = [
		['estudante'],
		['sistema']
		];
}