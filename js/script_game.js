$(document).ready(function(){
	var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
	
	$("#acesso").click(function(){
		
		$("#login").hide();
		$("#cadastro").hide();
		$("#login-content-form").show("fast");
	});

	$("#entrar").click(function(){
		
		$("#cadastro").hide("fast");
		$("#login").show("fast");
		$("[name=jogador]").focus();
		$("#login-content-form").hide("fast");
		
	});
	
	$("#cadastrar").click(function(){
		
		$("#login").hide("fast");
		$("#cadastro").show("fast");
		$("[name=cadastro_jogador]").focus();
		$("#login-content-form").hide("fast");
	});
	
	$("input[name=cadastro_senha]").focus(function(){
		if($("input[name=cadastro_jogador]").val()==""){
			alert("Campo Jogador está vazio.");
			$("input[name=cadastro_jogador]").focus();
			return false;
		}
		
		
		$.ajax({ url: 'valida.php',
         data: {action:'validaPosJogador',jogador: $("input[name=cadastro_jogador]").val()},
         type: 'post',
         success: function(output) {
         			if(output!=''){
                     alert(output);
                     $("input[name=cadastro_jogador]").val('').focus();
         				
         			}
         		}
		});
	});
	
	$("input[name=login_senha]").focus(function(){
		if($("input[name=jogador]").val()==""){
			alert("Campo Jogador está vazio.");
			$("input[name=jogador]").focus();
			return false;
		}
	});
	
	
	$('input[name=repetir_senha]').change(function(){
		if($('input[name=repetir_senha]').val()!=$('input[name=cadastro_senha]').val()){
			alert("Por favor verifique senha digitada.");
			$('input[name=repetir_senha]').val("");
			$('input[name=cadastro_senha]').val("");
			$('input[name=cadastro_senha]').focus();
		}
	});
	
	$("input[name=email]").blur(function(){
		var emailaddressVal = $("input[name=email]");
	    if(emailaddressVal.val() == '') {
	      alert("Preencha o campo email.");
	      emailaddressVal.val('').focus();
	    }else if(!emailReg.test(emailaddressVal.val())) {
	    	alert("Digite um e-mail válido.");
	    	emailaddressVal.val('').focus();
	    }
	});

    $("#form_cadastro").submit(function(){
        regEx_Ext = /^(19|20)\d\d([- /.])(0[1-9]|1[012])\2(0[1-9]|[12][0-9]|3[01])$/;
        regEx_Br = /^(0[1-9]|[12][0-9]|3[01])[- /.](0[1-9]|1[012])[- /.](19|20)\d\d$/;
        date = $("[name='datanasc']");
        if(regEx_Br.test(date.val())) {
            splited = date.val().split(/[- /.]/);
            date.val(splited[2]+"-"+splited[1]+"-"+splited[0]);
        }
        alert(date.val());
        if(!regEx_Ext.test(date.val())){
            return false
        }

    });
});

function returnError(p){
	console.log('erro: '+p);
	if(p != null && p != ""){
		alert(p);
	}
}
