<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
abstract class MY_Controller_CRUD extends MY_Controller 
{
	protected $_model;
	protected $_sort_by;
	protected $_sort_order;
	protected $_titulo;
	protected $_id;
	protected $_validacao = array();
	
	public function __construct()
	{
		parent::__construct();
	}
	abstract protected function _init_filtros($valores = array(), $url = '');
	abstract protected function _init_listagem($itens = array(), $url = '', $exportar = FALSE);
	protected function _get_parametros_extra() { return array(); }
	protected function _salva_adicionar() 
	{
	    $data = $this->_get_dados();
	    return $this->_model->adicionar($data);
	}
	protected function _salva_editar($id)
	{
	    $data = $this->_get_dados();
	    $this->_model->editar($data, array($this->_id => $id));
	    return $id;
	}
	protected function _salva_remover()
	{
	    $selecionados = $this->input->post('selecionados');
	    return $this->_model->excluir($this->_id.' in ('.implode(',',$selecionados).')');
	}
	public function index()
	{
		$this->listar();
	}
	public function adicionar()
	{
	    $c = $this->_class;
	    $f = $this->_method;
	    if (isset($this->_validacao['adicionar']))
	    {
	        $this->_validacao = $this->_validacao['adicionar'];
	    }
	    $this->form_validation->set_rules($this->_validacao);
	    if ($this->form_validation->run())
	    {
	        $id = $this->_salva_adicionar();
	        redirect($c.'/editar/'.$id.'/1');
	    }
	    else
	    {
	        $this->ggt_layouts
	            ->set_include(JS.'validacao_padrao.js')
	            ->set_navigation_bar($this->_titulo, $c.'/listar','0')
    	        ->set_navigation_bar('Adicionar', $c.'/'.$f,'1');
	        
	        $data = $this->_get_parametros_extra();
	        if ( ! isset($data['botoes']))
	        	$data['botoes'] = '<a href="'.site_url($c.'/listar').'" class="button">&laquo; Voltar</a>'.PHP_EOL;
	        $data['action'] = site_url($c.'/'.$f);
	        $data['validacao'] = $this->msg_validacao();
	        $data['campos'] = $this->_validacao;
	        $data['titulo'] = $this->_titulo;
	        $this->ggt_layouts->view('layouts/form', $data);
	    }
	}
	public function editar($id = NULL, $ok = FALSE)
	{
	    $c = $this->_class;
	    $f = $this->_method;
	    $dados = $this->_model->get_por_id($id);
	    if ($dados)
	    {
	        if (isset($this->_validacao['editar']))
	        {
	            $this->_validacao = $this->_validacao['editar'];
	        }
	        $this->form_validation->set_rules($this->_validacao);
	        if ($this->form_validation->run())
	        {
	            $id = $this->_salva_editar($id);
	            redirect($c.'/'.$f.'/'.$id.'/1');
	        }
	        else
	        {
	            $this->ggt_layouts
	                ->set_include(JS.'validacao_padrao.js')
	                ->set_navigation_bar($this->_titulo, $c.'/listar','0')
    	            ->set_navigation_bar('Editar', $c.'/'.$f.'/'.$id.'/','1');
	            
	            $salvo = ($ok) ? MSG_SALVO : '';
	            $data = $this->_get_parametros_extra();
	            if ( ! isset($data['dados'])) 
	            	$data['dados'] = $dados;
	            if ( ! isset($data['botoes']))
	            	$data['botoes'] = '<a href="'.site_url($c.'/listar').'" class="button">&laquo; Voltar</a>'.PHP_EOL;
	            $data['action'] = site_url($this->_class.'/'.$this->_method.'/'.$id);
	            $data['validacao'] = $this->msg_validacao($salvo);
	            $data['campos'] = $this->_validacao;
	        	$data['titulo'] = $this->_titulo;
	            $this->ggt_layouts->view('layouts/form', $data);
	        }
	    }
	    else
	    {
	        redirect($c.'/listar');
	    }
	}
	public function remover()
	{
	    $quantidade = $this->_salva_remover();
	    switch ($quantidade)
	    {
	        case 0:
	            print 'Nenhum item apagado.';
	        break;
	        case 1:
	            print 'Um item foi apagado.';
	        break;
	        default:
	            print $quantidade.' itens foram apagados.';
	        break;
	    }
	}
	public function listar($sort_by = 1, $sort_order = 'desc')
	{
	    $data = $this->_init_listar($sort_by, $sort_order);
	    $data['titulo'] = $this->_titulo;
	    $this->ggt_layouts
    	    ->set_include(CSS.'listar.css')
    	    ->set_include(JS.'listar.js')
    	    ->set_navigation_bar($this->_titulo, $this->_class.'/'.$this->_method,'1')
    	    ->view('layouts/listar', $data);
	}
	public function _init_listar($sort_by, $sort_order)
	{
		$this->_sort_by = $sort_by;
		$this->_sort_order = strtolower($sort_order);
		$offset = $this->input->get('per_page');
		$url = site_url($this->_class.'/'.$this->_method.'/'.$sort_by.'/'.$sort_order);
	
		$filtros = $this->_init_filtros($this->input->get('ggt_f'), $url);
		$parametros_url = $filtros->get_parametros_url();
	
		$lista = $this->_model->get_lista($filtros->get_parametros_ci_where(), $sort_by, $sort_order, $offset, N_ITENS_PAGINA);
		$data['paginacao'] = $this->_init_paginacao($lista['num_itens'], $url.'?'.$parametros_url);
	
		$url = site_url($this->_class.'/'.$this->_method.'/[sort_by]/[sort_order]').'?'.$parametros_url;
	
		$data['num_itens'] = ($offset+1) . ' - ' . (
				($offset+N_ITENS_PAGINA) > $lista['num_itens'] ? $lista['num_itens'] : $offset+N_ITENS_PAGINA
		) . ' de '.$lista['num_itens'];
			
		$data['listagem'] = $this->_init_listagem($lista['itens'], $url);
		$data['filtro'] = $filtros->get_formulario_html();
	
		$data['botao_direito'] = $this->_init_botao_direito();
		$data['acao_exportar'] 	= site_url($this->_class.'/exportar/');
		$data['acao_adicionar'] = site_url($this->_class.'/adicionar/');
		$data['acao_editar'] 	= site_url($this->_class.'/editar/');
		$data['acao_remover'] 	= site_url($this->_class.'/remover/');
	
		return $data;
	}
	protected function _init_botao_direito()
	{
		$acoes = array();
		if (tem_permissao($this->_class, 'exportar')) $acoes[] = array('class' => 'excel', 'click' => 'bt_direito.exportar()',	'descricao' => 'Exportar XLS',);
		if (tem_permissao($this->_class, 'editar'))   $acoes[] = array('class' => 'edit',  'click' => 'bt_direito.editar()',	'descricao' => 'Editar Iten(s) Selecionado(s)',);
		if (tem_permissao($this->_class, 'remover'))  $acoes[] = array('class' => 'delete','click' => 'bt_direito.deletar()', 	'descricao' => 'Apagar Iten(s) Selecionado(s)',);
		return $acoes;
	}
	public function exportar()
	{
		$this->load->helper('download');
		$filtros = $this->_init_filtros($this->input->get('ggt_f'), '');
		$lista = $this->_model->get_lista($filtros->get_parametros_ci_where());
		$data = $this->_init_listagem($lista['itens'], '', TRUE);
		$name = $this->_class.'-'.date('Y-m-d').'.xls';
		force_download($name, ($data));
	}
	private function _init_paginacao($total_itens, $url) 
	{
		$this->load->library('pagination');
		$config = array(
			'page_query_string' => TRUE,
			'base_url' 			=> $url,
			'total_rows' 		=> $total_itens,
			'per_page' 			=> N_ITENS_PAGINA,
			'uri_segment' 		=> 5,
			'first_link'		=> '&laquo;',
			'last_link'			=> '&raquo;',
			'cur_tag_open' 		=> '<div class="button ui-state-active">',
			'cur_tag_close'		=> '</div>',
		);
		$this->pagination->initialize($config);
		return $this->pagination->create_links();
	}
}