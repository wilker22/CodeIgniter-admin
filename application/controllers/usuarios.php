<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH.'core/MY_Controller_CRUD.php');

class Usuarios extends MY_Controller_CRUD 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('usuarios_model');
		$this->_model = $this->usuarios_model;
		$this->_id     = 'id';
		$this->_titulo = 'Usuários';
		
		$this->_validacao = array(
			'nome'  => array('field' => 'nome',	'label' => 'Nome', 	'rules' => 'trim|required', 'tipo' => 'text',     'extra_campo' => 'class="inputTxt required" maxlength="60" autocomplete="off"', 'extra_div' => 'class="coluna"'),
			'login' => array('field' => 'login','label' => 'Login',	'rules' => 'trim|required', 'tipo' => 'text',     'extra_campo' => 'class="inputTxt required" maxlength="20" autocomplete="off"', 'extra_div' => 'class="coluna3"'),
			'senha' => array('field' => 'senha','label' => 'Senha',	'rules' => 'trim|required', 'tipo' => 'password', 'extra_campo' => 'class="inputTxt required" maxlength="20" autocomplete="off"', 'extra_div' => 'class="coluna3"'),
			'ativo' => array('field' => 'ativo','label' => 'Ativo',	'rules' => 'trim', 'tipo' => 'radio', 'itens' => array('S' => 'Sim', 'N' => 'Não'), 'extra_campo' => 'class="coluna2"', 'extra_div' => 'class="coluna3"'),
		);
	}
	protected function _init_filtros($valores = array(), $url = '')
	{
		//Campo Filtrar id
		$itens[] = array ('tipo' => 'input', 'nome' => 'id', 'descricao' => 'ID', 'valor' => '', 'extra' => 'class="ui-state-default ui-corner-all"', 'where' => 'id = [valor]', 'ci_where' => array('funcao_ci' => 'where', 'campo' => 'id', 'valor' => ''));
		//Campo Filtrar nome
		$itens[] = array ('tipo' => 'input', 'nome' => 'nome', 'descricao' => 'Nome', 'valor' => '', 'extra' => 'class="ui-state-default ui-corner-all"', 'where' => 'nome like "%[valor]%"', 'ci_where' => array('funcao_ci' => 'like', 'campo' => 'nome', 'valor' => ''));
		//Campo Filtrar login
		$itens[] = array ('tipo' => 'input', 'nome' => 'login', 'descricao' => 'Login', 'valor' => '', 'extra' => 'class="ui-state-default ui-corner-all"', 'where' => 'login like "%[valor]%"', 'ci_where' => array('funcao_ci' => 'like', 'campo' => 'login', 'valor' => ''));
		//Campo Filtrar ativo
		$itens[] = array ('tipo' => 'input', 'nome' => 'ativo', 'descricao' => 'Ativo <small>S ou N</small>', 'valor' => '', 'extra' => 'class="ui-state-default ui-corner-all"', 'where' => 'ativo like "[valor]"', 'ci_where' => array('funcao_ci' => 'where', 'campo' => 'ativo', 'valor' => ''));
		
		$botoes = '';
		$botoes .= '<input type="button" class="button" name="adicionar_'.$this->_class.'" id="adicionar_'.$this->_class.'" value="Adicionar Novo" onclick="bt_direito.adicionar()" />';
		$botoes .= ' <input type="button" class="button" name="exportar_xls" id="exportar_xls" value="Exportar Dados" onclick="bt_direito.exportar()" />';
		
		$filtros = $this->ggt_filtros->initialize($itens, $valores, $url, 4, $botoes);
		return $filtros;
	}
	protected function _init_listagem($itens = array(), $url = '', $exportar = FALSE) 
	{
		$config = array(
			'cabecalhos' => array(
				'id' 	=> 'ID',
				'nome'	=> 'Nome',
				'login'	=> 'Login',
				'ativo'	=> 'Ativo',
			),
			'itens' => $itens
		);
		if ( ! $exportar)
		{
			$config['selecionavel'] = array('chave' => 'id', 'display' => 'none');
			$config['url'] = $url;
			$config['sort_by'] = pega_chave_array($config['cabecalhos'], ($this->_sort_by-1));
			$config['sort_order'] = $this->_sort_order;
			$config['botoes'] = array(
					'<a href="javascript:bt_direito.editar([id])" title="Alterar Item ID: [id]"><img width="20" class="imgButton" alt="Editar" src="'.base_url().'img/edit.png"></a>',
					'<a href="javascript:bt_direito.deletar([id])" title="Remover Item ID: [id]"><img width="20" class="imgButton" alt="Editar" src="'.base_url().'img/delete.png"></a>'
			);
			return $this->ggt_listagem->initialize($config)->get_html();
		}
		else
		{
			$config['cabecalhos']['senha']	= 'Senha';
			return $this->ggt_listagem->initialize($config)->get_xls();
		}
	}
	protected function _get_dados()
	{
		$data = $this->input->post_to_array();
		$data['senha'] = sha1($data['senha']);
		return $data;
	}
}

/* End of file inventario.php */
/* Location: ./application/controllers/inventario.php */