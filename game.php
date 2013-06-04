<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/game_styles.css">
        <link rel="stylesheet" href="css/pages_styles.css">
        <link rel="shortcut icon" href="favicon.ico" >
        <link rel="stylesheet" href="css/jquery-ui-1.8.24.custom.css" />
        <link rel="stylesheet" href="css/jquery.contextMenu.css" />
        
        <script src="js/jquery-1.8.1.js"></script>
		<script src="js/functions.js"></script>
		<script src="js/game.js"></script>
		<script src="js/jquery-ui-1.8.24.custom.min.js"></script>
		<script src="js/jquery.contextMenu.js" type="text/javascript"></script>
		<title>Checkers King - As Damas do Rei</title>
    </head>
	<body id="body">
		<div id="menu_action_portal">
			<span id="sair">Sair</span>
		</div>
		<?php
					
			include("seguranca.php");
			protegePagina();
			if(isset($_SESSION['jogo'])){
				include "php-pages/estruturas/table-factory.php";
				createTable();
			}else{
				include'php-pages/estruturas/areadojogador.php';
			}
		?>
		<div id="tabuleiro_conteiner"></div>
		<div class="box">
			<div id="hist" class="box-header">Histórico de atualizações</div>
		</div>
		<div class="box" style="right: 10px">
			<div id="gammers" class="box-header">Jogadores Conectados </div>
			<div id="user_conteiner">
			</div>
		</div>
		<ul id="menuAcoesJogadores" class="contextMenu">
		    <li class="view"><a href="#view">Ver Dados</a></li>        
		    <li class="desafiar"><a href="#desafiar">Desafiar</a></li>                    
		    <li class="assistir"><a href="#assistir">Assistir Jogo</a></li>            
        </ul>
        
        <div id="dialog" title="Você foi desafiado!" style="display: none">
			<p>Você foi desafiado por <b id="nome_oponent">teste</b>. Aceitar o desafio?</p>
		</div>
	</body>
</html>