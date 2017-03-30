<?php
	//Constantes
	$configs = new HXPHP\System\Configs\Config;

	$configs->env->add('development');

	
		//Globais
		$configs->title = 'GetStagio';

		//Configurações de Ambiente - Desenvolvimento
		$configs->env->add('development');

		$configs->env->development->baseURI = '/getstagio/';

		$configs->env->development->database->setConnectionData([
			'driver' => 'mysql',
			'host' => 'localhost',
			'user' => 'root',
			'password' => '',
			'dbname' => 'getstagio',
			'charset' => 'utf8'
		]);

		$configs->env->development->mail->setFrom([
			'from' => 'Remetente',
			'from_mail' => 'email@remetente.com.br'
		]);

		$configs->env->development->menu->setConfigs([
			'container' => 'nav',
			'container_class' => 'navbar navbar-default',
			'menu_class' => 'nav navbar-nav'
		]);

		$configs->env->development->menu->setMenus([
			'Home/home' => '%siteURL%/home',
			'Estagio/f' => [
				'Minhas Incscriçoes/list-alt' => '%baseURI%/estagio/list/',
				'Vagas de Estagio/newspaper-o' => '%baseURI%/estagio/candidatar/'
			],
			'Usuario/user' => [
				'Informações Basicas/user-secret' => '%baseURI%/home/informacoesBasicas/',
				'Perfil/user-plus' => '%baseURI%/home/perfil/',
				'Sair/power-off' => '%baseURI%/login/sair/'
			]
		],'Estudante');

		$configs->env->development->menu->setMenus([
			'Home/home' => '%siteURL%/home/',
			'Estagio/f' => [
				'Minhas Vagas/list-alt' => '%baseURI%/estagio/list/',
				'Nova Vaga/newspaper-o' => '%baseURI%/estagio/criar/'
			],
			'Usuario/user' => [
				'Informações Basicas/user-secret' => '%baseURI%/home/informacoesBasicas',
				'Perfil/user-plus' => '%baseURI%/home/perfil/',
				'Sair/power-off' => '%baseURI%/login/sair/'
			]
		],'Instituicao');

		$configs->env->development->menu->setMenus([
			'Home/home' => '%siteURL%/home/',
			'Cadastro Incompleto/folder-open' => '%baseURI%/home/cadastroadicional/',
			'Sair/power-off' => '%baseURI%/login/sair/'
			
		]);
		$configs->env->development->auth->setURLs('/getstagio/home/',' /getstagio/login/');
		//$configs->env->development->auth->setURLs('/sie/exemplo/home/', '/sie/exemplo/login/', 'admin');
		/*
		//Configurações de Ambiente - Produção
		$configs->env->add('production');

		$configs->env->production->baseURI = '/';

		$configs->env->production->database->setConnectionData([
			'driver' => 'mysql',
			'host' => 'localhost',
			'user' => 'usuariodobanco',
			'password' => 'senhadobanco',
			'dbname' => 'hxphp',
			'charset' => 'utf8'
		]);

		$configs->env->production->mail->setFrom([
			'from' => 'Remetente',
			'from_mail' => 'email@remetente.com.br'
		]);
	*/


	return $configs;
