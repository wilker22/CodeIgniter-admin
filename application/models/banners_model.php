<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Banners_Model extends MY_Model
{
	public $pasta = 'img/banners/';
	public function __construct()
	{
		parent::__construct();
		$this->tabela = 'banners';
		$this->colunas = 'id, imagem, descricao, ordem, ativo';
		$this->id = 'id';
	}
}