<?php
class PaymentComponent extends Object{
	var $settings = array();
	var $data = array();
	var $controller;
	function __construct() {
		$this->settings = Configure::read('Shop.payment.'.$this->name);
		//debug('Shop.payment.'.$this->name);
    }
	
	function initialize(&$controller) {
		$this->controller =& $controller;
	}
	
	function initData() {
		
	}
	
	function listItemPreprocess(){
		$this->set('listElement',array(
				'name' => 'payment/'.$this->name,
				'option' => array(
					'plugin' => 'shop'
				)
		));
	}
	
	function getData($mode){
		$this->reset();
		$this->initData();
		if(method_exists($this,$mode . 'Preprocess')){
			$this->{$mode.'Preprocess'}();
		}
		return $this->data;
	}
	
	function set($name,$data){
		$this->data[$name] = $data;
	}
	
	function reset(){
		$this->data = array();
	}
}
?>