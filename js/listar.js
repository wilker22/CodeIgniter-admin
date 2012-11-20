var linha_selecionada = "";
var bt_direito = {
	getSelecionados: function (id) {
		var $ggt_selecionados = [];
		$(".ggt_listagem tbody input[type=checkbox]").each(function() {
			if (this.checked) {
				$ggt_selecionados.push(this.value);
			}
		});
		if ($ggt_selecionados.length == 0) $ggt_selecionados.push(linha_selecionada);
		return $ggt_selecionados;
	},
	nenhumSelecionado: function() {
		util.popUp("Nenhum Item Selecionado");
	},
	adicionar: function () {
		window.location = ACAO_ADICIONAR;
	},
	editar: function(id) {
		var $selecionados = [];
		if (typeof(id) === 'undefined')
			$selecionados = this.getSelecionados();
		else
			$selecionados.push(id);		
		if($selecionados.length > 0)
		{
			var $i=1, url = "";
			for (;$i<$selecionados.length;)
			{
				url = ACAO_EDITAR + $selecionados[$i];
				util.abreJanela(url, $i);
				$i++;
			}
			window.location = ACAO_EDITAR + $selecionados[0];
		}
		else
		{
	
			this.nenhumSelecionado();
		}
	},
	exportar: function() {
		var $param = $("#ggt_parametros_url").val();
		window.location = ACAO_EXPORTAR + "?" + $param;
	},
	deletar: function(id) {
		var $selecionados = [];
		if (typeof(id) === 'undefined')
			$selecionados = this.getSelecionados();
		else
			$selecionados.push(id);
		
		if($selecionados.length > 0)
		{
			var $url = ACAO_REMOVER;
			if (window.confirm("deseja apagar os ("+$selecionados.length+") itens selecionados? "))
			{
				$.post($url, { "selecionados": $selecionados}, function(data){
					util.popUp(data, util.reload);
				});
			}
		}
		else
		{
			this.nenhumSelecionado();
		}
	}
};
var listagem = {
	clicaLinha: function() {
		var $this = $(this),
			$check = $this.find("input[type=checkbox]");
		if ($check.attr("checked")) {
			$this.removeClass("ggt_linha_selecionada");
			$check.removeAttr("checked");
		} else {
			$check.attr("checked", "checked");
			$this.addClass("ggt_linha_selecionada");
		}
		listagem.verificaCheck();
	},
	verificaCheck: function() {
		var $ggt_todos = $("#ggt_seleciona_todos");
		$ggt_todos.attr("checked", "checked");
		$(".ggt_listagem tbody input[type=checkbox]").each(function() {
			if (!this.checked) {
				$ggt_todos.removeAttr("checked");
				return false;
			}
		});
	},
	sobreLinha: function () {
		linha_selecionada = $(this).find("input[type=checkbox]").val();
	},
	selecionaTodos: function() {
		var $selecionado = $(this).attr("checked"),
			$linhas = $(".ggt_listagem tbody tr");
		if ($selecionado) {
			$linhas.addClass("ggt_linha_selecionada");
		} else {
			$linhas.removeClass("ggt_linha_selecionada");
		}
		$(".ggt_listagem input[type=checkbox]").each(function() {
			this.checked = $selecionado;
		});
	},
	fixClickCheck: function() {
		this.checked = !this.checked;
	}
};
$(function() {
	$.mask.definitions['~']='[SsNn]';
	$(".ggt_bolean").mask("~");
	$(".ggt_listagem tbody tr")
		.click(listagem.clicaLinha)
		.bind("mouseenter", listagem.sobreLinha)
		.contextMenu({
			menu : "botao_direito",
			leftButton : true
		});
	$(".ggt_listagem tbody input[type=checkbox]").click(listagem.fixClickCheck);
	$("#ggt_seleciona_todos").click(listagem.selecionaTodos);
	$(".ggt_paginacao a").button();	
	
	$(".save").click(function(event) {
		event.preventDefault();
		var $this = $(this), 
			href = $this.attr('href'), 
			id = $this.attr('rel'),
			ordem = $("#ordem"+id).val(),
			descricao = $("#descricao"+id).val();
		
		$.ajax({
			  type: 'POST',
			  url: href,
			  data: { 'ordem': ordem, 'descricao': descricao},
			  success: function(data) {
				  util.popUp(data, util.reload);
			  }
		});
	});
	$(".img").click(function(event) {
		event.preventDefault();
		var $this = $(this), 
			src = $this.attr('src');
		util.popUp('<img src="'+src+'" />', null, {'titulo': 'Preview'});
	});
});