
function troca(tipo)
{
	
	switch(tipo)
	{
		case '1': 
		$("#instituicao").hide();
		$("#estudante").show('slow');
		break;
		case '2': 
		$("#estudante").hide();
		$("#instituicao").show('slow');
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
	$("#id_cnpj").click(function(event) {
		$("#erroCNPJ").hide();
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
	$("#situacaoTec").change(function(event) {
		if($(this).val()=="Incompleto"){
			$("#fimCT").attr('disabled', 'disabled');
		}else{
			$("#fimCT").removeAttr('disabled');
		}
	});
	$("#sitSP").change(function(event) {
		if($(this).val()=="Incompleto"){
			$("#fimSP").attr('disabled', 'disabled');
		}else{
			$("#fimSP").removeAttr('disabled');
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
            	alert(e)
                $("#cidade_id").html(e);
            }
        });
    });
	 
});
