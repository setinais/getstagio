<?php 

class ConhecimentosEscritorio extends HXPHP\System\Model{
	static $belongs_to = [
		['estudante'],
		['conheciemnto']
		];
}