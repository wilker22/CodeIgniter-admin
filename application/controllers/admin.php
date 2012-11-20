<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Admin extends MY_Controller {
	public function __construct() 
	{
		parent::__construct(FALSE);
	}
	public function index($id = 0)
	{
		$alert = '';
		$validacao = array(
			'login'	=> array('field' => 'login','label' => 'Login',	'rules' => 'trim|required|callback__checkar_login', 'tipo' => 'text', 		'extra_campo' => 'class="inputTxt required" maxlength="20" autocomplete="off"', 'extra_div' => 'class="coluna"'),
			'senha' => array('field' => 'senha','label' => 'Senha', 'rules' => 'trim|required', 						'tipo' => 'password', 	'extra_campo' => 'class="inputTxt required" maxlength="20" autocomplete="off"', 'extra_div' => 'class="coluna"'),
		);
		$this->form_validation->set_rules($validacao);
		if ($this->form_validation->run() || user_logado())
		{
			redirect('usuarios');
		}
		else
		{
			switch ($id) 
			{
				case 2:
					$alert = 'VOCÊ FOI DESCONECTADO DO SISTEMA';
				break;
				case 3:
					$alert = 'VOCÊ NÃO TEM AUTORIZAÇÃO PARA ACESSAR ESTA PÁGINA';
				break;
				default:
					$alert = '';
				break;
			}
		}
		$data['action'] = site_url($this->_class.'/'.$this->_method);
		$data['titulo'] = 'Login do Sistema';
		$data['campos'] = $validacao;
		$data['validacao'] = $this->msg_validacao($alert);
		$this->ggt_layouts
			->set_include(CSS.'login.css')
			->view('layouts/form', $data);
	}
	public function logout()
	{
		$this->ggt_sessao->remove();
	}
	
	public function _checkar_login()
	{
		$this->load->model('usuarios_model');
		$login = $this->input->post('login');
		$senha = $this->input->post('senha');
		$user = $this->usuarios_model->get_login($login, $senha);
		if (isset($user->id))
		{
		    $this->ggt_sessao->registra($user);
		    return TRUE;
		}
		else
		{
		    $this->form_validation->set_message('_checkar_login', 'NOME DO USUÁRIO OU SENHA INVÁLIDOS');
		    return FALSE;
		}
	}
}

/* End of file admin.php */
/* Location: ./application/controllers/admin.php */