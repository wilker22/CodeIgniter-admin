<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if(!function_exists('page_to_string'))
{
	function page_to_string($link)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_URL, $link);
		$string = curl_exec($curl);
		curl_close($curl);
		return $string;
	}
}
if(!function_exists('monta_campos_form'))
{
	function monta_campos_form($campos = array(), $dados = NULL)
	{
		$html = '';
		if(count($campos))
		{
			$i = 0;
			$dados = isset($dados) ? $dados : new stdClass();
			foreach ($campos as $campo)
			{
				$i++;
				$value = set_value($campo["field"], isset($dados->{$campo["field"]}) ? $dados->{$campo["field"]} : '');
				$dados->{$campo["field"]} = isset($dados->{$campo["field"]}) ? $dados->{$campo["field"]} : '';
				$campo['extra_div'] = isset($campo['extra_div']) ? $campo['extra_div'] : '';
				$campo["field"] = isset($campo["field"]) ? $campo["field"] : '';
				$campo["extra_campo"] = isset($campo["extra_campo"]) ? $campo["extra_campo"] : '';
				$campo["tipo"] = isset($campo["tipo"]) ? $campo["tipo"] : "text";
				$campo["selecionado"] = !empty($value) ? $value : (isset($campo["selecionado"]) ? $campo["selecionado"] : '');

				$html .= PHP_EOL.'<div '.$campo['extra_div'].'>';
				$html .= PHP_EOL.'<label for="ggt_'.$campo['field'].'">'.$campo['label'].'</label>';
				switch ($campo['tipo'])
				{
					case 'checkbox':
						if (isset($campo['itens']))
						{
							$html .= PHP_EOL.'<div class="divInput">';
							foreach ($campo['itens'] AS $id => $item)
							{
								$i++;
								$html .= PHP_EOL.'<div '.$campo["extra_campo"].'><input type="checkbox" name="'.$campo["field"].'[]" id="ggt_'.$campo["field"].$i.'" value="'.$id.'" title="'.$item.'" '.(($dados->{$campo["field"]} == $id) ? 'checked="checked"' : '').' /> <label for="ggt_'.$campo["field"].$i.'">'.$item.'</label></div>';
							}
							$html .= PHP_EOL.'</div>';
						}
						break;
					case 'radio':
						if (isset($campo['itens']))
						{
							$html .= PHP_EOL.'<div class="divInput">';
							foreach ($campo['itens'] AS $id => $item)
							{
								$i++;
								$html .= PHP_EOL.'<div '.$campo["extra_campo"].'><input type="radio" name="'.$campo["field"].'" id="ggt_'.$campo["field"].$i.'" value="'.$id.'" title="'.$item.'" '.(($dados->{$campo["field"]} == $id) ? 'checked="checked"' : '').' /> <label for="ggt_'.$campo["field"].$i.'">'.$item.'</label></div>';
							}
							$html .= PHP_EOL.'</div>';
						}
						break;
					case 'select':
						$html .= PHP_EOL.'<select name="'.$campo["field"].'" id="ggt_'.$campo["field"].'" title="'.$campo["label"].'" '.$campo["extra_campo"].'>';
						$html .= PHP_EOL.gera_select_option($campo['itens'], $campo["selecionado"], 'Selecione...');
						$html .= PHP_EOL.'</select>';
						break;
					case 'textarea':
						$html .= PHP_EOL.'<textarea name="'.$campo["field"].'" id="ggt_'.$campo["field"].'" placeholder="'.$campo["label"].'" '.$campo["extra_campo"].'>'. $value.'</textarea>';
						break;
					case 'file':
						$html .= PHP_EOL.'<input type="file" name="'.$campo["field"].'" id="ggt_'.$campo["field"].'" placeholder="'.$campo["label"].'" '.$campo["extra_campo"].' rel="'.$dados->{$campo["field"]}.'" />';
						break;
					default:
						$html .= PHP_EOL.'<input type="'.$campo["tipo"].'" name="'.$campo["field"].'" id="ggt_'.$campo["field"].'" placeholder="'.$campo["label"].'" '.$campo["extra_campo"].' value="'. $value.'" />';
						break;
				}
				$html .= PHP_EOL.'</div>';
			}
		}
		return $html;
	}
}
if(!function_exists('pega_chave_array'))
{
	function pega_chave_array($array = array(), $posicao = 0)
	{
		$ret = null;
		if (count($array))
		{
			$array = array_keys($array);
			if ($array[$posicao])
				$ret = $array[$posicao]; 
		}
		return $ret;
	}
}
if(!function_exists('mysql_data'))
{
	function mysql_data($data, $now = false)
	{
		$ret = ($now) ? date('Y-m-d') : null;
		if (strlen($data) === 10)
		{
			$data = explode('/', $data);
			if (count($data) === 3)
				$ret = date('Y-m-d', mktime(0,0,0,$data[1],$data[0],$data[2]));
		}
		return $ret;
	}
}
if(!function_exists('gera_select_option'))
{
	function gera_select_option($dados, $selecionado = '', $vazio = '')
	{
		$option = '';
		if(!empty($vazio))
		{
			$option = '<option value="">'.$vazio.'</option>'.PHP_EOL;
		}
		if(count($dados))
		{
			foreach ($dados as $value)
			{
				$option .= '<option value="'.$value->id.'"'.(($selecionado==$value->id) ? ' selected="selected"' : '' ).'>'.$value->nome.'</option>'.PHP_EOL;
			}
		}
		return $option;
	}
}
if ( ! function_exists('menu_ativo'))
{
	function menu_ativo($modulo = '')
	{
		$CI =& get_instance();
		return ($CI->router->class === $modulo);
	}
}
if( ! function_exists('pega_extensao_arquivo') )
{
	function pega_extensao_arquivo($arquivo = '')
	{
		return str_replace('.', '', strtolower(substr($arquivo,-4)));
	} 
}
if( ! function_exists('pega_id_youtube'))
{
	function pega_id_youtube($link)
	{
		$ret = false;
		parse_str( parse_url( $link, PHP_URL_QUERY ), $tmp );
		if (isset($tmp['v']) && ! empty($tmp['v']))
		{
			$ret = $tmp['v'];
		}
		return $ret;
	}
}
if( ! function_exists('tem_permissao'))
{
	function tem_permissao($classe, $metodo = NULL)
	{
		$ret = false;

		if (isset($_SESSION['permissoes']))
		{
			$metodo = ($metodo === 'index') ? '' : $metodo;
			$permissoes = $_SESSION['permissoes'];
			if (isset($metodo) && !empty($metodo))
			{
				$ret = isset($permissoes[$classe][$metodo]);
				if ( ! $ret && strstr($metodo, 'json'))
				{
					$CI =& get_instance();
					$ret = $CI->input->is_ajax_request();
				}
			}
			else
			{
				$ret = isset($permissoes[$classe]);
			}
		}
		return $ret;
	}
}
if( ! function_exists('user_logado'))
{
	function user_logado()
	{
		return (isset($_SESSION['nome'])) ? $_SESSION['nome'] : FALSE;
	}
}

