
$(document).ready(function() {
	$("#id_requisitos").keydown(function(e) {
		if(e.keyCode  == 13){
			if($(this).val() != ""){
				$("#requist").append("<div class='input-group'><input type='text' name='requisito' disabled='disabled' class='req_sito' value='"+$("#id_requisitos").val()+"'><span class='glyphicon glyphicon-edit edita_requisito' aria-hidden='true'></span><span class='remove_campo'>X</span></div>");
				$("#id_requisitos").val("");
				$("#id_requisitos").focus()
			}
			return false;
		}	
	}).blur(function(){
		if($(this).val() != ""){
			$("#requist").append("<div class='input-group'><input type='text' name='requisito' disabled='disabled' class='req_sito' value='"+$("#id_requisitos").val()+"'><span class='glyphicon glyphicon-edit edita_requisito' aria-hidden='true'></span><span class='remove_campo'>X</span></div>");
			$("#id_requisitos").val("");
		}
		var tamanho = 1;
		$("#requist>.input-group>input").each(function() {
			$(this).attr("name","requisito-"+tamanho);
			tamanho++;
		});

	});
	$('#requist').on("click",".remove_campo",function(e) {
		$(this).parent().html('');
	});
	$("form").submit(function(event) {
		$("input").each(function(){
			$(this).removeAttr('disabled');
		});
		var tamanho = 1;
		$("#requist>.input-group>input").each(function() {
			$(this).attr("name","requisito-"+tamanho);
			tamanho++;
		});
	});
	$('#requist').on("click",".edita_requisito",function(e){
		$("#id_requisitos").val($(this).parent().find('input').val());
		$("#id_requisitos").focus();
		$(this).parent().remove();
	});
	
});


