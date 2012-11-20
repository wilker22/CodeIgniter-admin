<?php 
class MY_Controller extends CI_Controller 
{
	protected $_class;
	protected $_method;
	public $user = NULL;
	public function __construct($verificar_login = TRUE, $eh_admin = TRUE)
	{
		parent::__construct();
		$this->load->library('GGT_Layouts');
		$this->_class = $this->router->class;
		$this->_method = $this->router->method;
		if ($eh_admin)
		{
			$this->load->library('GGT_Listagem');
			$this->load->library('GGT_Filtros');
			$this->load->library('GGT_Sessao');
			if ($verificar_login)
			{
				$this->ggt_sessao->verifica();
				$this->user = $_SESSION["usuario"];
			}
			$this->_carrega_itens_admin();
		}
		else 
		{
			$this->_carrega_itens_site();
		}
	}
	private function _carrega_itens_admin()
	{
		$this->ggt_layouts
			->set_include(CSS.'admin.css')
			->set_include(VENDOR.'jquery-ui-1.9.1.custom/jquery-ui-1.9.1.custom.min.css')
			->set_include(VENDOR.'jquery-msgbox/jquery.msgbox.css')
			->set_include(VENDOR.'contextMenu/jquery.contextMenu.css')
			->set_include(VENDOR.'clEditor/jquery.clEditor.css')
			->set_include(JS.'admin.js')
			->set_include(VENDOR.'jquery-msgbox/jquery.msgbox.min.js')
			->set_include(VENDOR.'jquery-maskmoney-1.3/jquery.maskMoney.1.3.js')
			->set_include(VENDOR.'jquery-maskedinput-1.3/jquery.maskedinput-1.3.min.js')
			->set_include(VENDOR.'jquery-ui-1.9.1.custom/jquery.ui.datepicker-pt-BR.js')
			->set_include(VENDOR.'jquery-ui-1.9.1.custom/jquery-ui-1.9.1.custom.min.js')
			->set_include(VENDOR.'contextMenu/jquery.contextMenu.js')
			->set_include(VENDOR.'clEditor/jquery.clEditor.min.js');
	}
	private function _carrega_itens_site()
	{
		$this->ggt_layouts
			->set_include(VENDOR.'jquery-fancyBox-2.1.3/jquery.fancybox.css')
			->set_include(VENDOR.'jquery-fancyBox-2.1.3/jquery.fancybox.pack.js')
			->set_include(VENDOR.'jquery-fancyBox-2.1.3/helpers/jquery.fancybox-thumbs.css')
			->set_include(VENDOR.'jquery-fancyBox-2.1.3/helpers/jquery.fancybox-thumbs.js')
			->set_include(CSS.'site.css')
			->set_include(JS.'site.js')
			
			->set_include('http://platform.tumblr.com/v1/share.js', false);
	}
	public function msg_validacao($salvo = NULL)
	{
		$ret = array();
		$ret['msg'] = $this->form_validation->error_string('<p><span class="ui-icon ui-icon-circle-minus">&nbsp;</span><span>', '</span></p>');
		$ret['class'] = "error";
		if (empty($ret['msg']))
		{
			if( ! empty($salvo))
			{
				$ret['class'] = "success";
				$ret['msg'] =  $salvo;
			}
			else
			{
				$ret['class'] = '';
				$ret['msg'] =  '';
			}
		}
		return $ret;
	}
	protected function _get_dados()
	{
		$data = $this->input->post_to_array();
		return $data;
	}
	public function envia_arquivo($str, $params)
	{
		$ret = TRUE;
		$nome = NULL;
		$tmp = explode(',', $params);
		$campo = isset($tmp[0]) ? $tmp[0] : $tmp;
		$config = array(
				'overwrite' => TRUE,
				'file_name' => mktime()
		);
		if (isset($tmp[1]))
			$config['upload_path'] = $tmp[1];
		if (isset($tmp[2]))
			$required = (!empty($tmp[2]));
		if (isset($tmp[3]))
		{
			//$config['allowed_types'] = str_replace(' ', '|', $tmp[3]);
			$config['allowed_types'] = '*';
			$allowed_types = explode(' ', $tmp[3]);
		}
		if ( ! empty($_FILES[$campo]['tmp_name']))
		{
			$extencao = str_replace('.', '', strtolower(substr($_FILES[$campo]['name'],-4)));
			if ( ! isset($allowed_types) || in_array($extencao, $allowed_types))
			{
				$this->load->library('upload', $config);
				if ($this->upload->do_upload($campo))
				{
					$upload_data = $this->upload->data();
					$nome = $upload_data['file_name'];
					$_POST[$campo] = $nome;
				}
				else
				{
					$msg = $this->upload->display_errors('', '');
					$this->form_validation->set_message('envia_arquivo', $msg);
					$ret = FALSE;
				}
			}
			else
			{
				$this->form_validation->set_message('envia_arquivo', 'O campo %s é de um tipo não válido ('.$extencao.'). Tipos aceitos: '.implode(',',$allowed_types));
				$ret = FALSE;
			}
		}
		else
		{
			if($required)
			{
				$this->form_validation->set_message('envia_arquivo', 'O campo %s é obrigatório.');
				$ret = FALSE;
			}
		}
		return $ret;
	}
}