<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Esta classe é uma biblioteca responsável por:
 * - gerar uma tabela com um formulário em html
 * - gerar os parametros sql para a consulta de acordo com o formulario
 * 
 * Podemos configurar os itens do formulário.
 * 
 * @author Gihovani Demétrio <gihovani@gmail.com>
 * @version 0.1
 * @copyright Copyright © 2012, Gihovani Demétrio
 * @access public
 * @package Libraries
 * @subpackage GGT_Filtros
 */

class GGT_Filtros
{
	private $itens, $valores, $url, $colunas,  $botoes;
	public function __construct($config = NULL)
	{
		if (isset($config))
			$this->initialize($config['itens'], $config['valores'], $config['url'], $config['colunas'], $config['botoes']);
	}
	public function get_formulario_html()
	{
		if ($this->qtd_itens)
		{
			$hidden = '';
			$formulario = '<input type="hidden" name="ggt_parametros_url" id="ggt_parametros_url" value="'.$this->get_parametros_url().'" />'.PHP_EOL;
			$formulario .= '<form name="ggt_filtros" method="get" action="'.$this->url.'">'.PHP_EOL;
			$formulario .= '<table cellpadding="0" cellspacing="0" width="100%" class="ggt_filtros">'.PHP_EOL;
			$formulario .= '<tbody>'.PHP_EOL;
			$formulario .= '<tr>';
			$qt_hidden = 0;
			for ($i = 0; $i < $this->qtd_itens; $i++)
			{
				if ($this->itens[$i]['tipo'] != 'select')
					$valor = $this->itens[$i]['valor'];
				else
					$valor = '';
					
				if ($this->itens[$i]['tipo'] != 'hidden')
				{
					$formulario .= PHP_EOL.'<td width="'.intval(100/$this->colunas).'%"'.(!empty($this->itens[$i]["colspan"]) ? ' colspan="'.$this->itens[$i]["colspan"].'"' : '').'>'.PHP_EOL;
					$formulario .= '<label for="ggt_f_'.$this->itens[$i]["nome"].'" title="'.$this->itens[$i]["descricao"].'">';
					$formulario .= $this->itens[$i]["descricao"];
					$formulario .= ':</label><br/>'.PHP_EOL;
					$formulario .= $this->_get_campo_item($this->itens[$i], (!empty($this->valores[$this->itens[$i]["nome"]]) ? $this->valores[$this->itens[$i]["nome"]] : $valor));
					$formulario .= '</td>';
					if ((($i + 1)-$qt_hidden) % $this->colunas == 0)
					{
						$formulario .= PHP_EOL.'</tr><tr>';
					}
				}
				else
				{
					$qt_hidden++;
					$hidden .= $this->_get_campo_item($this->itens[$i], (!empty($this->valores[$this->itens[$i]["nome"]]) ? $this->valores[$this->itens[$i]["nome"]] : $valor));
				}
			}
			$formulario .= '</tr>'.PHP_EOL;
			$formulario .= '</tbody>'.PHP_EOL;
			$formulario .= '<tfoot>'.PHP_EOL;
			$formulario .= '<tr>'.PHP_EOL;
			$formulario .= '<td align="center" colspan="'.$this->colunas.'">'.PHP_EOL;
			$formulario .= '<input type="submit" name="ggt_filtros_submit" value="Filtrar" class="submit" />'.PHP_EOL;
			$formulario .= '&nbsp;';
			$formulario .= '<input type="button" name="ggt_filtros_limpar" value="Limpar" onclick="window.location=\''.$this->url.'\'" class="button ui-button ui-widget ui-state-default ui-corner-all" />'.PHP_EOL;
			$formulario .= $this->botoes;
			$formulario .= '</td>'.PHP_EOL;
			$formulario .= '</tr>'.PHP_EOL;
			$formulario .= '</tfoot>'.PHP_EOL;
			$formulario .= '</table>'.PHP_EOL;
			$formulario .= $hidden;
			$formulario .= '</form>'.PHP_EOL;
			$formulario = str_replace('<tr></tr>', '', $formulario);
		} 
		else
		{
			$formulario = '';
		}
		return $formulario;
	}
	/**
	 * Função que retorna os filtro em sql 
	 * @access public
	 * @return String
	 */
	public function get_parametros_sql()
	{
		if ($this->qtd_itens)
		{
			foreach ($this->itens as $item)
			{
				$valor = ( ! empty($this->valores[$item['nome']]) ? $this->valores[$item['nome']] : '');
				if (gettype($valor) == 'array')
				{
					$valor = implode(',', $valor);
				}
				if ($valor) 
				{
					if ($item['tipo'] == 'select')
					{
						foreach ($item['valor'] as $opcoes)
						{
							if (($opcoes->id == $valor) && (!empty($opcoes->where)))
							{
								$parametro = str_replace('[valor]', $valor, $opcoes->where);
							}
						
						}
						if (empty($parametro))
							$parametro = str_replace('[valor]', $valor, $item['where']);
					} 
					elseif ($item['tipo'] == 'input')
					{
						if (empty($valor))
							$valor = '%';
						
						$parametro = str_replace('[valor]', $valor, $item['where']);
					}
					elseif ($item['tipo'] == 'hidden')
					{
						$parametro = $item['where'];
					}
					$parametros[] = $parametro;
				}
				unset($parametro);
			}
			$parametros = '('.(!empty($parametros) ? @implode(' AND ', $parametros) : '1').')';
		} 
		else
		{
			$parametros = '(1)';
		}
		return $parametros;
	}
	/**
	 * Função que retorna os filtro estilo get url
	 * @access public
	 * @return String
	 */
	public function get_parametros_url()
	{
		$parametros = '';
		if ($this->valores)
		{
			foreach ($this->valores as $id => $valor)
			{
				if (gettype($valor) == 'array')
				{
					$valor = implode(',', $valor);
				}
				$parametros .= '&ggt_f['.$id.']='.$valor;
			}
		}
		return $parametros;
	}
	/**
	 * Função que retorna os filtro estilo codeigniter
	 * @access public
	 * @return Array[]
	 */
	public function get_parametros_ci_where()
	{
		$parametros = array();
		if ($this->qtd_itens)
		{
			foreach ($this->itens as $item)
			{
				$valor = ( ! empty($item['ci_where']['valor']) ? $item['ci_where']['valor'] : $this->valores[$item['nome']]);
				if ($valor)
				{
					$item['ci_where']['valor'] = (is_string($valor) && strtolower($valor) == 'null') ? NULL : $valor;
					$parametros[] = $item['ci_where'];
				}
			}
		}
		return $parametros;
	}
	/**
	 * Função que retorna o campo do filtro
	 * @access public
	 * @param $item Array[]
	 * @param $selecionado String
	 * @return String
	 */
	private function _get_campo_item($item, $selecionado)
	{
		if (gettype($selecionado) == 'array')
		{
			$selecionado = implode(',', $selecionado);
		}
		$campo = '';
		if ($item["tipo"] == "select")
		{
			$campo .= '<select name="ggt_f['.$item['nome'].']" id="ggt_f_'.$item['nome'].'" '.$item['extra'].' '.(!empty($item['disabled']) ? 'disabled="disabled"' : '').'>'.PHP_EOL;
			$campo .= '<option value="">Selecione...</option>'.PHP_EOL;
			foreach ($item['valor'] as $opcao)
			{
				$campo .= '<option value="'.$opcao->id.'"'.(($opcao->id == $selecionado) ? ' selected' : '').' title="'.$opcao->nome.'">';
				$campo .= $opcao->nome;
				$campo .= '</option>'.PHP_EOL;
			}
			$campo .= '</select>'.PHP_EOL;
		} 
		elseif ($item['tipo'] == 'input')
		{
			$campo .= '<input type="text" name="ggt_f['.$item['nome'].']" id="ggt_f_'.$item['nome'].'" value="'.$selecionado.'" '.$item['extra'].' '.(!empty($item['disabled']) ? 'disabled="disabled"' : '').' />'.PHP_EOL;
		} 
		elseif ($item['tipo'] == 'hidden')
		{
			$campo .= '<input type="hidden" name="ggt_f['.$item['nome'].']" id="ggt_f_'.$item['nome'].'" value="'.$selecionado.'"/>'.PHP_EOL;
		}
		return $campo;
	}
	/**
	 * Função de inicializacao da biblioteca
	 * @access public
	 * @param itens Array[] 
	 * @param valores String[] 
	 * @param url String
	 * @param colunas int
	 * @param botoes String[]
	 * @return GGT_Filtros
	 */
	public function initialize($itens, $valores, $url, $colunas = 3, $botoes = '')
	{
		$this->itens = $itens;
		$this->valores = $valores;
		$this->url = $url;
		$this->colunas = !empty($colunas) ? $colunas : 3;
		$this->botoes = !empty($botoes) ? $botoes : '';
		$this->qtd_itens = sizeof($this->itens);
		
		return $this;
	}
}