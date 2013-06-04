<?php
// Inclui o arquivo com o sistema de seguran�a
include("seguranca.php");

if(isset($_POST['jogador']) && isset($_POST['login_senha'])) {
	
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		// Salva duas vari�veis com o que foi digitado no formul�rio
		// Detalhe: faz uma verifica��o com isset() pra saber se o campo foi preenchido
		$jogador = (isset($_POST['jogador'])) ? $_POST['jogador'] : '';
		$senha = (isset($_POST['login_senha'])) ? $_POST['login_senha'] : '';
		
		// Utiliza uma fun��o criada no seguranca.php pra validar os dados digitados
		if (validaJogador($jogador, $senha) == true) {
			// O usu�rio e a senha digitados foram validados, manda pra p�gina interna
			DB::init();
			DB::query("UPDATE jogador SET status = 1 where id = ".$_SESSION['usuarioID']);
			header("Location: game.php");
		} else {
			// O usu�rio e/ou a senha s�o inv�lidos, manda de volta pro form de login
			// Para alterar o endere�o da p�gina de login, verifique o arquivo seguranca.php
			expulsaVisitante("O usuário e/ou a senha são inválidos");
			return false;
		}
	}
}

switch($_POST['action']){
		
		case 'validaPosJogador'	: $response = validaPosJogador(); break;
		
		default:
			throw new Exception('Wrong action');
	}
	echo $response;

function validaPosJogador(){
	DB::init();
	DB::query("SELECT * FROM jogador where nome = '".$_POST['jogador']."'");
	if(DB::getMySQLiObject()->affected_rows==1){
  		return "Login em uso";
  	}	
}
?>