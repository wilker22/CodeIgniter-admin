<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Esta classe é uma biblioteca responsável verificar sessoes ativas.
* @author Gihovani Demétrio <gihovani@gmail.com>
* @version 0.1
* @copyright Copyright © 2012, Gihovani Demétrio
* @access public
* @package Libraries
* @subpackage GGT_Sessao
*/
class GGT_Sessao {
	// variaveis privadas
	// instancia do CodeIgniter 
	private $CI;
	private $tabela = 'sessoes';
	private $db;
	public function __construct() 
	{
		session_start();
		$_SESSION["id"] = session_id();
		$this->CI =& get_instance();
		$this->db = $this->CI->db;
		//$this->_cria_tabela();
	}
	private function _esta_valida() 
	{
		$tmp = $this->_buscar($_SESSION["id"]);
		if ($tmp)
		{
			$tmp = tem_permissao($this->CI->router->class, $this->CI->router->method);
		}
		return $tmp;
	}
	private function _cria_tabela() 
	{
		$sql = 'CREATE TABLE `'.$this->tabela.'` (
				  `id` char(32) NOT NULL,
				  `dth_inicio` datetime DEFAULT NULL,
				  `dth_termino` datetime DEFAULT NULL,
				  `url` tinytext,
				  `ip` varchar(15) DEFAULT NULL,
				  `user` int(11) NOT NULL,
				  PRIMARY KEY (`id`)
				)';
		$this->db->query($sql);
		return  $this->db->affected_rows();
	}
	private function _gravar($id, $id_user)
	{
		$data = array(
				'id' 			=> $id,
				'dth_inicio' 	=> date('Y-m-d H:i:s', mktime()),
				'dth_termino' 	=> date('Y-m-d H:i:s', mktime() + TEMPO_EXPIRAR),
				'url' 			=> $_SERVER["REQUEST_URI"],
				'ip' 			=> $_SERVER["REMOTE_ADDR"],
				'user' 			=> $id_user,
		);
		if ($this->_buscar($id, false))
		{
			$this->db->update($this->tabela, $data,  array('id' => $id));
		}
		else
		{
			$this->db->insert($this->tabela, $data);
		}
	}
	private function _apagar($id)
	{
		$this->db->delete($this->tabela, array('id' => $id));
	}
	private function _buscar($id, $valida = true)
	{
		$filtro = 'id = "'.$id. '"';
		if ($valida)
			$filtro .= ' AND dth_termino > NOW()';
			
		$tmp = $this->db
			->select('id, dth_inicio, dth_termino, url, ip, user')
			->where($filtro)
			->limit(1)
			->get($this->tabela)
			->result();
		if (isset($tmp[0]))
		{
			$tmp = $tmp[0];
		}
		else
		{
			$tmp = FALSE;
		}
		return $tmp;
	}
	public function verifica()
	{
		if (isset($_SESSION["usuario"])) 
		{
			if ($this->_esta_valida()) 
			{
				$this->registra();
			} 
			else 
			{
				$this->remove(3);
			}
		}
		else 
		{
			$this->remove(3);
		}
	}
	public function registra($user = false) 
	{
		if ($user)
		{
			$_SESSION["usuario"] 	= $user->id;
			$_SESSION["nome"] 		= $user->nome;
			$_SESSION["permissoes"]	= $user->permissoes;
		}
		$this->_gravar($_SESSION["id"], $_SESSION["usuario"]);
	}
	public function remove($id = 2) 
	{
		$this->_apagar($_SESSION["id"]);
		$_SESSION = array();
		session_destroy();
		redirect('admin/index/'.$id);
	}
}