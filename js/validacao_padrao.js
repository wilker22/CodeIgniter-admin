$(function(){
	$('#html5form').submit(function() {
		var error = [], msg = '';
		$(".required").each(function() {
			var $this = $(this), 
				valor = $.trim($this.val()),
				titulo = '';
			
			if(valor.length < 1) {
				titulo = $this.attr("placeholder");
				if (typeof(titulo) === 'undefined')
					titulo = $this.attr("title");
				if (typeof(titulo) !== 'undefined')
					error.push('<label for="'+$this.attr("id")+'">'+titulo+'</label>');
			}
		});
		if(error.length > 0) {
			msg = (error.length === 1) ? '<h4>Preencha o campo <u>'+error[0]+'</u> corretamente.</h4>' : '<h4>Preencha os campos abaixo corretamente:</h4><ul><li>'+error.join('</li><li>')+'</li></ul>';
			$('#divErros')
				.removeClass('success')
				.addClass('error')
				.html(msg);
			return false;
		} else {
			return true;
		}
	});
});