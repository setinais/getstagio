<?php 

class ConheciemntoEscritorio extends Model{
	static $belongs_to = [
		['estudante'],
		['conheciemnto']
		];
}