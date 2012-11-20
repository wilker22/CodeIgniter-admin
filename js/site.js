var util = {
	URL_HTTP: 'http://2tempos.com.br/novo/',
	URL_SPACER : 'http://2tempos.com.br/novo/img/spacer.gif',
	EOL : "\n"
},
	Fundo = function(_backgrounds, _elemento) {
	this.tempo = 2000;
	this.listaImgs = _backgrounds || [];
	this.elemento = _elemento || 'body';
	this.atual = this.listaImgs.length;
	this.nextImg = function() {
		var ret = false;
		if (this.listaImgs.length > 0) {
			this.atual++;
			if (typeof(this.listaImgs[this.atual]) === 'undefined')
			{
				this.atual = 0;
			}
			ret = this.listaImgs[this.atual];
		}
		return ret;
	};
	this.prevImg = function() {
		var ret = false,
			qtd = this.listaImgs.length;
		if (qtd > 0) {
			this.atual--;
			if (typeof(this.listaImgs[this.atual]) === 'undefined')
			{
				this.atual = qtd;
			}
			ret = this.listaImgs[this.atual];
		}
		return ret;
	};
	this.troca = function(prev) {
		var img = (typeof(prev) === 'undefined') ? this.nextImg() : this.prevImg();
		if (img)
		{
			$(this.elemento)
				.fadeTo('slow', 0, function() {
					$(this)
						.addClass('ggt-bg-slider')
						.css('background-image', 'url(' + img + ')');
				})
				.fadeTo('slow', 1);
		}
	};
	this.inicialize = function(funcao, _intervalo) {
		eval(funcao);
		var intervalo = _intervalo || 3, 
			timer = window.setInterval(funcao, (intervalo*1000));
		return timer;
	};
},
	compartilhamentos = {
	geraHtml: function(img, imgp, url, titulo) {
		var e = {
				img: encodeURI(img),
				imgp: encodeURI(imgp),
				url: encodeURI(url),
				titulo: encodeURI(titulo)
		},
		html = '<div class="fotos-overlay">'+util.EOL;
		html += '	<a rel="colecao" class="fancybox" data-thumbnail="'+imgp+'" href="'+img+'" title="'+titulo+'">'+util.EOL;
		html += '		<img alt="Ampliar foto" src="'+util.URL_HTTP+'img/ampliar.png" />'+util.EOL;
		html += '	</a>'+util.EOL;
		html += '	<hr />'+util.EOL;
		html += '	<p>Compartilhe</p>'+util.EOL;
		html += '	<a target="_blank" class="share" href="http://www.facebook.com/sharer.php?u='+e.img+'&t='+e.titulo+'" title="Share on Facebook">facebook</a>'+util.EOL;
		html += '	<a target="_blank" class="share" href="https://plus.google.com/share?url='+e.img+'&title='+e.titulo+'&hl=pt-BR" title="Share on Google+">google+</a>'+util.EOL;
		html += '	<a target="_blank" class="share" href="http://promote.orkut.com/preview?nt=orkut.com&tt='+e.titulo+'&du='+e.img+'&cn=" title="Share on Orkut">orkut</a>'+util.EOL;
		html += '	<a target="_blank" class="share" href="http://pinterest.com/pin/create/button/?url='+e.url+'&media='+e.img+'&description='+e.titulo+'" title="Share on Pinterest">pinterest</a>'+util.EOL;
		html += '	<a target="_blank" class="share" href="http://www.tumblr.com/share/photo?source='+e.img+'&caption='+e.titulo+'&clickthru='+e.url+'" title="Share on Tumblr">tumblr</a>'+util.EOL;
		html += '	<a target="_blank" class="share" href="https://twitter.com/share?url='+e.img+'&text='+e.titulo+'" title="Share on Twitter">twitter</a>'+util.EOL;
		html += '</div>'+util.EOL;
		return html;
	},
	inicialize : function() {
		$('.compartilha').each(function() {
			var $this = $(this),
				img = $this.attr('data-img'),
				imgp = $this.attr('data-imgp'),
				url = $this.attr('data-url'),
				titulo = $this.attr('data-titulo');
			$this
				.prepend(compartilhamentos.geraHtml(img, imgp, url, titulo))
				.hover(compartilhamentos.mostra,compartilhamentos.esconde);
		});
	},
	mostra : function() {
		$(this).find('.fotos-overlay').fadeIn('slow');
	},
	esconde : function(){
		$(this).find('.fotos-overlay').hide();
	} 
}, Destaques = {
		inicialize: function() {
			this.topo();
		},
		topo: function() {
			var qtd = $('#pl-destaques li').size();
		if(qtd) {
			var k = $("#pl-destaques"), 
				auto = (qtd > 1);
			k.wtRotator({
				width : 255,
				height : 241,
				button_width : 14,
				button_height : 14,
				button_margin : 10,
				auto_start : auto,
				delay : 3000,
				transition : "fade",
				transition_speed : 800,
				auto_center : true,
				block_size : 75,
				vert_size : 55,
				horz_size : 50,
				cpanel_align : "TR",
				timer_align : "bottom",
				display_thumbs : false,
				display_dbuttons : false,
				display_playbutton : false,
				tooltip_type : "image",
				display_numbers : false,
				display_timer : false,
				mouseover_pause : false,
				cpanel_mouseover : false,
				text_mouseover : false,
				text_effect : "fade",
				text_sync : true,
				shuffle : false,
				block_delay : 25,
				vstripe_delay : 73,
				hstripe_delay : 183
			});
		}
	}
};
$(function () {
	Destaques.inicialize();
	$('.compartilha').hover(compartilhamentos.mostra,compartilhamentos.esconde);
	$('.fancybox').fancybox({
		helpers:  {
	        thumbs : {
	            width: 50,
	            height: 50,
	            source  : function(current) {
	                return $(current.element).data('thumbnail');
	            }
	        }
	    }
	});
});