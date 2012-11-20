<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH.'core/MY_Controller_CRUD.php');
class Banners extends MY_Controller_CRUD 
{
	private $_pasta;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('banners_model');
		$this->_model = $this->banners_model;
		$this->_id     = 'id';
		$this->_titulo = 'Banners';
		$this->_pasta = realpath(APPPATH.'../'.$this->_model->pasta).'/';
		$this->_validacao = array(
			'imagem' 	=> array('field' => 'imagem', 		'label' => 'Imagem', 	'rules' => 'trim|callback_envia_arquivo[imagem,'.$this->_pasta.'/,0,gif|jpg|jepg|png]', 'tipo' => 'file', 'extra_campo' => 'class="inputTxt"', 'extra_div' => 'class="coluna2"'),
			'ordem' 	=> array('field' => 'ordem', 		'label' => 'N. Ordem', 	'rules' => 'trim|required|integer', 'tipo' => 'number', 'extra_campo' => 'class="inputTxt required" maxlength="3" autocomplete="off"', 'extra_div' => 'class="coluna2"'),
			'descricao' => array('field' => 'descricao', 	'label' => 'Descrição',	'rules' => 'trim', 'tipo' => 'textarea', 'extra_campo' => 'class="textAreaTxt"', 'extra_div' => 'class="clearfix"'),
			'ativo' 	=> array('field' => 'ativo',		'label' => 'Ativo',		'rules' => 'trim', 'tipo' => 'radio', 'itens' => array('S' => 'Sim', 'N' => 'Não'), 'extra_campo' => 'class="coluna2"', 'extra_div' => 'class="clearfix"'),
		);
	}
	protected function _init_filtros($valores = array(), $url = '')
	{
		//Campo Filtrar id
		$itens[] = array ('tipo' => 'input', 'nome' => 'id', 'descricao' => 'ID', 'valor' => '', 'extra' => 'class="ui-state-default ui-corner-all"', 'where' => 'id = [valor]', 'ci_where' => array('funcao_ci' => 'where', 'campo' => 'id', 'valor' => ''));
		//Campo Filtrar nome
		$itens[] = array ('tipo' => 'input', 'nome' => 'nome', 'descricao' => 'Nome', 'valor' => '', 'extra' => 'class="ui-state-default ui-corner-all"', 'where' => 'nome like "%[valor]%"', 'ci_where' => array('funcao_ci' => 'like', 'campo' => 'nome', 'valor' => ''));
		//Campo Filtrar descricao
		$itens[] = array ('tipo' => 'input', 'nome' => 'descricao', 'descricao' => 'Descrição', 'valor' => '', 'extra' => 'class="ui-state-default ui-corner-all"', 'where' => 'descricao like "%[valor]%"', 'ci_where' => array('funcao_ci' => 'like', 'campo' => 'descricao', 'valor' => ''));
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
					'id' 		=> 'ID',
					'imagem'	=> 'Imagem',
					'descricao'	=> 'Descrição',
					'ordem'		=> 'Ordem',
					'ativo'		=> 'Ativo',
				),
				'itens' => $itens
		);
		if ( ! $exportar)
		{
			$i = 1;
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
			return $this->ggt_listagem->initialize($config)->get_xls();
		}
	}
	protected function _get_parametros_extra() 
	{
		$data = array();
		if ($this->_method == "editar")
		{
			$id = $this->uri->segment(3);
			$dados = $this->_model->get_por_id($id);
			$dados->imagem = '<a href=\''.site_url(str_replace(array('[pasta]','[imagem]','[class]','[method]','[id]'), array($this->_model->pasta, $dados->imagem, $this->_class, $this->_method, $id), IMG_RECORTAR)).'\'><img src=\''.site_url(str_replace(array('[pasta]', '[imagem]', '[largura]', '[altura]'), array($this->_model->pasta, $dados->imagem, '', 15), IMG_THUMB)).'&v='.mktime().'\' alt=\'recortar\' height=\'15\' class=\'recortar\' /></a>';
			$data['dados'] = $dados;
		}
		$data['enctype'] = 'multipart/form-data';
		
		$this->ggt_layouts->set_include(JS.'form_padrao.js');
		return $data;
	}
}
/* End of file inventario.php */
/* Location: ./application/controllers/inventario.php */