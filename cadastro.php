<?php

	include("seguranca.php");
	$result;
	if(isset($_POST['cadastro_jogador']) && isset($_POST['cadastro_senha']) && isset($_POST['email']) && $_POST['cadastro_jogador']!='' && $_POST['cadastro_senha']!='' && $_POST['email']!='') {
		
		DB::init();
		
		$foto=$_FILES["foto"];
		
		//---------------------
		if (!empty($foto["name"])) {
			// Largura m�xima em pixels
			$largura = 300;
			// Altura m�xima em pixels
			$altura = 300;
			// Tamanho m�ximo do arquivo em bytes
			$tamanho = 50000;
	 
	    	// Verifica se o arquivo � uma imagem
	    	if(!preg_match("/^image\/(pjpeg|jpeg|png|gif|bmp)$/", $foto["type"])){
	     	   $error[1] = "O arquivo não é uma imagem.";
	   	 	} 
	 
			// Pega as dimens�es da imagem
			$dimensoes = getimagesize($foto["tmp_name"]);
	 
			// Verifica se a largura da imagem � maior que a largura permitida
			if($dimensoes[0] > $largura) {
				$error[2] = "A largura da imagem não deve ultrapassar ".$largura." pixels";
			}
	 
			// Verifica se a altura da imagem � maior que a altura permitida
			if($dimensoes[1] > $altura) {
				$error[3] = "Altura da imagem não deve ultrapassar ".$altura." pixels";
			}
	 
			// Verifica se o tamanho da imagem � maior que o tamanho permitido
			if($foto["size"] > $tamanho) {
	   		 	$error[4] = "A imagem deve ter no máximo ".$tamanho." bytes";
			}
			// Se n�o houver nenhum erro
			if (!isset($error)) {
				
				// Pega extens�o da imagem
				preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $foto["name"], $ext);
	 
	        	// Gera um nome �nico para a imagem
	        	$nome_imagem = md5(uniqid(time())) . "." . $ext[1];
	        	// Caminho de onde ficar� a imagem
	        	$caminho_imagem = "fotos/" . $nome_imagem;
				// Faz o upload da imagem para seu respectivo caminho
				move_uploaded_file($foto["tmp_name"], $caminho_imagem);
				
		//---------------------
				$con = mysql_connect(DB::$dbOptions['db_host'],DB::$dbOptions['db_user'],DB::$dbOptions['db_pass']);
				mysql_select_db(DB::$dbOptions['db_name'], $con);
				mysql_query("INSERT INTO `jogador`(`nome`, `senha`, `email`, `data_nascimento`, `status`,`avatarlink`) VALUES ('".$_POST['cadastro_jogador']."','".md5($_POST['cadastro_senha'])."','".$_POST['email']."','".$_POST['datanasc']."',1,'".$nome_imagem."')");
				$_SESSION['usuarioID'] = mysql_insert_id();
				mysql_close($con);
			}
		}else{
			$con = mysql_connect(DB::$dbOptions['db_host'],DB::$dbOptions['db_user'],DB::$dbOptions['db_pass']);
			mysql_select_db(DB::$dbOptions['db_name'], $con);
			mysql_query("INSERT INTO `jogador`(`nome`, `senha`, `email`, `data_nascimento`, `status`) VALUES ('".$_POST['cadastro_jogador']."','".md5($_POST['cadastro_senha'])."','".$_POST['email']."','".$_POST['datanasc']."',1)");
			$_SESSION['usuarioID'] = mysql_insert_id();
			mysql_close($con);
		}
			if ( isset($_SESSION['usuarioID'])  && $_SESSION['usuarioID'] != 0) {
				
				session_start();
				$_SESSION['usuarioNome'] = $_POST['cadastro_jogador'];
		
				// Verifica a op��o se sempre validar o login
				if ($_SG['validaSempre'] == true) {
					$_SESSION['usuarioLogin'] = $_POST['cadastro_jogador'];
					$_SESSION['usuarioSenha'] = $_POST['cadastro_senha'];
				}
				
				$jogador = array('email'      		=> $_POST['email'],
								 'data_nascimento'  => $_POST['datanasc'],
								 'avatarlink' 		=> $caminho_imagem 
						   );
				$_SESSION['dados_jogador']=$jogador;
				header("Location: game.php");
				
			}else{
				expulsaVisitante();
				return false;
			}
	}else{
		expulsaVisitante();
		return false;
	}
?>