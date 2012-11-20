<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Imagens extends MY_Controller
{
	private $_titulo;
	public function __construct()
	{
		parent::__construct(FALSE);
		$this->_titulo = 'Imagens';
		$this->_validacao = array(
				array('field' => 'imagem', 	'label' => 'Imagem',	'rules' => 'trim|required|min_length[5]'),
				array('field' => 'x1', 		'label' => 'X1',		'rules' => 'trim|required'),
				array('field' => 'x2', 		'label' => 'X2',		'rules' => 'trim|required'),
				array('field' => 'y1', 		'label' => 'Y1',		'rules' => 'trim|required'),
				array('field' => 'y2', 		'label' => 'Y2',		'rules' => 'trim|required'),
				array('field' => 'largura',	'label' => 'Largura',	'rules' => 'trim|required'),
				array('field' => 'altura', 	'label' => 'Altura',	'rules' => 'trim|required'),
		);
	}
	public function recortar($imagem = '', $ok = '')
	{
		$tamanhos = array(
			'banners' 		=> array('w' => '800', 'h' => '600'),
		);
		$pasta 			= $this->input->get('p');
		$tipo 			= $this->input->get('t');
		$redirecionar 	= $this->input->get('r');
		
		$url 			= $this->_class.'/'.$this->_method.'/'.$imagem;
		$filtro 		= '?p='.$pasta.'&t='.$tipo.'&r='.$redirecionar;
		$caminho 		= realpath(APPPATH.'../'.$pasta.$imagem);
		if (is_file($caminho))
		{
			$data['imagem'] = $caminho;
			$data['imagem_url'] = site_url($pasta.$imagem);
		}
		
		$this->form_validation->set_rules($this->_validacao);
		if ($this->form_validation->run())
		{
			$ok = $this->_imagem_crop();
			redirect($url.'/'.$ok.'/'.$filtro);
		}
		else
		{
			$data['action'] = site_url($url.'/'.$filtro);
			$data['tamanho'] = isset($tamanhos[$tipo]) ? $tamanhos[$tipo] : $tamanhos['banners'];
			$data['botoes'] = '<a href="'.site_url($redirecionar).'" class="button">&laquo; Voltar</a>'.PHP_EOL;
			$data['validacao'] = $this->msg_validacao(htmlentities(urldecode($ok)));
			$this->ggt_layouts
				->set_include(VENDOR.'imgAreaSelect/jquery.imgareaselect.css')
				->set_include(VENDOR.'imgAreaSelect/jquery.imgareaselect.min.js')
				->set_include(JS.'imagem.js')
				->set_include('js/validacao_padrao.js')
				->view($this->_class.'/'.$this->_method, $data);
		}
	}
	
	private function _imagem_crop() 
	{
		$ret = 0;
		// Imagem original
		$imagem = $this->input->post('imagem');
		$extencao = pega_extensao_arquivo($imagem);
		// As coordenadas X e Y dentro da imagem original
		// recebidas pelo formulário
		$left   = $this->input->post('x1');
		$top    = $this->input->post('y1');
		$width  = $this->input->post('x2') - $left;
		$height = $this->input->post('y2') - $top;
		
		// Este será o tamanho final da imagem
		$crop_width  =  $this->input->post('largura');
		$crop_height =  $this->input->post('altura');
		
		if (! list($current_width, $current_height) = getimagesize($imagem))
			return "tipo de imagem invalido";
	
		if ($extencao == 'jpeg')
			$extencao = 'jpg';
		switch ($extencao) {
			case 'bmp' :
				$current_image = imagecreatefromwbmp($imagem);
				break;
			case 'gif' :
				$current_image = imagecreatefromgif($imagem);
				break;
			case 'jpg' :
				$current_image = imagecreatefromjpeg($imagem);
				break;
			case 'png' :
				$current_image = imagecreatefrompng($imagem);
				break;
			default :
				return "tipo de imagem invalido";
		}
		
		$new = imagecreatetruecolor($crop_width, $crop_height);
		
		// preserve transparency
		if ($extencao == "gif" or $extencao == "png") {
			imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
			imagealphablending($new, false);
			imagesavealpha($new, true);
		}
		
		imagecopyresampled ($new, $current_image, 0, 0, $left, $top, $crop_width, $crop_height, $width, $height);
		
		switch ($extencao) {
			case 'bmp' :
				imagewbmp($new, $imagem);
				break;
			case 'gif' :
				imagegif($new, $imagem);
				break;
			case 'jpg' :
				imagejpeg($new, $imagem);
				break;
			case 'png' :
				imagepng($new, $imagem);
				break;
		}
		imagedestroy($current_image);
		imagedestroy($new);
		
		return 'A imagem foi recortada corretamente';
	}
}