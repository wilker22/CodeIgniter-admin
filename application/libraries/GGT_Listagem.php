<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class GGT_Listagem 
{
	private $selecionavel, 
			$cabecalhos,
			$caption,
			$itens,
		 	$larguras,
			$botoes,
			$num_colunas,
			$url,
			$ordenar_por,
			$ordenar_sentido = 'desc';
	public function __construct($config = NULL) 
	{
	    if (isset($config))
	        $this->initialize($config); 
	}
	public function get_html()
	{	
	    if ( ! empty($this->larguras))
	        $largura_total = array_sum($this->larguras);
	    else 
	        $largura_total = '100%';
	    $conteudo  = '<table width="' . $largura_total . '" class="ggt_listagem">'.PHP_EOL;
		$conteudo .= $this->_get_caption();
		$conteudo .= $this->_get_cabecalho();
		$conteudo .= $this->_get_itens();
		$conteudo .= '</table>'.PHP_EOL;
		return $conteudo;
	}
	public function get_xls()
	{
		$ret = 'nenhum item encontrado';
		if (isset($this->itens[0]))
		{
			if ( isset($this->cabecalhos))
			{
				$cabecalhos = array_keys($this->cabecalhos);
			}
			else
			{
				$cabecalhos = get_object_vars($this->itens[0]);
				$cabecalhos = array_keys($cabecalhos);
				$this->cabecalhos = $cabecalhos;
			}
			$delimitador = "\t";
			$ret = '"'.implode('"'.$delimitador.'"', $this->cabecalhos).'"'."\r\n";
			unset($this->cabecalhos);
			foreach ($this->itens as $key => $item)
			{
				$tmp = array();
				foreach ($cabecalhos as $chave)
				{
					$tmp[] = '"'.html_entity_decode(str_replace(array('"', "\n", "\r", "\t"), array("''", " ", " ", " "), strip_tags($item->$chave)), ENT_NOQUOTES, 'UTF-8').'"';
				}
				$ret .= implode($delimitador, $tmp)."\r\n";
				unset($this->itens[$key], $tmp);
			}
		}
		return utf8_decode($ret);
	}
    private function _get_caption() 
	{
		$conteudo = ( ! empty($this->caption)) ? '<caption>'.$this->caption.'</caption>'.PHP_EOL : '';
		return $conteudo;
	}
	private function _get_cabecalho() 
	{
	    $conteudo = '';
		if ( empty($this->cabecalhos) && ! empty($this->itens))
		    $this->cabecalhos = array_keys($this->itens[0]);
		if (is_array($this->cabecalhos))
		{
		    $this->num_colunas = sizeof($this->cabecalhos);
    	    $conteudo = '<thead><tr>'.PHP_EOL;
    	    $this->selecionavel['chave'] = isset($this->selecionavel['chave']) ? $this->selecionavel['chave'] : '';
    	    $this->selecionavel['display'] = isset($this->selecionavel['display']) ? $this->selecionavel['display'] : '';
    	    if (isset($this->cabecalhos[$this->selecionavel['chave']]))
    	    {
    	        $input = '<input type="checkbox" style="display:'.$this->selecionavel['display'].'" name="ggt_seleciona_todos" id="ggt_seleciona_todos" /> ';
    	    }
    	    else
    	    {
    	        $input = '';
    	    }
    	    $i = 1;
			foreach ($this->cabecalhos AS $chave => $cabecalho) 
    		{
    		    $conteudo .= $this->_set_html_cabecalho($cabecalho, $chave, $i, $input);
    			$input = '';
    			$i++;
    		}
    		if ( isset($this->botoes) && is_array($this->botoes))
    		{
    			$this->larguras['botoes'] = count($this->botoes)*27;
    			$conteudo .= $this->_set_html_cabecalho(' ', 'botoes');
    			$this->num_colunas++;
    		}
    		$conteudo .= '</tr></thead>'.PHP_EOL;
		}
		return $conteudo;
	}
	private function _get_itens() 
	{
		$conteudo = '<tbody>'.PHP_EOL;
		$chaves = array_keys($this->cabecalhos);
        $tamanho = sizeof($chaves);
		if (count($this->itens)) 
		{
		    foreach ($this->itens as $item)
			{
				$conteudo .= '<tr>';
				$id = isset($item->{$this->selecionavel['chave']}) ? $item->{$this->selecionavel['chave']} : '';
				if ( ! empty($id))
    	    	{
    	    	    $input = '<input type="checkbox" style="display:'.$this->selecionavel['display'].'" name="ggt_selecionado['.$id.']" id="ggt_selecionado_'.$id.'" value="'.$id.'" /> ';
				}
				else
				{
				    $input = '';
				}
				for ($i = 0; $i<$tamanho; $i++) 
				{
					$valor = isset($item->$chaves[$i]) ? $item->$chaves[$i] : '';
					$conteudo .= $this->_set_html_item($input.$valor);
					$input = '';
				}
				if ( isset($this->botoes) && is_array($this->botoes))
				{
					$conteudo .= $this->_set_html_item(str_replace('[id]', $id, implode(' ', $this->botoes)));
				}
				$conteudo .= '</tr>'.PHP_EOL;
			}
		} 
		else 
		{
		    $conteudo .= '<tr><td class="txt-center ggt-red" colspan="'.$this->num_colunas.'">Nenhum registro disponível.</td></tr>'.PHP_EOL;
		}
		$conteudo .= '</tbody>'.PHP_EOL;
		return $conteudo;
	}
	private function _set_html_cabecalho($campo, $chave = '', $posicao = 0, $input = '') 
	{
		$link = '';
		$img = '';
		$sentido = 'asc';
		if (isset($this->url) && ! empty($this->url))
		{
			if ($this->ordenar_por === $chave)
			{
				if ($this->ordenar_sentido === 'asc')
					$sentido = 'desc';
				$img = '<img src="'.base_url().'img/s_'.$this->ordenar_sentido.'.png" alt="ordenacao '.$this->ordenar_sentido.'" />';
			}
			$link = str_replace(array('[sort_by]', '[sort_order]'), array($posicao, $sentido), $this->url);
		}
		$largura = isset($this->larguras[$chave]) ? $this->larguras[$chave] : '';
		$conteudo = '<th'.(empty($largura) ? '' : ' width="'.$largura.'" ').' class="ui-widget-header">';
		$conteudo .= ( ! empty($link)) ? ' <a href="'.$link.'" title="'.$campo.'">' . $input . $campo . $img .'</a>' : $input . $campo;
		$conteudo .= '</th>'.PHP_EOL;
		return $conteudo; 
	}
	private function _set_html_item($campo) 
	{
		return '<td class="ui-widget-content">' . $campo . '</td>'.PHP_EOL;
	}
    public function initialize($config)
	{
		foreach ($config AS $c => $v)
        {
        	$this->$c = $v;
        }
		return $this;
	}
}