// JavaScript Document

var TURNO = 0;
var TOMADA_OBRIGATORIA = false;
var RELOAD = window.location.reload;
function allowDrop(ev){
	ev.preventDefault();
}
function drag(ev){
	ev.dataTransfer.setData("Text",ev.target.id);
}
function drop(ev){
	var data = ev.dataTransfer.getData("Text");
	if(!TOMADA_OBRIGATORIA){ //Se não há tomadas obrigatórias qualquer peça pode ser movida
		if(((data.charAt(0) == 'b'&& TURNO == 0) || (data.charAt(0) == 'p' && TURNO == 1)) && validaMovimentoPeca(data,ev)){
			ev.preventDefault();
			element = document.getElementById(data);
			element.id = element.id.charAt(0)+ev.target.id;
			deSelecionarPeca(element);
			ev.target.appendChild(element);
			setDama(element);
			TURNO = Math.abs(TURNO-1);
			mudarTurnoEmFoco()
			//FUNÇÃO QUE GRAVA MOVIMENTOS EM ARQUIVO
			
			$.ajax({
				url:'php-pages/ajax.php?action=movimentoTabuleiro',
				type:'POST',
				data:{tipo: 'M',idOrigem: data, idDestino: ev.target.id},
				dataType: 'json',
			});
			
		}
	}else if(document.getElementById(data).getAttribute('data-selecionada') == "true" && validaTomada(data,ev)){ //Caso haja tomadas obrigatórias apenas uma das peças selecionadas deve ser movida
		
		ev.preventDefault();
		element = document.getElementById(data);
		element.id = element.id.charAt(0)+ev.target.id;
		deSelecionarPeca(element);
		ev.target.appendChild(element);
		controleTomadasMaisUmMe();
		limpaObrigatoriedade();
		//TOMADA_OBRIGATORIA = false;
		certificaMovimentos(element);
		//FUN��O QUE GRAVA MOVIMENTOS EM ARQUIVO
		$.ajax({
				url:'php-pages/ajax.php?action=movimentoTabuleiro',
				type:'POST',
				data:{jogador: $('#jogador').attr('data-jogadorid'), tipo: 'T',idOrigem: data, idDestino: ev.target.id},
				dataType: 'json',
			});
		if(!TOMADA_OBRIGATORIA){
			setDama(element);
			TURNO = Math.abs(TURNO-1);
			mudarTurnoEmFoco();
		}
	}deSelecionarPeca(document.getElementById(data));
}

