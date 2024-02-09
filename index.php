<?php
	# dados do banco

use controllers\adminController;
use controllers\chamadoController;
use Controllers\HomeController;

	define('HOST','localhost');
	define('DATABASE','name');
	define('USER','user');
	define('PASSWORD','password');
	
	define('BASE', 'http://localhost:8090/teste/projeto_chamado/');

	// require 'vendor/autoload.php';
	require '../vendor/autoload.php';

	$autoload = function($class){
		include($class.'.php');
	};

	spl_autoload_register($autoload);

	// // testando conexao
	// $pdo = \MySql::conectar();

	$homeController = new HomeController();
	$chamadoController = new chamadoController();
	$adminController = new adminController();

	Router::get('/',function() use ($homeController){
		// echo '<h2>Home!</h2>';
		$homeController->index();
	});

	Router::get('/chamado',function() use ($chamadoController){
		if(isset($_GET['token'])){
			if($chamadoController->existeToken()){
				$info = $chamadoController->getPergunta($_GET['token']);
				$chamadoController->index($info);
			}else{
				die('O token está setado porém não existe!');
			}
		}else {
			die("Apenas com o token do chamando para você conseguir interagir!");
		}
    });

	Router::get('/admin', function() use ($adminController){
		$adminController->index();
	});

?>