jQuery(document).ready(function($) {
	$("#id_telefone").mask('(99)9 9999-9999');
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
});
