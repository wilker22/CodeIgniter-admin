<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class MY_Model extends CI_Model
{
	public $tabela, $colunas, $id;
	
	public function __construct()
	{
		parent::__construct();
	}
	public function get_lista($filtro = NULL, $sort_by = 1, $sort_order = 'asc', $offset = 0, $limit = NULL, $extra = NULL)
	{
		$ret = $this->get_itens($this->tabela, $this->colunas, $filtro, $sort_by, $sort_order, $offset, $limit, $extra);
		return $ret;
	}
	public function get_por_id($id)
	{
		$tmp = $this->get_itens($this->tabela, $this->colunas, array($this->id => $id));
		if ( isset($tmp['itens'][0]))
			$tmp = $tmp['itens'][0];
		else
			$tmp = FALSE;
	
		return $tmp;
	}
	/**
     * Função que adiciona registros ao BD
     * @access public
     * @param $data[] campos e valores
     * @return id inserido
     */
    public function adicionar($data=array())
	{
		$this->db->insert($this->tabela, $data); 
		return $this->db->insert_id();
	}
	/**
	 * Função que carrega alterações nos registros do BD
	 * @access public
	 * @param $data[] campos e valores a serem alterados
	 * @param $filtro[] identificador da tabela
	 * @return linhas afetadas
	 */
	public function editar($data = array(),$filtro = array())
	{
		$this->db->update($this->tabela, $data, $filtro);  
		return $this->db->affected_rows();
	}
	/**
	 * Função que exclui registros do BD
	 * @access public
	 * @param $filtro identificação dos registros excluidos
	 * @return linhas afetadas
	 */
	public function excluir($filtro)
	{
		$this->db->delete($this->tabela,$filtro);
		return $this->db->affected_rows();
	}
	/**
	 * Função que busca numa tabela a quantidade de itens da consulta
	 * @param $filtro string os valores a serem buscados
	 * @access public
	 * @return int $ret - quantidade 
	 */
	public function get_num_itens($table, $filter = NULL)
	{
		$ret = 0;
		$qry = $this->db->select('COUNT(*) as count', FALSE);
		if (is_array($table))
		{
			$qry->from($table[0]['nome']);
			unset($table[0]);
			foreach ($table as $tbl)
			{
				$qry->join($tbl['nome'], $tbl['where'], isset($tbl['tipo']) ? $tbl['tipo'] : 'inner');
			}
		}
		else
		{
			$qry->from($table);
		}
		
		if (isset($filter))
		{	
			$qry->where($filter);
		}
		$tmp = $qry->get()->result();
		if (isset($tmp[0]->count))
		{
			$ret = $tmp[0]->count;
		}
		return $ret;
	}
	/**
	 * Função que retorna a lista com base na busca do BD
	 * @param $table[] lista de tabelas 
	 * @param $column string com as colunas
	 * @param $filtro[] os valores a serem buscados
	 * @param $sort_by coluna de referencia para ordenação
	 * @param $sort_order criterio de ordem 'asc' ascendente e 'desc' decrescente'
	 * @param $offset posicao dos itens retornados
	 * @param $limit quantidade de itens retornados
	 * @param $extra algum parametro extra como having ou group by etc.
	 * @access public
	 * @return $ret[itens] - lista dos itens selecionados
	 * @return $ret[num_itens] - qtde dos itens selecionados
	 */
	public function get_itens($table, $column, $filter = NULL, $sort_by = NULL, $sort_order = NULL, $offset = NULL, $limit = NULL, $extra = NULL)
	{
		$qry_lines = $this->db->select('SQL_CALC_FOUND_ROWS ' . $column, FALSE);
		if (is_array($table))
		{
			$qry_lines->from($table[0]['nome']);
			unset($table[0]);
			foreach ($table as $tbl)
			{
				$qry_lines->join($tbl['nome'], $tbl['where'], isset($tbl['tipo']) ? $tbl['tipo'] : 'inner');
			}
		}
		else
		{
			$qry_lines->from($table);
		}
		if (isset($filter) && ! empty($filter))
		{
			if (is_array($filter) && isset($filter[0]))
			{
				foreach ($filter as $filter_tmp)
				{
					if (isset($filter_tmp['funcao_ci']))
						$qry_lines->{$filter_tmp['funcao_ci']}($filter_tmp['campo'], $filter_tmp['valor']);
					else
						$qry_lines->where($filter_tmp);
				}
			}
			else
			{
				$qry_lines->where($filter);
			}
		}
		if (!empty($sort_by))
		{
			$sort_order = (strtolower($sort_order) == 'desc') ? 'desc' : 'asc';
			$qry_lines->order_by($sort_by, $sort_order, FALSE);
		}
		if (!empty($limit) && isset($offset))
		{
			$qry_lines->limit($limit, $offset);
		}
		if (isset($extra['where'])) 
		{
			$qry_lines->where($extra['where']); 
		}
		if (isset($extra['group_by'])) 
		{
			$qry_lines->group_by($extra['group_by']); 
		}
		if (isset($extra['having'])) 
		{
			$qry_lines->having($extra['having']); 
		}
		$qry_lines = $qry_lines->get()->result();
		//print '<hr />'.$this->db->last_query().'<hr />';
		if (!isset($qry_lines[0]))
		{
			$ret['itens'] = array();
			$ret['num_itens'] = 0;
		}
		else
		{
			$ret['itens'] = $qry_lines;
			$qry_linhas = $this->db->query('SELECT FOUND_ROWS() as count')->result();
			$ret['num_itens'] = $qry_linhas[0]->count;
		}
		return $ret;
	}
}