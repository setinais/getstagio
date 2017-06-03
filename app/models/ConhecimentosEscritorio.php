<?php 

class ConhecimentoEscritorio extends HXPHP\System\Model{
	static $belongs_to = [
		['estudante'],
		['conheciemnto']
		];
}