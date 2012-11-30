<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Esta classe é uma biblioteca responsável por:
 * controlar os layouts
 * @author Gihovani Demétrio <gihovani@gmail.com>
 * @version 0.1
 * @copyright Copyright © 2012, Gihovani Demétrio
 * @access public
 * @package Libraries
 * @subpackage GGT_Layouts
 */
class GGT_Layouts {
	
	// variaveis privadas
	// instancia do CodeIgniter 
	private $CI;
	// titulo da pagina, NULL by default
	private $title_for_layout = '';
	// keywords da pagina, NULL by default
	private $keywords = array(NM_SISTEMA);
	// description da pagina, NULL by default
	private $description = '';
	// separador do titulo da pagina, | by default
	private $title_separator = ' - ';
	// includes javascript e css 
	private $file_includes = array();
	// barra navegacao
	private $navigation_bar_for_layout = array();
	  
	public function __construct() 
	{
		$this->title_for_layout = NM_SISTEMA;
		$this->description = 'Sistema ' . NM_SISTEMA; 
		$this->CI =& get_instance();
		$this->set_includes_defaults();
		$this->set_title(false);
	}
	
	private function set_includes_defaults() 
	{
		$this
			->set_include(CSS.'main.css')
			->set_include(VENDOR.'jquery-1.8.2.min.js')
			->set_include(JS.'plugins.js');
			
	}
	
	public function set_title($title) 
	{
		if( ! empty($title)) 
		{
			$this->title_for_layout .= $this->title_separator . $title;
		}
		return $this;
	}
	
	public function get_title() 
	{
		return $this->title_for_layout;
	}

	public function set_keywords($keywords) 
	{
		if (is_array($keywords))
		{
			$this->keywords = $keywords;
		}	
		else
		{
			$this->keywords[] = $keywords;
		}
		return $this;
	}
	
	public function get_keywords() 
	{
		if (count($this->keywords) > 0) 
		{
			$this->keywords = implode(', ', $this->keywords);
		}
		return $this->keywords;
	}
	
	public function set_description($description) 
	{
		$this->description  = $description;
		
		return $this;
	}
	
	public function get_description() 
	{
		return $this->description;
	}
	
	public function set_include($path, $prepend_base_url = TRUE)
	{
		if ($prepend_base_url)
		{
			$path = base_url().$path;
		}
		
		if (preg_match('/js$/', $path))
		{
			$this->file_includes['js'][$path] = $path;
		}		
		elseif (preg_match('/css$/', $path))
		{
			$this->file_includes['css'][$path] = $path;
		}
		return $this;
	}
	public function del_include($path, $prepend_base_url = TRUE)
	{
	    if ($prepend_base_url)
	    {
	        $path = base_url().$path;
	    }	    
	    if (preg_match('/js$/', $path))
	    {
	        unset($this->file_includes['js'][$path]);
	    }
	    elseif (preg_match('/css$/', $path))
	    {
	        unset($this->file_includes['css'][$path]);
	    }
	    return $this;
	}
	private function get_css()
	{
		$final_includes = '';
		if(!empty($this->file_includes['css']))
		{
			foreach ($this->file_includes['css'] as $include) 
			{
				$final_includes .= '<link rel="stylesheet" href="'.$include.NO_CACHE.'" type="text/css" />'.PHP_EOL;
			}
		}
		return $final_includes;
	}
	private function get_js()
	{
		$final_includes = '';
		if(!empty($this->file_includes['js']))
		{
			foreach ($this->file_includes['js'] as $include)
			{
				$final_includes .= '<script src="'.$include.NO_CACHE.'"></script>'.PHP_EOL;;
			}
		}
		return $final_includes;
	}
	public function set_navigation_bar($title, $url, $active = 0, $prepend_base_url = TRUE) 
	{
		if ($prepend_base_url)
		{
			$url = base_url().$url;
		}
		$this->navigation_bar_for_layout[] = (object) array('title'=>$title, 'url'=>$url, 'active'=>$active);
		return $this;
	}

	public function get_navigation_bar() 
	{
		return $this->navigation_bar_for_layout;
	}
	
	public function view($view_name, $params = array(), $layout = LAYOUT_DEFAULT)
	{
		// carrega o conteudo da view com os parametros passados
		$view_content = $this->CI->load->view($view_name, $params, TRUE);
		if ($this->CI->input->is_ajax_request())
		{
			print $view_content;
		}
		else
		{
			// agora carrega o conteudo do layout e com o conteudo da view
			$params = array(
				'ggt_content' => $view_content, 
				'ggt_navigation' => $this->get_navigation_bar(), 
				'ggt_css' => $this->get_css(), 
				'ggt_js' => $this->get_js(), 
				'ggt_title' => $this->get_title(),
				'ggt_keywords' => $this->get_keywords(),
				'ggt_description' => $this->get_description(),
			);
			$this->CI->load->view('layouts/' . $layout, $params);
		}
	}
}