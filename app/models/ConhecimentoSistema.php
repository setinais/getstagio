<?php 

class conhecimentoSistema extends Model{
	static $belongs_to = [
		['estudante'],
		['sistema']
		];
}