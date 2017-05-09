
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


	 
});
