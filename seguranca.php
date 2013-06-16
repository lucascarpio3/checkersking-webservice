<?php

//  Configura��es do Script
// ==============================

require"php-pages/classes/DB.class.php";

$_SG['conectaServidor'] = true;    // Abre uma conex�o com o servidor MySQL?
$_SG['abreSessao'] = true;         // Inicia a sess�o com um session_start()?

$_SG['caseSensitive'] = false;     // Usar case-sensitive? Onde 'thiago' � diferente de 'THIAGO'

$_SG['validaSempre'] = true;       // Deseja validar o usu�rio e a senha a cada carregamento de p�gina?
// Evita que, ao mudar os dados do usu�rio no banco de dado o mesmo contiue logado.

$_SG['servidor'] = 	DB::$dbOptions['db_host'];    // Servidor MySQL
$_SG['usuario']  = 	DB::$dbOptions['db_user'];    // Usu�rio MySQL
$_SG['senha']    =  DB::$dbOptions['db_pass'];    // Senha MySQL
$_SG['banco']    =  DB::$dbOptions['db_name'];    // Banco de dados MySQL

$_SG['paginaLogin'] = 'index.php'; // P�gina de login

$_SG['tabela'] = 'jogador';        // Nome da tabela onde os usu�rios s�o salvos
// ==============================

// ======================================
//   ~ N�o edite a partir deste ponto ~
// ======================================

// Verifica se precisa fazer a conex�o com o MySQL
if ($_SG['conectaServidor'] == true) {
$_SG['link'] = mysql_connect($_SG['servidor'], $_SG['usuario'], $_SG['senha']) or die("MySQL: Não foi possível conectar ao servidor [".$_SG['servidor']."].");
mysql_select_db($_SG['banco'], $_SG['link']) or die("MySQL: Não foi possível conectar ao banco de dados [".$_SG['banco']."].");
}

// Verifica se precisa iniciar a sess�o
if ($_SG['abreSessao'] == true) {
	session_start();
}

/**
* Fun��o que valida um usu�rio e senha
*
* @param string $usuario - O usu�rio a ser validado
* @param string $senha - A senha a ser validada
*
* @return bool - Se o usu�rio foi validado ou n�o (true/false)
*/
function validaJogador($usuario, $senha) {
	global $_SG;
	
	$cS = ($_SG['caseSensitive']) ? 'BINARY' : '';
	
	// Usa a fun��o addslashes para escapar as aspas
	$nusuario = addslashes($usuario);
	$nsenha = md5($senha);
	
	// Monta uma consulta SQL (query) para procurar um usu�rio
	$sql = "SELECT * FROM `".$_SG['tabela']."` WHERE ".$cS." `nome` = '".$nusuario."' AND ".$cS." `senha` = '".$nsenha."' LIMIT 1";
	$query = mysql_query($sql);
	$resultado = mysql_fetch_assoc($query);
	
	// Verifica se encontrou algum registro
	if (empty($resultado)) {
	// Nenhum registro foi encontrado => o usu�rio � inv�lido
		return false;

	} else {
		// O registro foi encontrado => o usu�rio � valido
		
		// Definimos dois valores na sess�o com os dados do usu�rio
		$_SESSION['usuarioID'] = $resultado['id']; // Pega o valor da coluna 'id do registro encontrado no MySQL
		$_SESSION['usuarioNome'] = $resultado['nome']; // Pega o valor da coluna 'nome' do registro encontrado no MySQL

		$jogador = array('email'			=> $resultado['email'],
			 			 'data_nascimento'  => $resultado['data_nascimento'],
						 'avatarlink' 		=> 'fotos/'.$resultado['avatarlink']);
			
		$_SESSION['dados_jogador']=$jogador;
		
		// Verifica a op��o se sempre validar o login
		if ($_SG['validaSempre'] == true) {
		// Definimos dois valores na sess�o com os dados do login
		$_SESSION['usuarioLogin'] = $usuario;
		$_SESSION['usuarioSenha'] = $senha;
		}			
		return true;
	}
}

/**
* Fun��o que protege uma p�gina
*/
function protegePagina() {
	global $_SG;
	
	if (!isset($_SESSION['usuarioID']) OR !isset($_SESSION['usuarioNome'])) {
	// N�o h� usu�rio logado, manda pra p�gina de login
	expulsaVisitante("Usuário não logado");
	} else if (!isset($_SESSION['usuarioID']) OR !isset($_SESSION['usuarioNome'])) {
		// H� usu�rio logado, verifica se precisa validar o login novamente
		if ($_SG['validaSempre'] == true) {
			// Verifica se os dados salvos na sess�o batem com os dados do banco de dados
			if (!validaUsuario($_SESSION['usuarioLogin'], $_SESSION['usuarioSenha'])) {
				// Os dados n�o batem, manda pra tela de login
				expulsaVisitante("Dados de login incorretos");
			}
		}
	}
}

/**
* Fun��o para expulsar um visitante
*/
function expulsaVisitante($error) {
	global $_SG;
	
	// Remove as vari�veis da sess�o (caso elas existam)
	unset($_SESSION['usuarioID'], $_SESSION['usuarioNome'], $_SESSION['usuarioLogin'], $_SESSION['usuarioSenha']);
	
	if($error != null){
		$_SESSION['error'] = $error;
	}
	
	// Manda pra tela de login
	header("Location: ".$_SG['paginaLogin']);
	}
?>