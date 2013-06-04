<div id="area-exclusiva" class="tabuleiro">
	<?php
		@session_start();
		DB::init();
		if(isset($_SESSION['userView'])){
			$result = DB::query("SELECT avatarlink,nome,data_nascimento,email FROM jogador WHERE id = ".$_SESSION['userView']);
			echo "<div id=\"home\">Home</div>";
		}else{
			$result = DB::query("SELECT avatarlink,nome,data_nascimento,email FROM jogador WHERE id = ".$_SESSION['usuarioID']);
		}
		$user = $result->fetch_object();
	?>
	<div id="avatar_area_exclusiva">
		<img src='fotos/<?php echo $user->avatarlink ?>'/>
	</div>
	<div id="infos_user_area_exclusiva">
		<h1 id="jogador_nome" ><?php echo $user->nome ?></h1>
		<a href="mailto:<?php echo $user->email  ?>">
			<?php echo $user->email ?>
		</a>
		<h2>Data de nascimento: <?php echo date("d/m/Y",strtotime($user->data_nascimento)) ?></h2>
	</div>
	<div id="score_area_exclusiva">
		<table>
			<tr><td>Partidas jogadas</td>	<td class="cell_meio">0</td><td class="cell_meio"></td><td>Desafios lançados	</td>	<td class="cell_meio">0</td>	</tr>
			<tr><td>Vitórias		</td>	<td class="cell_meio">0</td><td class="cell_meio"></td><td>Desafios recebidos 	</td>	<td class="cell_meio">0</td>	</tr>
			<tr><td>Derrotas		</td>	<td class="cell_meio">0</td><td class="cell_meio"></td><td>Desafios aceitos		</td>	<td class="cell_meio">0</td>	</tr>
			<tr><td>Empates			</td>	<td class="cell_meio">0</td><td class="cell_meio"></td><td>Desafios recusados 	</td>	<td class="cell_meio">0</td>	</tr>
		</table>
	</div>
	<div id="historico_jogos">
		<div style="width: 100%">
			<table id="tabela_jogos" cellpadding="0" cellspacing="0">
				<tr>
					<td>Oponente</td>
					<td>Resultado</td>
					<td>Data do jogo</td>
					<td>Tempo de jogo</td>
				</tr>			
			</table>
		</div>
		<div id="jogos_content" class="scroll-pane">
			<?php
			/*while (($jogo = $result_jogo->fetch_object()) != NULL) {
				echo "<table class=\"tabela_jogos_content\" cellpadding=\"0\" cellspacing=\"2\">
					<tr>
						<td>$jogo->jogador1</td>
						<td>venceu</td>
						<td>$jogo->data</td>
						<td>00:30 hrs</td>
					</tr>	
				</table>";
			}
			*/
			?>
		</div>
		
	</div>
</div>