
function troca(tipo)
{
	
	switch(tipo)
	{
		case '1': 
		$("#instituicao").hide();
		$("#estudante").show('fast');
		break;
		case '2': 
		$("#estudante").hide();
		$("#instituicao").show('fast');
		break;
		default: 
		alert('invalido');
	}
}
jQuery(document).ready(function($) {
	$("#id_cnpj").mask('99.999.999/9999-99');
	$("#id_cpf").mask('999.999.999-99');
	$("#formInstituicao").submit(function(event) {
		if(valida_cnpj($("#id_cnpj").val())){
			return true;
		}else{
			$("#erroCNPJ").show("fast").focus();
			return false;
		}
	});
	$("#defn").click(function(event) {
		$("#deficiencia").attr('disabled', 'disabled');
	});

	$("#defs").click(function(event) {
		$("#deficiencia").removeAttr('disabled');
	});
	$(".id_telefone").blur(function(){
 		if($(this).val().length <= 10 || $(this).val().length == 10){
 			$(this).mask('(99)9999-9999');
 		}else{
 			$(this).mask('(99)99999-9999');
 		}
 	});

 	$(".cep").mask('99999-999');

	$("#id_cnpj").click(function(event) {
		$("#erroCNPJ").hide();
	});

	$("#sitEM").change(function(event) {
		switch($(this).val()){
			case "Incompleto":
				$("#fimEM").attr('disabled', 'disabled');
				$("#serieEM").removeAttr('disabled');
				break;
			case "Cursando":
				$("#fimEM").attr('disabled', 'disabled');
				$("#serieEM").removeAttr('disabled');
				break;
			case "Completo":
				$("#serieEM").attr('disabled', 'disabled');
				$("#fimEM").removeAttr('disabled');
				break;
		}
	});

	$("#situacaoTec").change(function(event) {
		switch($(this).val()){
			case "Incompleto":
				$("#fimCT").attr('disabled', 'disabled');
				$("#serieCT").removeAttr('disabled');
				break;
			case "Cursando":
				$("#fimCT").attr('disabled', 'disabled');
				$("#serieCT").removeAttr('disabled');
				break;
			case "Completo":
				$("#serieCT").attr('disabled', 'disabled');
				$("#fimCT").removeAttr('disabled');
				break;
		}
	});

	$("#sitSP").change(function(event) {
		switch($(this).val()){
			case "Incompleto":
				$("#fimSP").attr('disabled', 'disabled');
				$("#serieSP").removeAttr('disabled');
				break;
			case "Cursando":
				$("#fimSP").attr('disabled', 'disabled');
				$("#serieSP").removeAttr('disabled');
				break;
			case "Completo":
				$("#serieSP").attr('disabled', 'disabled');
				$("#fimSP").removeAttr('disabled');
				break;
		}
	});

	$("#iniEM").change(function(event) {
		var ano = $(this).val();
		$("#fimEM").find('option').each(function(index, el) {
			if($(this).val() < ano ){
				$(this).attr('disabled','disabled');
			}else{
				if($(this).val() != "selecione"){
					$(this).attr('disabled');
				}
				$(this).css('font-weight', '700');
			}
		});
	});iniCT

	$("#iniCT").change(function(event) {
		var ano = $(this).val();
		$("#fimCT").find('option').each(function(index, el) {
			if($(this).val() < ano ){
				$(this).attr('disabled','disabled');
			}else{
				if($(this).val() != "selecione"){
					$(this).attr('disabled');
				}
				$(this).css('font-weight', '700');
			}
		});
	});

	$("#iniSP").change(function(event) {
		var ano = $(this).val();
		$("#fimSP").find('option').each(function(index, el) {
			if($(this).val() < ano ){
				$(this).attr('disabled','disabled');
			}else{
				if($(this).val() != "selecione"){
					$(this).attr('disabled');
				}
				$(this).css('font-weight', '700');
			}
		});
	});
	var i = 0;
	var b = 0;
	$("#checkboxes").find('input').each(function(index, el) {
		$(this).click(function(){
			if($(this).prop('checked')){
				b++;
				$("#seleEscritorio").html(b+" Selecionados");
			}else{
				b--;
				$("#seleEscritorio").html(b+" Selecionados");
			}
		});
	});
	$("#checkboxes2").find('input').each(function(index, el) {
		$(this).click(function(){
			if($(this).prop('checked')){
				i++;
				$("#seleSistema").html(i+" Selecionados");
			}else{
				i--;
				$("#seleSistema").html(i+" Selecionados");
			}
		});
	});








	var obj = "";
	$("#forma2").click(function(event) {
		if(obj == ""){
			obj = $("#cursoTec").find('[disabled="disabled"]');
			obj.each(function(index, el) {
				$(this).removeAttr('disabled');
			});
		}else{
			obj.each(function(index, el) {
				$(this).attr('disabled','disabled');
			});
		 	obj="";
		}
	});
	var obj2 = "";
	$("#forma3").click(function(event) {
		if(obj2 == ""){
			obj2 = $("#cursoSup").find('[disabled="disabled"]');
			obj2.each(function(index, el) {
				$(this).removeAttr('disabled');
			});
		}else{
			obj2.each(function(index, el) {
				$(this).attr('disabled','disabled');
			});
		 	obj2="";
		}
	});
	$("#formEstudante").submit(function(event) {
		if(valida_cpf($("#id_cpf").val())){
			return true;
		}else{
			$("#erroCNPJ").show("fast").focus();
			return false;
		}
	});
	$("#id_cpf").click(function(event) {
		$("#erroCPF").hide();
	});


    $("#estado_id").change(function(event) {
        $.ajax({
            url: 'http://localhost/getstagio/cadastroAdicional/ajax/carregaCidade',
            type: 'POST',
            dataType: 'html',
            data: "cidade_id="+$(this).val(),
            success: function(e){
                $("#cidade_id").html(e);
            }
        });
    });
    $("#estado_id2").change(function(event) {
        $.ajax({
            url: 'http://localhost/getstagio/cadastroAdicional/ajax/carregaCidade',
            type: 'POST',
            dataType: 'html',
            data: "cidade_id="+$(this).val(),
            success: function(e){
                $("#cidade_id2").html(e);
            }
        });
    });
	 
});