if ( ! function_exists('envia_email'))
{
	function envia_email($para, $assunto, $msg, $anexo = null, $de = EMAIL_CONTATO)
	{
		$CI =& get_instance();
		$CI->load->library('email', array('mailtype' => 'html'));
		$CI->email->set_newline('\r\n');
		$CI->email->clear();
		$CI->email->from($de);
		$CI->email->to($para);
		$CI->email->subject($assunto);
		$CI->email->message($msg);
			
		if (isset($anexo) && ! empty($anexo))
			$CI->email->attach($anexo);
		return $CI->email->send();
	}
}
if ( ! function_exists('menu_link'))
{
	function menu_link($campo, $modulo = NULL, $acao = 'listar')
	{
		if ( ! isset($modulo))
			$modulo = strtolower($campo);
		return '<li><a href="'.site_url($modulo.'/'.$acao).'" class="'. (menu_ativo($modulo) ? 'active' : '') .'">'.$campo.'</a></li>';
	}
}
if ( ! function_exists('menu_monta'))
{
	function menu_monta()
	{
		$ret = '';
		if (isset($_SESSION['permissoes']))
		{
			$permicoes = $_SESSION['permissoes'];
			foreach ($permicoes AS $modulo => $nome)
			{
				$nome = current($nome);
				$ret .= menu_link($nome, $modulo);
			}
		}
		return ($ret !== '') ? $ret = '<ul>'.$ret.'</ul>' : '';
	}
}
if ( ! function_exists('unzip'))
{
	function unzip($arquivo, $nova_pasta, $caminho)
	{
		$ret = true;
		if ( ! empty($arquivo) && ! empty($nova_pasta) &&  ! empty($caminho))
		{
			$zip = new ZipArchive();
			$res = $zip->open($caminho.$arquivo);
			if ($res === TRUE) 
			{
				$dir = $caminho.$nova_pasta;
				if ( ! is_dir($dir)) 
				{
					@mkdir($dir, 0755, true) or die("Erro para criar a pasta $dir\n");
				}
				$zip->extractTo($dir);
				$zip->close();
				$ret = true;
			}
			else
			{
				$ret = false;
			}
		}
		
		return $ret;
	}
}
if ( ! function_exists('pega_arquivos'))
{
	function pega_imagens($caminho)
	{
		return glob($caminho."{*.jpg,*.JPG}", GLOB_BRACE);
	}
}
if(!function_exists('formata_data_mysql'))
{
	function formata_data_mysql($data, $set_hoje_default = TRUE)
	{
		$data = str_replace('_', '', $data);
		if ($set_hoje_default)
		{
			$ret = date('Y-m-d');
		}
		else
		{
			$ret = NULL;
		}
			
		if ( strlen($data) == 10)
		{
			$data = explode('/', $data);
			if (count($data) == 3)
			{
				$ret = implode('-', array_reverse($data));
			}
		}

		return $ret;
	}
}
if(!function_exists('formata_valor_dinheiro_mysql'))
{
	function formata_valor_dinheiro_mysql($valor)
	{
		$valor_final = ereg_replace("[^0-9]", "", $valor);
		$valor_final = number_format($valor_final/100,2,'.','');
		return $valor_final;
	}
}

if ( ! function_exists('formata_valor_dinheiro'))
{
	function formata_valor_dinheiro($valor, $set_zero_default = TRUE, $moeda = 'R$ ')
	{
		$ret = '';
		$valor = floatval($valor);
		$tmp = number_format($valor, 2, ',', '.');
		if ($set_zero_default || $valor > 0)
		{
			$ret = $moeda . $tmp;
		}
		return $ret;
	}
}