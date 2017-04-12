
$(document).ready(function() {
	$("#busca").keyup(function(event) {
		var pesquisa = $(this).val();
		if(pesquisa != ""){
			$(".busca").each(function(index, el) {
				var registro = $(this).html();
				var match = pesquisa.split(" ").every(function (palavra) {
					var regex = new RegExp(palavra, "i");
					return regex.test(registro);
				});
				if(!match){
					//alert($(this).html());
					$(this).parent().parent().hide("fast");
				}else{
					$(this).parent().parent().show("fast");
				}
			});
		}else{
			$(".busca").each(function(index, el) {
				$(this).parent().parent().show("fast");
			});
		}
	});
	$(document).on( "click",".candidatar", function(){
		var id = $(this).attr('id');
		obj = $(this);
		$.ajax({
			url: 'http://localhost/getstagio/estagio/ajax',
			type: 'POST',
			dataType: 'html',
			data: "id="+id,
		})
		.done(function(e) {
			if(e == "true"){
				obj.removeClass('label-success');
				obj.addClass('label-info');
				obj.find('.troca').html("Inscrito");
				obj.css('cursor', 'not-allowed');
				obj.parent().append(" <span class='label label-danger descandidatar' id='"+id+"' style='cursor: pointer;'><span class='glyphicon glyphicon-log-in'></span>  <span class='troca'> Desinscrever</span></span>")
			}
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
		
	});
	$(document).on( "click",".descandidatar", function(){
		var id = $(this).attr('id');
		obj = $(this);
		$.ajax({
			url: 'http://localhost/getstagio/estagio/ajax/descandidatar',
			type: 'POST',
			dataType: 'html',
			data: "id="+id,
		})
		.done(function(e) {
			if(e == "true"){
				obj.parent().html("<span class='label label-success candidatar' id='"+id+"' style='cursor: pointer;'><span class='glyphicon glyphicon-log-in'></span>  <span class='troca'>Inscrever</span></span>")
			}
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
		
	});
});