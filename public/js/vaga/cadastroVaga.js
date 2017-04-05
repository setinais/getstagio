$(document).ready(function() {
	
	$("#id_requisitosminimos").keydown(function(e) {
		if(e.keyCode  == 13){
			if($(this).val() != ""){
				$("#requist").append("<div class='input-group'><input type='text' name='requisito' disabled='' class='req_sito' value='"+$("#id_requisitosminimos").val()+"'><span class='remove_campo'>X</span></div>");
				$("#id_requisitosminimos").val("");
				$("#id_requisitosminimos").focus()
			}
			return false;
		}	
	}).blur(function(){
		if($(this).val() != ""){
			$("#requist").append("<div class='input-group'><input type='text' name='requisito' disabled='' class='req_sito' value='"+$("#id_requisitosminimos").val()+"'><span class='remove_campo'>X</span></div>");
			$("#id_requisitosminimos").val("");
		}
		var i=1;
		$("#requist>.input-group>input").each(function(index, el) {
			$(this).attr("name","requisito-"+i);i++;
			alert("da");
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


