$(document).ready(function(){
	
	$.tzGET = function(action,data,callback){
		$.get('php-pages/ajax.php?action='+action,data,callback,'json');
	}
	
	 function render(template,user){
		
		var arr = [];
		switch(template){			
			case 'user':
				if(user.status == 1){
					arr = ['<div id="'+user.id+'" class="online"><img src="fotos/',user.avatarlink,'" />',user.nome,'</div>'];
				}else if(user.status == 2){
					arr = ['<div id="'+user.id+'" class="emjogo"><img src="fotos/',user.avatarlink,'" />',user.nome,'</div>'];
				}
			break;
		}
		
		return arr.join('');
	}
	
	(function getUsersTimeoutFunction(){getUsers(getUsersTimeoutFunction);})();
	
	function getUsers(callback){
		$.tzGET('getUsers',function(r){
			var users = [];
			for(var i=0; i< r.users.length;i++){
					users.push(render('user',r.users[i]));
			}
			$('#user_conteiner').html(users.join(''));
			setTimeout(callback,10000);
		});
	}
	
	$("#sair").click(function(){
		jQuery.ajax({url:'php-pages/ajax.php?action=logout', async:false});
		window.location.reload(true);
	});
	
	$("#home").click(function(){
		jQuery.ajax({url:'php-pages/ajax.php?action=goHome', async:false});
		window.location.reload(true);
	});
	
	$('#user_conteiner').hover(function(){
		$(this).children('div').contextMenu({ menu: 'menuAcoesJogadores', leftButton: true },
		function contextMenuWork(action, el, pos) {

	        switch (action) {
	            case "desafiar":
	                {
	                	if(el.attr('class').indexOf("emjogo") == -1){
		                    $.ajax({url:'php-pages/ajax.php?action=desafiar',
		                    		type:'POST',
		                    		data: {jogador2: el.attr('id')},
		                    		dataType: 'json'
		                    });
	                	}else{
	                		alert("No momento este jogador já está em um jogo.");
	                	}
	                    break;
	            	}
	            case "view":
	                {
	                	$.ajax({url:'php-pages/ajax.php?action=viewGammer',
	                    		type:'POST',
	                    		data: {jogador: el.attr('id')},
	                    		dataType: 'json'
	                    });
	                    setInterval(function(){window.location.reload(true)},3000);
	   	                break;
	   	            }
		    }
    	});
	});
	
	(function getJogoTimeoutFunction(){
		getJogo(getJogoTimeoutFunction);
	})();
	
	function getJogo(callback){

		$.tzGET('getJogo',function(result){
			if(result != null){
				$("#nome_oponent").text(result.oponente);
				$('#dialog').dialog({
					autoOpen: false,
					width: 600,
					buttons: {
						"Sim": function() {
							
							$(this).dialog("close");
							$('#area-exclusiva').hide("fast");
							jQuery.ajax({url:'php-pages/ajax.php?action=aceitarJogo',data : {jogoID: result.id},type: "POST", async:false});
                            $(document.body).load('game.php', function(){
                                $("#oponent").addClass("grayscale");
                            });
						},
						"Não": function() {
							$(this).dialog("close");
							jQuery.ajax({url:'php-pages/ajax.php?action=recusarJogo',data : {jogoID: result.id},type: "POST", async:false});
						}
					}
				});
				
			$('#dialog').dialog('open');
			
			}
			setTimeout(callback,5000);
		});
	}
	
	(function getJogo1TimeoutFunction(){
		getJogo1(getJogo1TimeoutFunction);
	})();
	
	function getJogo1(callback){

		$.tzGET('getJogo1',function(result){
			if(result != null){
                $(document.body).load('game.php', function(){
                    $("#gamer").addClass("grayscale");
                });
			}
			setTimeout(callback,5000);
		});
	}
	
	(function getMovimentoTimeoutFunction(){
		getMovimento(getMovimentoTimeoutFunction);
		
	})();
	
	function getMovimento(callback){

		$.tzGET('getMovimento',function(r){
			if(r != null){
				if(r.tipo=='M'){
					pecaIdOrigem=$("#"+r.campoOrigem+" img:last-child").attr("id");
					$("#"+r.campoOrigem+" img:last-child").attr("id",pecaIdOrigem.charAt(0)+r.campoDestino);
					$("#"+r.campoDestino).html($("#"+r.campoOrigem).html());
					$("#"+r.campoOrigem).html("");
					passaTurno(document.getElementById(pecaIdOrigem.charAt(0)+r.campoDestino));
				}else if(r.tipo=='T'){
					var campoOrigem  = String(r.campoOrigem);
					var campoDestion = String(r.campoDestino);
					ind1 = parseInt(campoDestion.charAt(0)) - parseInt(campoOrigem.charAt(0));
					ind2 = parseInt(campoDestion.charAt(1)) - parseInt(campoOrigem.charAt(1));
					ind1 = ind1/Math.abs(ind1);
					ind2 = ind2/Math.abs(ind2);
					ind1 = parseInt(campoDestion.charAt(0))-ind1;
					ind2 = parseInt(campoDestion.charAt(1))-ind2;
					$("#"+ind1+ind2).html("");
					pecaIdOrigem = $("#"+r.campoOrigem+" img:last-child").attr("id");
					$("#"+r.campoOrigem+" img:last-child").attr("id",pecaIdOrigem.charAt(0)+r.campoDestino);
					$("#"+r.campoDestino).html($("#"+r.campoOrigem).html());
					$("#"+r.campoOrigem).html("");
					controleTomadasMaisUmOponent();
					limpaObrigatoriedade();
					certificaTomada(pecaIdOrigem.charAt(0)+r.campoDestino);
					if(TOMADA_OBRIGATORIA == false){
						passaTurno(document.getElementById(pecaIdOrigem.charAt(0)+r.campoDestino));
					}else{
						limpaObrigatoriedade();
					}
				}
			}
			setTimeout(callback,1000);
		});
	}
	if(document.getElementById("tabuleiro") != null){
		window.onbeforeunload = function () {
			return "A operação cancelará o jogo em andamento.";
	   }
	   
	   (function checaFimDeJogo(){
			fimDeJogo(checaFimDeJogo());
	   })();
	
		function fimDeJogo(callback){
	
			jQuery.ajax({
				url:'php-pages/ajax.php?action=fimDeJogo',
				data: {type: 1},
				type: "POST",
				async: false,
				sucess: function(r){
					window.location.reload(true);
				}
			});
			setTimeout(callback,3000);
		}
  	}
	if($("#pecas_tomadas_me").attr("src") == "imgs/peca_branca.png"){
		$("#gamer").addClass("grayscale");
	}
	if($("#pecas_tomadas_op").attr("src") == "imgs/peca_branca.png"){
		$("#oponent").addClass("grayscale");
	}
});

function controleTomadasMaisUmMe(){
	$("#gamer").find("label").html(parseInt($("#gamer").find("label").html())+1);
}
function controleTomadasMaisUmOponent(){
	$("#oponent").find("label").html(parseInt($("#gamer").find("label").html())+1);
}
function validaFinalizacao(){
	if(existePecasDoTurno() == false){
		jQuery.ajax({
			url:'php-pages/ajax.php?action=fimDeJogo',
			data: {type: "0"},
			type: "POST",
			async: false
		});
		window.location.reload(true);
	}
}
function existePecasDoTurno(){
	pecas = document.getElementsByClassName("peca");
	if(TURNO == 0){
		for(i=0;i<pecas.length;i++){
			if(pecas[i].id.charAt("0")=="b")return true;
		}
	}else{
		if(TURNO == 1){
			for(i=0;i<pecas.length;i++){
				if(pecas[i].id.charAt("0")=="p")return true;
			}
		}
	}
	return false;
}
