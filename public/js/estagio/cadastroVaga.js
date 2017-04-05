$(document).ready(function() {
	v=1;
	$("#id_requisitos").keydown(function(e) {
		if(e.keyCode  == 13){
			if($(this).val() != ""){
				$("#requist").append("<div class='input-group'><input type='text' name='requisito'  class='req_sito' value='"+$("#id_requisitos").val()+"'><span class='remove_campo'>X</span></div>");
				$("#id_requisitos").val("");
				$("#id_requisitos").focus()
				v++;
			}
			return false;
		}	
	}).blur(function(){
		if($(this).val() != ""){
			$("#requist").append("<div class='input-group'><input type='text' name='requisito'  class='req_sito' value='"+$("#id_requisitos").val()+"'><span class='remove_campo'>X</span></div>");
			$("#id_requisitos").val("");
			v++;
		}
		var i=1;
		$("#requist>.input-group>input").each(function(index, el) {
			$(this).attr("name","requisito-"+i);i++;
		});
	});
	$('#requist').on("click",".remove_campo",function(e) {
		$(this).parent().html('');
	});
	$("form").submit(function(event) {
		$("input").each(function(){
			$(this).removeAttr('disabled');
		});
	});

});


