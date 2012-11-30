<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
			$ret = true;
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
if ( ! function_exists('link_menu'))
{
	function link_menu($campo, $modulo = NULL, $acao = 'listar')
	{
		if ( ! isset($modulo))
			$modulo = strtolower($campo);
		return '<li><a href="'.site_url($modulo.'/'.$acao).'" class="'. (menu_ativo($modulo) ? 'active' : '') .'">'.$campo.'</a></li>';
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