
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
			$("#requist").append("<div class='input-group'><input style='width: 75%;' type='text' name='requisito' disabled='disabled' class='req_sito' value='"+$("#id_requisitos").val()+"'><span class='glyphicon glyphicon-edit edita_requisito' aria-hidden='true'></span><span class='remove_campo'>X</span></div>");
			$("#id_requisitos").val("");
		}
		var tamanho = 1;
		$("#requist>.input-group>input").each(function() {
			$(this).attr("name","requisito-"+tamanho);
			tamanho++;
		});

	});
	$("#id_cargahoraria").blur(function(event) {
		var val = parseInt($(this).val());
		if(val<0){
			$(this).val(1).focus();
		}else{
			$(this).val(val);
		}
	});
	$("#id_qnt").blur(function(event) {
		var val = parseInt($(this).val());
		if(val<0){
			$(this).val(1).focus();
		}else{
			$(this).val(val);
		}
	});
	$("#id_remuneracao").blur(function(event) {
		var val = parseInt($(this).val());
		if(val<0){
			$(this).val(0).focus();
		}else{
			$(this).val(val);
		}
	});
	$("#id_duracao").blur(function(event) {
		var val = parseInt($(this).val());
		if(val<0){
			$(this).val(1).focus();
		}else{
			$(this).val(val);
		}
	});
	$('#requist').on("click",".remove_campo",function(e) {
		$(this).parent().html('');
	});
	$("#form_cadastro_vaga").submit(function(event) {
		alert($("#cargo_id").html());
		alert($("#cargo_id").val() );
		if($("#cargo_id").html() == ""){
			$("#erro_cargo").html("<b>Nenhum cargo cadastrado por favor cadastre um cargo!</b>")
			$('#cadastrar_cargo').modal('show');
			return false;
		}else{
			$("input").each(function(){
				$(this).removeAttr('disabled');
			});
			var tamanho = 1;
			$("#requist>.input-group>input").each(function() {
				$(this).attr("name","requisito-"+tamanho);
				tamanho++;
			});
			return true;
		}
	});
	$("#form_cadastro_cargo").submit(function(event) {
		if($("#id_cargo").val() !=""){
			$.ajax({
				url: 'http://localhost/getstagio/estagio/ajax/cadastrarCargo',
				type: 'POST',
				dataType: 'html',
				data: "nome="+$("#id_cargo").val(),
				success: function(e){
					if(e == "true"){
						$("#erro_cargo").html("<p class='bg-success'>Cargo criado com sucesso.</p>");
						$.ajax({
							url: 'http://localhost/getstagio/estagio/ajax/carregaCargo',
							type: 'POST',
							dataType: 'html',
							data: "nome='nada'",
							success: function(e){
								$("#cargo_id").html(e);
							}
						});
						$("#id_cargo").val("");
					}else if(e == "false"){
						$("#erro_cargo").html("<p class='bg-danger'>Ocorreu algum erro ao cadastrar o cargo tente novamente!</p>");
					}
				}
			});
		}else{
			$(this).focus()
			$("#erro_cargo").append('<b>Por favor digite o nome do cargo!.</b>')
		}
		return false;
	});
	$("#tempo_servico").change(function(event) {
		if($(this).val() == "A decidir"){
			$("#id_duracao").val(0).removeAttr('required').attr('disabled', '');
		}else{
			$("#id_duracao").val(1).removeAttr('disabled').attr('required', 'required');
		}
	});
	$('#requist').on("click",".edita_requisito",function(e){
		$("#id_requisitos").val($(this).parent().find('input').val());
		$("#id_requisitos").focus();
		$(this).parent().remove();
	});
	$("")
});


