<?php if (isset($imagem)): ?>
<?php 
list($current_width, $current_height) = getimagesize($imagem);
$proporcao = ceil((($current_width*100)/500)/100);
//$proporcao = 1;
?>
<form action="<?php echo $action ?>" method="post" id="html5form">
<input title="Imagem" class="ggt_required" type="hidden" name="imagem" id="imagem" value="<?php echo $imagem;?>" />
<input title="Selecione a área do recorte" type="hidden" class="required" name="x1" id="x1" />
<input type="hidden" name="y1" id="y1" />
<input type="hidden" name="x2" id="x2" />
<input type="hidden" name="y2" id="y2" />
<input type="hidden" name="largura" id="w" value="<?php echo $tamanho['w']; ?>" />
<input type="hidden" name="altura" id="h" value="<?php echo $tamanho['h']; ?>" />
<?php 
//manter proporcao
$current_width 	= ceil($current_width/$proporcao);
$current_height = ceil($current_height/$proporcao);
$tamanho['w'] 	= ceil($tamanho['w']/$proporcao);
$tamanho['h'] 	= ceil($tamanho['h']/$proporcao);
?>
	<fieldset>
    	<img src="<?php echo base_url();?>img/edit.png" alt="Lapis" class="formIcon" />
        <legend>Imagem -> Recortar</legend>
        
        <div id="divErros" class="<?php echo isset($validacao['class']) ? $validacao['class'] : ''; ?>"><?php echo isset($validacao['msg']) ? $validacao['msg'] : ''; ?></div>
        <table>
		<thead>
			<tr>
				<th>Original</th>
				<th>Preview</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td valign="top">
					<img src="<?php echo $imagem_url.'?'.mktime(); ?>" id="img-principal" alt="Selecione o espaço da imagem" width="<?php echo $current_width;?>" height="<?php echo $current_height; ?>" />
				</td>
				<td valign="top">
					<div style="position:relative; overflow:hidden; margin: 0; width:<?php echo $tamanho['w']; ?>px; height:<?php echo $tamanho['h']; ?>px;">
						<img id="img-thumbnail" src="<?php echo $imagem_url.'?'.mktime(); ?>" style="position: relative; width: <?php echo $tamanho['w']; ?>px; height: <?php echo $tamanho['h']; ?>px; margin-left: 0px; margin-top: 0px;" alt="Previsualização da imagem" />
					</div>
				</td>
			</tr>
		</tbody>
		</table>
		<?php echo isset($botoes) ? $botoes : ''; ?>
        <input type="submit" value="Salvar" class="submit"/>
	</fieldset>
</form>
<script type="text/javascript">
var w = <?php echo $tamanho['w']; ?>, 
	h = <?php echo $tamanho['h']; ?>,
	_width = <?php echo $current_width; ?>,
	_height = <?php echo $current_height; ?>,
	_proporcao = <?php echo $proporcao;?>;
</script>
<?php else: ?>
<p>Imagem não encontrada.</p>
<?php endif; ?>