function validaMovimentoPeca(idPeca,ev){
	if($("#"+idPeca).attr("data-dama") == "FALSE"){
		pos1 = [parseInt(idPeca.charAt(1))+1] + [parseInt(idPeca.charAt(2))+1];
		pos2 = [parseInt(idPeca.charAt(1))+1] + [parseInt(idPeca.charAt(2))-1];
		pos3 = [parseInt(idPeca.charAt(1))-1] + [parseInt(idPeca.charAt(2))+1];
		pos4 = [parseInt(idPeca.charAt(1))-1] + [parseInt(idPeca.charAt(2))-1];
		if(idPeca.charAt(0) == 'b' && (ev.target.id == pos3 || ev.target.id == pos4)){
			return true;
		}else if(idPeca.charAt(0) == 'p' && (ev.target.id == pos1 || ev.target.id == pos2)){
			return true;
		}
		return false;
	}else{
		directions = possibilidadesMovimentoDama(idPeca);
		if(jQuery.inArray(ev.target.id, directions) != -1)return true;
		else return false;
	}
}
function possibilidadesMovimentoDama(idPeca){
	var cursor_x = parseInt(idPeca.charAt(1));
		var cursor_y = parseInt(idPeca.charAt(2));
		var i = 0;
		var directions = new Array();
		while(cursor_x > 0 && cursor_y > 0){
			cursor_x--;
			cursor_y--;
			if($("#"+cursor_x+""+cursor_y+" img:last-child").length != 0 && $("#"+cursor_x+""+cursor_y+" img:last-child").attr("id").charAt('0') == idPeca.charAt('0')){
				break;
			}else{
				directions[i] = cursor_x+""+cursor_y;
				i++;
			}
		}
		cursor_x = parseInt(idPeca.charAt(1));
		cursor_y = parseInt(idPeca.charAt(2));
		while(cursor_x > 0 && cursor_y < 7){
			cursor_x--;
			cursor_y++;
			if($("#"+cursor_x+""+cursor_y+" img:last-child").length != 0 && $("#"+cursor_x+""+cursor_y+" img:last-child").attr("id").charAt('0') == idPeca.charAt('0')){
				break;
			}else{
				directions[i] = cursor_x+""+cursor_y;
				i++;
			}
		}
		cursor_x = parseInt(idPeca.charAt(1));
		cursor_y = parseInt(idPeca.charAt(2));
		while(cursor_x < 7 && cursor_y < 7){
			cursor_x++;
			cursor_y++;
			if($("#"+cursor_x+""+cursor_y+" img:last-child").length != 0 && $("#"+cursor_x+""+cursor_y+" img:last-child").attr("id").charAt('0') == idPeca.charAt('0')){
				break;
			}else{
				directions[i] = cursor_x+""+cursor_y;
				i++;
			}
		}
		cursor_x = parseInt(idPeca.charAt(1));
		cursor_y = parseInt(idPeca.charAt(2));
		while(cursor_x < 7 && cursor_y > 0){
			cursor_x++;
			cursor_y--;
			if($("#"+cursor_x+""+cursor_y+" img:last-child").length != 0 && $("#"+cursor_x+""+cursor_y+" img:last-child").attr("id").charAt('0') == idPeca.charAt('0')){
				break;
			}else{
				directions[i] = cursor_x+""+cursor_y;
				i++;
			}
		}
		return directions;
}
function validaTomada(idPeca,ev){
	
	dif_i = 0;
	dif_j = 0;
	if($("#"+idPeca).attr("data-dama")=="FALSE"){
		pos1 = [parseInt(idPeca.charAt(1))+2] + [parseInt(idPeca.charAt(2))+2];
		pos2 = [parseInt(idPeca.charAt(1))+2] + [parseInt(idPeca.charAt(2))-2]; 
		pos3 = [parseInt(idPeca.charAt(1))-2] + [parseInt(idPeca.charAt(2))+2];
		pos4 = [parseInt(idPeca.charAt(1))-2] + [parseInt(idPeca.charAt(2))-2];
		
		if(ev.target.id == pos3 || ev.target.id == pos4 || ev.target.id == pos1 || ev.target.id == pos2){
			dif_i = (parseInt(ev.target.id.charAt(0))-parseInt(idPeca.charAt(1)))/2;
			dif_j = (parseInt(ev.target.id.charAt(1))-parseInt(idPeca.charAt(2)))/2;
			peca_meio = document.getElementById(""+(parseInt(idPeca.charAt(1))+dif_i)+(parseInt(idPeca.charAt(2))+dif_j));
			if(peca_meio.childNodes.length != 0 && isOposity(document.getElementById(idPeca),peca_meio)){
				peca_meio.removeChild(peca_meio.lastChild);
				return true;
			}
		}
	}else{
		var directions = possibilidadesMovimentoDama(idPeca);
		tomadora = document.getElementById(idPeca);
		
		pos1 = [parseInt(idPeca.charAt(1))+1] + [parseInt(idPeca.charAt(2))+1];
		pos2 = [parseInt(idPeca.charAt(1))+1] + [parseInt(idPeca.charAt(2))-1]; 
		pos3 = [parseInt(idPeca.charAt(1))-1] + [parseInt(idPeca.charAt(2))+1];
		pos4 = [parseInt(idPeca.charAt(1))-1] + [parseInt(idPeca.charAt(2))-1];
		for(i = 0; i < directions.length; i++){
			if(directions[i] == pos1 || directions[i] == pos2 || directions[i] == pos3|| directions[i] == pos4){
				directions.splice(i,1);
			}
		}
		if(jQuery.inArray(ev.target.id, directions) != -1){
			ind1 = parseInt(ev.target.id.charAt(0)) - parseInt(tomadora.id.charAt(1));
			ind2 = parseInt(ev.target.id.charAt(1)) - parseInt(tomadora.id.charAt(2));
			ind1 = ind1/Math.abs(ind1);
			ind2 = ind2/Math.abs(ind2);
			ind1 = parseInt(ev.target.id.charAt(0))-ind1;
			ind2 = parseInt(ev.target.id.charAt(1))-ind2;
			peca_meio = document.getElementById(""+ind1+ind2);
			if(peca_meio.childNodes.length != 0 && isOposity(tomadora,peca_meio)){
				peca_meio.removeChild(peca_meio.lastChild);
				return true;
			}
		}
	}
	return false;
}
//Movimentos de pe�a
function certificaMovimentos(peca){
	var pecas =[];
	var elementos = document.getElementsByClassName("peca");
	if(peca == null){
		for(i = 0; i < elementos.length; i++){
			if(elementos[i].id.charAt(0) == 'b' && TURNO == 0){
				pecas.push(elementos[i]);
			}else if(elementos[i].id.charAt(0) == 'p' && TURNO == 1){
				pecas.push(elementos[i]);
			}
		}
	}else pecas.push(peca);
	console.log(pecas.length);
	for(j=0;j<pecas.length;j++){
		console.log(pecas[j].getAttribute("data-dama"));
		certificaTomada(pecas[j].id);
		
	}
}
//Movimento de Tomada
function certificaTomada(idPeca){
	var pecaOuCampo = document.getElementById(idPeca);
	if(pecaOuCampo.getAttribute('data-dama') == "FALSE"){
		pos1 = document.getElementById([parseInt(idPeca.charAt(1))+1] + [parseInt(idPeca.charAt(2))+1]);
		pos2 = document.getElementById([parseInt(idPeca.charAt(1))+1] + [parseInt(idPeca.charAt(2))-1]);
		pos3 = document.getElementById([parseInt(idPeca.charAt(1))-1] + [parseInt(idPeca.charAt(2))+1]);
		pos4 = document.getElementById([parseInt(idPeca.charAt(1))-1] + [parseInt(idPeca.charAt(2))-1]);
		
		if(isOposity(document.getElementById(idPeca),pos1) && certificaCampoParaTomada(document.getElementById(idPeca),pos1) != null){
			TOMADA_OBRIGATORIA = true;
			selecionarPeca(document.getElementById(idPeca));
			document.getElementById(idPeca).setAttribute('data-selecionada',true);
		}
		if(isOposity(document.getElementById(idPeca),pos2) && certificaCampoParaTomada(document.getElementById(idPeca),pos2) != null){
			TOMADA_OBRIGATORIA = true;
			selecionarPeca(document.getElementById(idPeca));
			document.getElementById(idPeca).setAttribute('data-selecionada',true);
		}
		if(isOposity(document.getElementById(idPeca),pos3) && certificaCampoParaTomada(document.getElementById(idPeca),pos3) != null){
			TOMADA_OBRIGATORIA = true;
			selecionarPeca(document.getElementById(idPeca));
			document.getElementById(idPeca).setAttribute('data-selecionada',true);
		}
		if(isOposity(document.getElementById(idPeca),pos4) && certificaCampoParaTomada(document.getElementById(idPeca),pos4) != null){
			TOMADA_OBRIGATORIA = true;
			selecionarPeca(document.getElementById(idPeca));
			document.getElementById(idPeca).setAttribute('data-selecionada',true);
		}
	}else{
		var directions = possibilidadesMovimentoDama(idPeca);
		for(i = 0; i < directions.length; i++){
			pos = document.getElementById(directions[i]);
			if(isOposity(document.getElementById(idPeca),pos) && certificaCampoParaTomada(document.getElementById(idPeca),pos) != null){
				TOMADA_OBRIGATORIA = true;
				selecionarPeca(document.getElementById(idPeca));
				document.getElementById(idPeca).setAttribute('data-selecionada',true);
			}
		}
	}
}
function certificaCampoParaTomada(tomadora,campoAtomar){
	try{
		var atomar = campoAtomar.childNodes[0];
		if(tomadora.getAttribute("data-dama") == "FALSE"){
			ind1 = parseInt(atomar.id.charAt(1)) + (parseInt(atomar.id.charAt(1)) - parseInt(tomadora.id.charAt(1)));
			ind2 = parseInt(atomar.id.charAt(2)) + (parseInt(atomar.id.charAt(2)) - parseInt(tomadora.id.charAt(2)));
			if(document.getElementById(""+ind1+ind2).childNodes.length == 0) return document.getElementById(""+ind1+ind2);
		}else{
			
			ind1 = parseInt(atomar.id.charAt(1)) - parseInt(tomadora.id.charAt(1));
			ind2 = parseInt(atomar.id.charAt(2)) - parseInt(tomadora.id.charAt(2));
			ind1 = ind1/Math.abs(ind1);
			ind2 = ind2/Math.abs(ind2);
			ind1 = parseInt(atomar.id.charAt(1))+ind1;
			ind2 = parseInt(atomar.id.charAt(2))+ind2;
			if(document.getElementById(""+ind1+ind2).childNodes.length == 0) return document.getElementById(""+ind1+ind2);
		}
		
	}catch(err){}
	return null;
}

