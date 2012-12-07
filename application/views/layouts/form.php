<fieldset class="ui-widget-content span-24">
	<img src="<?php echo base_url();?>img/edit.png" alt="Lapis" class="formIcon" />
	<legend class="ui-widget-header ui-corner-all"><?php echo isset($titulo) ? $titulo : 'Formulário'; ?></legend>
	<div id="divErros" class="<?php echo isset($validacao['class']) ? $validacao['class'] : ''; ?>"><?php echo isset($validacao['msg']) ? $validacao['msg'] : ''; ?></div>
	<form action="<?php echo $action ?>" method="post" id="html5form" <?php echo isset($enctype) ? 'enctype="'.$enctype.'"' : ''; ?>>
		<?php echo (isset($campos)) ? monta_campos_form($campos, isset($dados) ? $dados : NULL) : ''; ?>
		<?php echo (isset($form_html)) ? $form_html : ''; ?>
		<div class="clear botoes">
			<?php echo isset($botoes) ? $botoes : ''; ?>
			<input type="submit" value="Salvar" class="submit"/>
		</div>
	</form>
</fieldset>