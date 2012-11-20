<?php if (isset($filtro)): ?>
<fieldset class="ui-widget-content span-24">
	<legend	class="ui-widget-header ui-corner-all"><?php echo (isset($titulo)) ? $titulo : 'Pesquisar' ?></legend>
	<img src="<?php echo base_url();?>img/search.png" alt="Lupa" class="formIcon" />
	<?php print $filtro;?>
</fieldset>
<?php endif; ?>

<?php if (empty($paginacao)): ?>
<h3>Mostrando os Registros: <?php echo $num_itens; ?></h3>
<?php endif; ?>

<div class="clearfix">
<?php if ( ! empty($listagem)): ?>
	<?php print ( ! empty($paginacao)) ? '<div class="ggt_paginacao"><span class="registros"><strong>Registros</strong>: '.$num_itens.' - <strong>Páginas</strong>: </span>'.$paginacao.'</div>' : ''; ?>
	<?php print $listagem; ?>
	<?php print ( ! empty($paginacao)) ? '<div class="ggt_paginacao"><span class="registros"><strong>Registros</strong>: '.$num_itens.' - <strong>Páginas</strong>: </span>'.$paginacao.'</div>' : ''; ?>
<?php else: ?> 
	<p class="error txt-center">Nenhum resultado encontrado.</p>
<?php endif; ?>
</div>


<?php if (isset($botao_direito)):?>
<!-- Opções do menu no botão direito -->
<ul id="botao_direito" class="context-menu">
	<?php foreach ($botao_direito AS $acao): ?>
		<li class="<?php print $acao['class'];?> separator" title="<?php print $acao['descricao']; ?>">
		<?php if (isset($acao['click'])): ?>
			<a class="cursor" onclick="<?php print $acao['click']; ?>"><?php print $acao['descricao']; ?></a>
		<?php else: ?>
			<?php print $acao['descricao']; ?>
		<?php endif; ?>
		</li>
	<?php endforeach;?>
	<li class="quit separator"><a href="#sair">Fechar Janela</a></li>
</ul>
<?php endif;?>

<script type="text/javascript">
<?php if (isset($acao_exportar)):?>
var ACAO_EXPORTAR = '<?php echo $acao_exportar;?>';
<?php endif; ?>
<?php if (isset($acao_adicionar)):?>
var ACAO_ADICIONAR = '<?php echo $acao_adicionar;?>';
<?php endif; ?>
<?php if (isset($acao_editar)):?>
var ACAO_EDITAR = '<?php echo $acao_editar;?>';
<?php endif; ?>
<?php if (isset($acao_remover)): ?>
var ACAO_REMOVER = '<?php echo $acao_remover;?>';
<?php endif; ?>
</script>