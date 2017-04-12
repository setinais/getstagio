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
				obj.parent().parent().remove();
			}
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
});