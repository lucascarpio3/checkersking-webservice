<?php
require "classes/DB.class.php";
require "classes/Game.class.php";
require "classes/Jogo.class.php";

$base_url = 'http://localhost/checkersking/';

if(get_magic_quotes_gpc()){
	
	// If magic quotes is enabled, strip the extra slashes
	array_walk_recursive($_GET,create_function('&$v,$k','$v = stripslashes($v);'));
	array_walk_recursive($_POST,create_function('&$v,$k','$v = stripslashes($v);'));
}

try{

	$response = array();
	switch($_GET['action']){
		case 'aceitarJogo':
			$response = Jogo::aceitarDesafio($_POST['jogoID']);
			break;
		case 'recusarJogo':
			$response = Jogo::recusarDesafio($_POST['jogoID']);
			break;
		case 'viewGammer':
			session_start();
			$_SESSION['userView'] = $_POST['jogador'];
			break;
		case 'goHome':
			session_start();
			unset($_SESSION['userView']);
			break;
		case 'passaTurno':
			@session_start();
			$response = $_SESSION['jogo']['turno_de_jogada'];
			break;
		case 'getUsers':
			$response = Game::getUsers();
			break;
			
		case 'logout':
			$response = Game::logOut();
			break;
			
		case 'desafiar':
			$response = Jogo::init($_POST['jogador2']);
			break;
		case 'getJogo':
			if(!isset($_SESSION['jogo']['id'])){
				$response = Jogo::temJogo(0);
			}else{
				$response = NULL;
			}
			break;
		case 'getJogo1':
			@session_start();
			if(!isset($_SESSION['jogo'])){
				$response = Jogo::temJogo(1);
			}else{
				$response = null;
			}
			break;
		case 'movimentoTabuleiro':
			@session_start();
			$response = Jogo::setmovimento($_SESSION['usuarioID'],$_POST['tipo'],$_POST['idOrigem'],$_POST['idDestino']);
			break;
		case 'getMovimento':
			$response = Jogo::getMovimento();
			break;
		case 'fimDeJogo':
			$response = Jogo::finalizaJogo($_POST['type']);
			break;
		default:
			throw new Exception('Wrong action');
	}
	
	echo json_encode($response);
}
catch(Exception $e){
	die(json_encode(array('error' => $e->getMessage())));
}

?>