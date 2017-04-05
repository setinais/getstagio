$(document).ready(function() {
	v=1;
	$("#id_requisitos").keydown(function(e) {
		if(e.keyCode  == 13){
			if($(this).val() != ""){
				$("#requist").append("<div class='input-group'><input type='text' name='requisito"+v+"'  class='req_sito' value='"+$("#id_requisitos").val()+"'><span class='remove_campo'>X</span></div>");
				$("#id_requisitos").val("");
				$("#id_requisitos").focus()
				v++;
			}
			return false;
		}	
	}).blur(function(){
		if($(this).val() != ""){
			$("#requist").append("<div class='input-group'><input type='text' name='requisito"+v+"'  class='req_sito' value='"+$("#id_requisitos").val()+"'><span class='remove_campo'>X</span></div>");
			$("#id_requisitos").val("");
			v++;
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
<<<<<<< HEAD
	$("form").submit(function(event) {
		$("input").each(function(){
			$(this).removeAttr('disabled');
		});
	});
=======
>>>>>>> bb518c6ad0b6260b50d5f51473b4ad95ab1e59cf
});


