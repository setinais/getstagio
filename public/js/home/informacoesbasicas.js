jQuery(document).ready(function($){
	//$("#id_telefone").mask('(99)99999-9999');
 	$("#id_telefone").click(function(event) {
 		$(this).unmask()
 	});
 	$("#id_telefone").blur(function(){
 		if($(this).val().length <= 10 || $(this).val().length == 10){
 			$("#id_telefone").mask('(99)9999-9999');
 		}else{
 			$("#id_telefone").mask('(99)99999-9999');
 		}
 	});
 	if($("#id_telefone").val() != ""){
 		if($(this).val().length <= 10 || $(this).val().length == 10){
 			$("#id_telefone").mask('(99)9999-9999');
 		}else{
 			$("#id_telefone").mask('(99)99999-9999');
 		}
 	}
	$("#id_cep").mask('99999-999');
	$("#estado_id").blur(function(){
 	$("datalist").each(function(e){
 			if($(this).attr('data')){
 				$(this).attr('id',$(this).attr('data'));
 			}
 		})
 		$("#"+$(this).val()).attr('data',$(this).val());
 		$("#"+$(this).val()).attr('id','id_cidade');
 	});
 	$("#estado_id").change(function(event) {
 		$.ajax({
			url: 'http://localhost/getstagio/listar/ajax/carregaCidade',
			type: 'POST',
			dataType: 'html',
			data: "cidade_id="+$(this).val(),
			success: function(e){
				$("#cidade_id").html(e);
			}
		});
 	});

 	$("#cidade_id").click(function(event) {
 		if($(this).html() == ""){
 			$("#estado_id").focus().select();
 		}
 	});
});