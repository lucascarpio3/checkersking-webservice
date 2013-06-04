<!DOCTYPE HTML >
<html>
	<head>
		<link rel="stylesheet" href="css/pages_styles.css" />
		<script src="js/jquery-1.8.1.js"></script>
		<script src="js/script_game.js"></script>
		<link rel="shortcut icon" href="favicon.ico" >
		<title>Checkers King - As Damas do Rei</title>
	</head>
	<?php
		require "php-pages/classes/DB.class.php";
		DB::init();
		session_start();
	?>
	<body onload="returnError(<?php echo (isset($_SESSION['error'])) ? '\''.$_SESSION['error'].'\'' : "" ?>)">
		<?php unset($_SESSION['error']); ?>
		<div id="content_index">
			<span title="Entrar" id="entrar">Entrar</span>
			<span title="Cadastrar" id="cadastrar">Cadastre-se</span>
		</div>
		
		<div class="popup_login" id="login">
			<div class='popup_login_title'>Entrar</div>
			<form id="form_login" accept-charset='UTF-8' method='post' action='valida.php'>
				<table width="100%">
					<tr>
						<td align="right">
							<label>Jogador: </label>
						</td>
						<td>
							<input type='text' name='jogador' maxlength='16' />
						</td>
					</tr>
					<tr>
						<td align="right">
							<label>Senha: </label>
						</td>	
						<td>
							<input type='password' name='login_senha' />
						</td>
					</tr>
					<tr>
						<td colspan="2" align="center">
							<input type='submit' value='Entrar' />
						</td>
					</tr>
				</table>
			</form>
		</div>
		
		<div class="popup_login" id="cadastro">
			<div class='popup_login_title'>Cadastrar</div>
			<form id="form_cadastro" action='cadastro.php' accept-charset='UTF-8' method='post'  enctype="multipart/form-data">
				<table width="100%">
					<tr>
						<td align="right">
							<label>Jogador: </label>
						</td>
						<td>
							<input type='text' name='cadastro_jogador' maxlength='16' />
						</td>
					</tr>
					<tr>
						<td align="right">
							<label>Senha: </label>
						</td>	
						<td>
							<input type='password' name='cadastro_senha' />
						</td>
					</tr>
					<tr>
						<td align="right">
							<label>Repetir senha: </label>
						</td>
						<td>
							<input type="password" name="repetir_senha" />
					</tr>
					<tr>
						<td align="right">
							<label>e-mail:</label>
						</td>
						<td>
							<input type="email" name="email" />
						</td>
					</tr>
					<tr>
						<td align="right">
							<label>data de nasc.</label>
						</td>
						<td>
							<input type="date" name="datanasc" />
						</td>
					</tr>
					<tr>
						<td colspan="2" align="center">
							<input type="file" name="foto" />
						</td>
					</tr>
					<tr>
						<td colspan="2" align="center">
							<input type='submit' value='Cadastrar' />
						</td>
					</tr>
				</table>
			</form>
		</div>
		
	</body>
</html>