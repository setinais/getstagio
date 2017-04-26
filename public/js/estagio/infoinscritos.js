$(document).ready(function($){
	$(document).on( "click","[a:data-id-vaga]", function(){
		
		obj = $(this);
		$.ajax({
			url: 'http://localhost/getstagio/estagio/ajax'+id,
			type: 'POST',
			dataType: 'html',
			data: "id="+id,
		})
		.done(function(volta) {
			$('#infoinscritos').html(volta);
		})
		.fail(function() {
			console.log("error");
		})
		.always(function() {
			console.log("complete");
		});
		
	});
});