$(document).ready(function() {
	$("#busca").keyup(function(event) {
		var pesquisa = $(this).val();
		if(pesquisa != ""){
			$(".table>tbody>tr").each(function(index, el) {
				obj = $(this); test = false;
				obj.find(".busca").each(function(index, el) {
					var registro = $(this).html();
					var match = pesquisa.split(" ").every(function (palavra) {
						var regex = new RegExp(palavra, "i");
						return regex.test(registro);
					});
					if(match && !test){
						test = true;
					}
				});
				if(!test){
					obj.hide('fast');
				}else{
					obj.show('fast')
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
			url: 'http://localhost/getstagio/listar/ajax',
			type: 'POST',
			dataType: 'html',
			data: "id="+id,
		})
		.done(function(e) {
			e = e.replace(/^\s+|\s+$/g,"");
			if(e = "true"){
				obj.removeClass('label-success');
				obj.removeClass('candidatar');
				obj.addClass('label-info');
				obj.find('.troca').html("Inscrito");
				obj.css('cursor', 'not-allowed');
				obj.parent().append(" <span class='label label-danger descandidatar' id='"+id+"' style='cursor: pointer;'><span class='glyphicon glyphicon-log-in'></span>  <span class='troca'> Cancelar</span></span>")
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
			url: 'http://localhost/getstagio/listar/ajax/descandidatar',
			type: 'POST',
			dataType: 'html',
			data: "id="+id,
		})
		.done(function(e) {
			e = e.replace(/^\s+|\s+$/g,"");
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