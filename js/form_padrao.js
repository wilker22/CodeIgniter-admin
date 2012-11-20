$(function() {
	$('input[type=file]').each(function() {
	    var $this = $(this), titulo = $this.attr('placeholder'), link = $this.attr('rel');
	    $this.parent().find('label').html(titulo).after('<div style="float:right;padding-right: 20px;">'+link+'</div>');
	});
});