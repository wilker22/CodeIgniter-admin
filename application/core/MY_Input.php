<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class MY_Input extends CI_Input
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function post_to_array($prefix = '', $post = NULL, $empty_values = TRUE, $xss_clean = FALSE)
	{
		$ret = array();
		if ( ! isset($post))
			$post = $_POST;
		foreach ($post as $chave => $value)
		{
			if (is_array($value) && count($value))
			{
				$ret[$prefix.$chave] = $this->post_to_array('', $value, $empty_values, $xss_clean);
			}
			else
			{
				if ($xss_clean === TRUE)
					$tmp = $this->security->xss_clean($value);
				else 
					$tmp = $value;
				
				
				if ($empty_values)
				{
					$ret[$prefix.$chave] = $tmp;
				}
				else
				{
					if ( ! empty($tmp))
						$ret[$prefix.$chave] = $tmp;
					else 
						$ret[$prefix.$chave] = NULL;
				}
			}
		}
		
		return $ret;
	}
}