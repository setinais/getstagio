
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
	
});
