<?php
/**
* Construtor do Tabuleiro de Damas
*
* @author Lucas Cárpio <lucas.carpio@hotmail.com>
*
* @version 1.0
* @package CheckersKing
*/

function createTable(){
	echo "<script>
			$('#tabuleiro').load('ajax/test.html', function() {
	  			focusTurno();
			});
		</script>";
	echo "<div class='tabuleiro' id='tabuleiro'>";
	if($_SESSION['jogo']['turno_de_jogada'] == 0){
		for($i=0;$i<8;$i++){
			for($j=0;$j<8;$j++){
				if(($i+$j)%2 == 0){
					echo "<div id='$i$j' class='casa' style='background-image: url(\"imgs/campoB.jpg\");' ondrop='drop(event)' ondragover='allowDrop(event)'>";
				}else{
					echo "<div id='$i$j' class='casa' style='background-image: url(\"imgs/campoP.jpg\"); ' ondrop='drop(event)' ondragover='allowDrop(event)' >";	
					if($i>4){
						echo "<img id='b$i$j' src='imgs/peca_branca.png' onmousedown='selecionarPeca(this)' onmouseup='deSelecionarPeca(this)' ondragstart='drag(event)' class='peca' data-selecionada='false' data-dama='FALSE' >";
					}else if($i<3){
						echo "<img id='p$i$j' src='imgs/peca_preta.png' class='peca' data-selecionada='false' data-dama='FALSE' >";
					}
				}
				echo '</div>';			
			}
		}
	}else if($_SESSION['jogo']['turno_de_jogada'] == 1){
				
		for($i=7;$i>=0;$i--){
			for($j=7;$j>=0;$j--){
				if(($i+$j)%2 == 0){
					echo "<div id='$i$j' class='casa' style='background-image: url(\"imgs/campoB.jpg\");' ondrop='drop(event)' ondragover='allowDrop(event)'>";
				}else{
					echo "<div id='$i$j' class='casa' style='background-image: url(\"imgs/campoP.jpg\"); ' ondrop='drop(event)' ondragover='allowDrop(event)'>";	
					if($i>4){
						echo "<img id='b$i$j' src='imgs/peca_branca.png' class='peca' data-selecionada='false' data-dama='FALSE' >";
					}else if($i<3){
						echo "<img id='p$i$j' src='imgs/peca_preta.png' onmousedown='selecionarPeca(this)' onmouseup='deSelecionarPeca(this)' ondragstart='drag(event)' class='peca' data-selecionada='false' data-dama='FALSE' >";
					}
				}
				echo '</div>';			
			}
		}
	}
	echo '</div>';
	if($_SESSION['jogo']['turno_de_jogada'] == 0){
		echo "<div id=\"oponent\" name=\"top\" class=\"userbar\" style=\"top: 0;\">".$_SESSION['jogo']['oponent'].
			"<span>Peças tomadas: <img id=\"pecas_tomadas_op\" src=\"imgs/peca_branca.png\"> <label id=\"count\">0</label></span>"
		."</div>";
	echo "<div id=\"gamer\" name=\"bottom\" class=\"userbar\" style=\"bottom: 0;\">".$_SESSION['usuarioNome'].
		 	"<span>Peças tomadas: <img id=\"pecas_tomadas_me\" src=\"imgs/peca_preta.png\"> <label id=\"count\">0</label></span>"
	     ."</div>";
	}else if($_SESSION['jogo']['turno_de_jogada'] == 1){
		echo "<div id=\"oponent\" name=\"top\" class=\"userbar\" style=\"top: 0;\">".$_SESSION['jogo']['oponent'].
			"<span>Peças tomadas: <img id=\"pecas_tomadas_op\" src=\"imgs/peca_preta.png\"> <label id=\"count\">0</label></span>"
		."</div>";
	echo "<div id=\"gamer\" name=\"bottom\" class=\"userbar\" style=\"bottom: 0;\">".$_SESSION['usuarioNome'].
		 	"<span>Peças tomadas: <img id=\"pecas_tomadas_me\" src=\"imgs/peca_branca.png\"> <label id=\"count\">0</label></span>"
	     ."</div>";
	}
}

?>