var util = {
	URL_HTTP: 'http://localhost:8888/admin/',
	abreJanela: function(link, id, titulo) {
		window.open(link, id);
	},
	popUp: function(mensagem, callback, config) {
		var padrao = {
			'titulo': 'Aviso',
			'largura': '500',
		};
		if (typeof(callback) !== "function") {
		    callback = function() {
                $(this).dialog( "destroy" ).remove();
            };
		}
		if (typeof(config) === "undefined") {
			config = padrao;
		} else {
			if (typeof(config.titulo) === "undefined") {
				config.titulo = padrao.titulo;
			}
			if (typeof(config.largura) === "undefined") {
				config.largura = padrao.largura;
			}
		}
		$('body').append('<div id="dialog-message" title="'+config.titulo+'">'+mensagem+'</div>');
		$( "#dialog-message" ).dialog({
            modal: true,
            resizable: true, 
            width: config.largura,
            buttons: {
                Ok: callback
            }
        }).resizable( "option", "animate", true );
	},
	reload: function () { 
		var x = window.location;
		window.location = x;
	},
	createHiddenElement: function(name, value) {
		var element 	= document.createElement("input");
		element.type 	= "hidden";
		element.name 	= name;
		element.value 	= value;
		return element;
	},
	atualizaSelect: function(id, dados, ativo, branco) {
		if(typeof(branco) !== "number") branco = 0;
		if(typeof(dados) !== "object") object = [];
		
		var i=0; qtd = dados.length; select = document.getElementById(id);
		select.options.length = branco;
		
		for (;i<qtd;) {
			select.options[(i+branco)] = new Option(dados[i].nome, dados[i].id);
			i++;
		}
		
		if(typeof(ativo) === "number") select.value = ativo;
	}
};

$(function() {
	$(".ggt_data").each(function() {
		$(this).datepicker({
			changeMonth: true,
			changeYear: true
		}).mask("39/19/9999");
	});
	$('.ggt_hora').mask("29:69");
	$('.ggt_phone')
		.mask("(99) 9999-9999?9")
		.live('focusout', function (event) {
			var target, phone, element;
			target = (event.currentTarget) ? event.currentTarget : event.srcElement;
			phone = target.value.replace(/\D/g, '');
			element = $(target);
			element.unmask();
			if(phone.length > 10) {
				element.mask("(99) 99999-999?9");
			} else {
				element.mask("(99) 9999-9999?9");
			}
	});
	$("input:submit, .button, button").button();
});