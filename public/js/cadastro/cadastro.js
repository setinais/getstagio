
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
