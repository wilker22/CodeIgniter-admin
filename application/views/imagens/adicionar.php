<form enctype="multipart/form-data" id="html5form" method="post" action="<?php echo $url ?>">
	<div id="divErros" class="<?php echo isset($validacao['class']) ? $validacao['class'] : ''; ?>"><?php echo isset($validacao['msg']) ? $validacao['msg'] : ''; ?></div>
	<div class="coluna2">
		<label for="ggt_descricao">Descrição</label>
		<input type="text" value="<?php echo set_value('descricao');?>" autocomplete="off" maxlength="100" class="inputTxt" placeholder="Descrição" id="ggt_descricao" name="descricao">
	</div>
	<div class="coluna2">
		<label for="ggt_ordem">N. Ordem</label>
		<input type="number" value="<?php echo set_value('ordem', 9999);?>" autocomplete="off" maxlength="3" class="inputTxt required" placeholder="N. Ordem" id="ggt_" name="ordem">
	</div>
	<div class="coluna">
		<label for="ggt_imagem">Imagem Imagem (jpg)</label>
		<input type="file" rel="" class="inputTxt" placeholder="Imagem" id="ggt_imagem" name="imagem">
	</div>
	<?php echo isset($botoes) ? $botoes : ''; ?>
	<input type="submit" class="submit ui-button ui-widget ui-state-default ui-corner-all" value="Enviar">
</form>