<?php $i = 0; ?>
<fieldset class="ui-widget-content span-24">
	<img src="<?php echo base_url();?>img/edit.png" alt="Lapis" class="formIcon" />
	<legend class="ui-widget-header ui-corner-all"><?php echo $titulo; ?></legend>
	<div id="divErros" class="<?php echo isset($validacao['class']) ? $validacao['class'] : ''; ?>"><?php echo isset($validacao['msg']) ? $validacao['msg'] : ''; ?></div>
	<form action="<?php echo $action ?>" method="post" id="html5form" <?php echo isset($enctype) ? 'enctype="'.$enctype.'"' : ''; ?>>
		<?php foreach ($campos as $campo): $i++; ?>
		<div <?php echo isset($campo['extra_div']) ? $campo['extra_div'] : ''?>>
			<label for="ggt_<?php echo $campo['field']?>"><?php echo $campo['label']?></label>
			<?php switch ($campo['tipo']) 
			{
				case 'checkbox':
					if (isset($campo['itens']))
					{
						print '<div class="divInput">';
						foreach ($campo['itens'] AS $id => $item)
						{
							$i++;
							print '<div '.(isset($campo["extra_campo"]) ? $campo["extra_campo"] : '').'><input type="checkbox" name="'.$campo["field"].'[]" id="ggt_'.$campo["field"].$i.'" value="'.$id.'" title="'.$item.'" '.((isset($dados->{$campo["field"]}) && $dados->{$campo["field"]} == $id) ? 'checked="checked"' : '').' /> <label for="ggt_'.$campo["field"].$i.'">'.$item.'</label></div>';
						}
						print '</div>';
					}
					break;
				case 'radio':
					if (isset($campo['itens']))
					{
						print '<div class="divInput">';
						foreach ($campo['itens'] AS $id => $item)
						{
							$i++;
							print '<div '.(isset($campo["extra_campo"]) ? $campo["extra_campo"] : '').'><input type="radio" name="'.$campo["field"].'" id="ggt_'.$campo["field"].$i.'" value="'.$id.'" title="'.$item.'" '.((isset($dados->{$campo["field"]}) && $dados->{$campo["field"]} == $id) ? 'checked="checked"' : '').' /> <label for="ggt_'.$campo["field"].$i.'">'.$item.'</label></div>';
						}
						print '</div>';
					}
					break;
				case 'select':
					print '<select name="'.$campo["field"].'" id="ggt_'.$campo["field"].'" title="'.$campo["label"].'" '.( isset($campo["extra_campo"]) ? $campo["extra_campo"] : "").'>';
					print gera_select_option($campo['itens'], set_value($campo["field"], isset($dados->{$campo["field"]}) ? $dados->{$campo["field"]} : $campo["selecionado"]), 'Selecione...');
					print '</select>';
					break;
				case 'textarea':
					print '<textarea name="'.$campo["field"].'" id="ggt_'.$campo["field"].'" placeholder="'.$campo["label"].'" '.( isset($campo["extra_campo"]) ? $campo["extra_campo"] : "").'>'. set_value($campo["field"], isset($dados->{$campo["field"]}) ? $dados->{$campo["field"]} : '').'</textarea>';
					break;
				case 'file':
	   				print '<input type="file" name="'.$campo["field"].'" id="ggt_'.$campo["field"].'" placeholder="'.$campo["label"].'" '.( isset($campo["extra_campo"]) ? $campo["extra_campo"] : "").' rel="'. (isset($dados->{$campo["field"]}) ? $dados->{$campo["field"]} : '').'" />';
	   				break;
				default:
					print '<input type="'.(isset($campo["tipo"]) ? $campo["tipo"] : "").'" name="'.$campo["field"].'" id="ggt_'.$campo["field"].'" placeholder="'.$campo["label"].'" '.( isset($campo["extra_campo"]) ? $campo["extra_campo"] : "").' value="'. set_value($campo["field"], isset($dados->{$campo["field"]}) ? $dados->{$campo["field"]} : '').'" />';
					break;
			}?>
		</div>
		<?php endforeach;?>
		<div class="clear botoes">
			<?php echo isset($botoes) ? $botoes : ''; ?>
			<input type="submit" value="Salvar" class="submit"/>
		</div>
	</form>
</fieldset>