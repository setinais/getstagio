$(document).ready(function(){
	$(document).on( "click",".inscritos", function(){
		id = $(this).attr('data-id-vaga');
		$.ajax({
			url: 'http://localhost/getstagio/listar/ajax/carregaCandidato',
			type: 'POST',
			dataType: 'html',
			data: "vaga_id="+id,
		})
		.done(function(volta) {
			$('#estudantes_candidatos').html(volta);
			$("#aparece").hide("fast",function(){
			$("#troca").show("fast");
			$("#texto").html("Candidatos Inscritos");
			});
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
	});
	$("#some").click(function(){
		$("#troca").hide("fast",function(){
			$("#aparece").show("fast");
			$("#texto").html("Situação das Vagas");
		});
	})
});