function selecionarPeca(peca){
	if(peca.id.charAt(0) == 'b'){
		if(peca.getAttribute("data-dama")=="TRUE") peca.src = "imgs/dama_branca_selecionada.png";
		else peca.src = "imgs/peca_branca_selecionada.png";
	}else{
		if(peca.getAttribute("data-dama")=="TRUE") peca.src = "imgs/dama_preta_selecionada.png";
		else peca.src = "imgs/peca_preta_selecionada.png";
	}
}
function deSelecionarPeca(peca){
	if(peca.id.charAt(0) == 'b' && peca.getAttribute('data-selecionada') == "false"){
		if(peca.getAttribute("data-dama")=="TRUE") peca.src = "imgs/dama_branca.png";
		else peca.src = "imgs/peca_branca.png";
	}else if(peca.id.charAt(0) == 'p' && peca.getAttribute('data-selecionada') == "false"){
		if(peca.getAttribute("data-dama")=="TRUE") peca.src = "imgs/dama_preta.png";
		else peca.src = "imgs/peca_preta.png";
	}
}
function isOposity(tomadora,campoAtomar){
	try{
		campoAtomar = campoAtomar.childNodes[0];
		if(tomadora.id.charAt(0) != campoAtomar.id.charAt(0)) return true;
	}catch(err){}
	return false;
}
function limpaObrigatoriedade(peca){
	TOMADA_OBRIGATORIA = false;
	var pecas =[];
	var elementos = document.getElementsByClassName("peca");
	if(peca == null){
		for(i = 0; i < elementos.length; i++){
			if(elementos[i].id.charAt(0) == 'b' && TURNO == 0){
				pecas.push(elementos[i]);
			}else if(elementos[i].id.charAt(0) == 'p' && TURNO == 1){
				pecas.push(elementos[i]);
			}
		}
	}else pecas.push(peca);
	for(i=0;i<pecas.length;i++){
		pecas[i].setAttribute('data-selecionada',false);
		deSelecionarPeca(pecas[i]);
	}
}
function passaTurno(peca){
	if(peca != null) setDama(peca);
	TURNO = Math.abs(TURNO-1);
	limpaObrigatoriedade();
	certificaMovimentos();
	mudarTurnoEmFoco();
	console.log("-----------------------------------------------------");
}
function mudarTurnoEmFoco(){
	if($("#gamer").hasClass("grayscale")){
		$("#gamer").removeClass("grayscale");
		$("#oponent").addClass("grayscale");
	}else{
		$("#oponent").removeClass("grayscale");
		$("#gamer").addClass("grayscale");
	}
}		
function setDama(peca){
	if(peca.id.charAt(0) == 'b' && peca.id.charAt(1) == '0' || peca.id.charAt(0) == 'p' && peca.id.charAt(1) == '7'){
		peca.setAttribute("data-dama","TRUE");
		deSelecionarPeca(peca);
	}
}