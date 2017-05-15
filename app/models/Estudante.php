<?php

/**
* 
*/
class Estudante extends \HXPHP\System\Model
{
	static $belongs_to = [
		['usuario']
	];
	static $has_many = array(
		array('cadastros')
		);
	static $validates_presence_of = [
			[
				'matricula',
				'message' => '<strong>Matrícula</strong> é um campo obrigatório.'
			],
			[
				'curso',
				'message' => '<strong>Curso</strong> é um campo obrigatório.'
			],
			[
				'serie_modulo',
				'message' => '<strong>Série / Modúlo / Período</strong> é um campo obrigatório.'
			],
			[
				'data_nasc',
				'message' => '<strong>Data de nascimento</strong> é um campo obrigatorio.'
			]
			
	];
	static $validates_uniqueness_of  = [
			[
				['matricula'],
				'message' => 'Já existe um estudante cadastrado com esta <strong>matrícula</strong>.'
			],
			[
				['cpf'],
				'message'=>'Já existe um estudante cadastrado com este <strong>CPF</strong>.'
			]
	];

	public static function cadastrar($post,$id_user)
	{
		$callback = new \stdClass;
		$callback->status = false;
		$callback->user = null;
		$callback->errors = [];

		$post['usuario_id'] = $id_user;
		$cadastrar = self::create($post);
		if($cadastrar->is_valid())
		{
			$callback->status = true;
			$callback->user = $cadastrar;

			$usuario = Usuario::find($id_user);
			$usuario->funcoe_id = 1;
			$usuario->save();

		}
		else
		{
			$errors = $cadastrar->errors->get_raw_errors();
			foreach ($errors as $campo => $messagem) {
				array_push($callback->errors, $messagem[0]);
			}
		}

		return $callback;
	}
	
	public static function editar($atributos,$id)
	{
		$callback = new \stdClass;
		$callback->status = false;
		$callback->est = null;
		$callback->errors = [];

		if(!empty($atributos)){
			$editar = self::find_by_usuario_id($id);
			$editar->update_attributes($atributos);
			$editar->save();
			if($editar->is_valid())
			{
				$callback->status = true;
				$callback->est = $editar;
			}
			else
			{
				$errors = $editar->errors->get_raw_errors();
				foreach ($errors as $campo => $messagem) 
				{
					array_push($callback->errors, $messagem[0]);
				}
			}
		}
		else
		{
			$callback->errors = "Todos os campos estão vazios!";
		}
		return $callback;
	} 
	public static function mostrarPerfil($id_usuario)
	{
		$estudante = self::find_by_usuario_id($id_usuario);

		$layout = " 
				<div class='tab-pane active' id='tab_default_1'>
						Estagiario
										<p>
											My daughter  is good looking, with pleasant personality, smart, well educated, from well cultural and  a religious family background. having respect in heart for others.  
											would like to thanks you for visiting through my daughter;s profile. 
											She has done PG in Human Resources after her graduation. 
											At present working IN INSURANCE sector as Manager Training .
										</p>
										<h4>About her Family</h4>
										<p>
											About her family she belongs to a religious and a well cultural family background. 
											Father - Retired from a Co-operate Bank as a Manager. 
											Mother - she is a home maker. 
											1 younger brother - works for Life Insurance n manages cluster. 
										</p>
										<h4>Education </h4>
										<p>I have done PG in Human Resourses</p>
										<h4>Occupation</h4>
										<p>At present Working in Insurance sector</p>

									</div>
									<div class='tab-pane' id='tab_default_2'>
										<p>
											Education& Career
										</p>
										<div class='row'>
											<div class='col-sm-6'>
												<div class='form-group'>
													<label for='email'>Highest Education:</label>
													<p> MBA/PGDM</p>
												</div>
												<div class='form-group'>
													<label for='email'>Place of Birth:</label>
													<p> pune, maharashtra</p>
												</div>
												<div class='form-group'>
													<label for='email'>Place of Birth:</label>
													<p> pune, maharashtra</p>
												</div>
												<div class='form-group'>
													<label for='email'>Place of Birth:</label>
													<p> pune, maharashtra</p>
												</div>
											</div>
											<div class='col-sm-6'>
												<div class='form-group'>
													<label for='email'>Place of Birth:</label>
													<p> pune, maharashtra</p>
												</div>
												<div class='form-group'>
													<label for='email'>Place of Birth:</label>
													<p> pune, maharashtra</p>
												</div>
												<div class='form-group'>
													<label for='email'>Place of Birth:</label>
													<p> pune, maharashtra</p>
												</div>
												<div class='form-group'>
													<label for='email'>Place of Birth:</label>
													<p> pune, maharashtra</p>
												</div>

											</div>
										</div>
									</div>
		";
		return $layout;
	}
}