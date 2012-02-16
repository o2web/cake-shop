<?php 
class OrderMakerComponent extends Object
{
	var $components = array();
	
	var $controller = null;
	
	function initialize(&$controller) {
		$this->controller =& $controller;
	}
	
	function beforeFilter(){
		
	}
}
?>