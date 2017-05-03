$(document).ready( function () {
		$('#example').DataTable({
			paging: false,
			ordering: true,
			"orderMulti": false
		});
	} );
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