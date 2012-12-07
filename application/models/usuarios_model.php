<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Usuarios_Model extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->tabela = 'usuarios';
		$this->colunas = 'id, nome, login, ativo';
		$this->id = 'id';
	}
	public function get_login($login, $senha, $cripto = true)
	{
		if ($cripto) $senha = sha1($senha);
		$filtro = array(
			'login' => $login, 
			'senha' => $senha, 
			'ativo' => 'S'
		);
		$tmp = $this->get_itens($this->tabela, $this->colunas, $filtro);
		if (isset($tmp['itens'][0]))
		{
			$tmp = $tmp['itens'][0];
			$tmp->permissoes 	= $this->get_permicoes($tmp->id);
		}
		else
		{
			$tmp = FALSE;
		}
		return $tmp;
	}
	
	private function get_permicoes($user)
	{
		$ret = array(
			'usuarios' => array ('listar' => 'Usuários', 'adicionar' => 'Usuários', 'editar' => 'Usuários', 'remover' => 'Usuários', 'exportar' => 'Usuários'),
		);
		return $ret;
	}
}