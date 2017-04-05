$(document).ready(function($) {
	$("#Finalizar").click(function(event) {
		var string = checkboxValues();
		window.location.href = "/getstagio/estagio/finalizarVaga/"+string;
	});
	$("#Reabrir").click(function(event) {
		var string = checkboxValues();
		window.location.href = "/getstagio/estagio/reabrirVaga/"+string;
	});
	$("#Excluir").click(function(event) {
		var string = checkboxValues();
		window.location.href = "/getstagio/estagio/eliminarVaga/"+string;
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