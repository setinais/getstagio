$(document).ready(function($) {
	$("#Finalizar").click(function(event) {
		var string = checkboxValues();
		if(string != ""){
		window.location.href = "/getstagio/listar/finalizarVaga/"+string;
		}
	});
	$("#Reabrir").click(function(event) {
		var string = checkboxValues();
		if(string != ""){
		window.location.href = "/getstagio/listar/reabrirVaga/"+string;
		}
	});
	$("#Excluir").click(function(event) {
		var string = checkboxValues();
		if(string != ""){
			window.location.href = "/getstagio/listar/eliminarVaga/"+string;
		}
	});
	
});

function checkboxValues()
{
	var check = $("input:checkbox[name^=value_id]:checked");
	var ids = [];
	if(check.length > 0)
	{
		check.each(function() {
			ids.push($(this).val());
		});
	}
	var string = "";
	for (var i = 0; i < ids.length; i++) {
		string += ids[i]+"-";
	}

	return string;
}