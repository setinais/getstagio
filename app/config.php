<?php
	//Constantes
	$configs = new HXPHP\System\Configs\Config;

	$configs->env->add('development');

	
		//Globais
		$configs->title = 'SIE';

		//Configurações de Ambiente - Desenvolvimento
		$configs->env->add('development');

		$configs->env->development->baseURI = '/sie/';

		$configs->env->development->database->setConnectionData([
			'driver' => 'mysql',
			'host' => 'localhost',
			'user' => 'root',
			'password' => '',
			'dbname' => 'sie',
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
			'Usuario/user' => [
				'Informações Basicas/user-secret' => '%baseURI%/home/index/informacoesBasicas',
				'Perfil/user-plus' => '%baseURI%/home/index/perfil/',
				'Sair/power-off' => '%baseURI%/login/sair/'
			]
		],'Estudante');

		$configs->env->development->menu->setMenus([
			'Home/home' => '%siteURL%/home/',
			'Usuario/user' => [
				'Informações Basicas/user-secret' => '%baseURI%/home/index/informacoesBasicas',
				'Perfil/user-plus' => '%baseURI%/home/index/perfil/',
				'Sair/power-off' => '%baseURI%/login/sair/'
			]
		],'Instituicao');

		$configs->env->development->menu->setMenus([
			'Home/home' => '%siteURL%/home/',
			'Cadastro Incompleto/folder-open' => '%siteURL%/home/cadastroadicional/'
			
		]);
		$configs->env->development->auth->setURLs('/sie/home',' /sie/login/');
		//$configs->env->development->auth->setURLs('/hxphp/admin/home/', '/hxphp/admin/login/', 'admin');
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
