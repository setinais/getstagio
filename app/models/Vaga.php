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

	public static function search($id_user,$filters,$role)
	{
		$vagas = self::find('all', 
			[ 
				'conditions' => [ 
					'cargo_has_instituicao_id = ?',
					CargoHasInstituicao::find_by_instituicao_id(Instituicao::find_by_usuario_id($id_user)->id)->id
				]
			]
		);

		return $vagas;
	}
}