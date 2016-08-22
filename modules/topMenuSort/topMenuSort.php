<?php
class topMenuSort extends Module
{
	public function __construct() 
	{ 
		$this->page 			= basename(__FILE__, '.php');
		$this->name 			= 'topMenuSort'; 
		$this->version 			= 1; 
		$this->author 			= 'www.cmacias.com'; 
		$this->need_instance 	= 0; 
		
		parent::__construct();
		
		$this->displayName 	= $this->l('Sorts the menu block'); 
		$this->description 	= $this->l('Add controls to sort the menu block.');
		$this->secure_key 	= Tools::encrypt($this->name);
	}
	
	public function install()
	{
		if(!parent::install() || !$this->registerHook('backOfficeHeader'))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
	public function hookBackOfficeHeader()
	{
			$html  = '<script type="text/javascript" src="'.$this->_path.'topMenuSort.js"></script>';
			$html .= '<link href="'.$this->_path.'topMenuSort.css" rel="stylesheet" type="text/css">';
			
			return $html;
	}
}