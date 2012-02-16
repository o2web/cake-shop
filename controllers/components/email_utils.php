<?php 
class EmailUtilsComponent extends Object
{
	var $components = array('Email');
	var $controller = null;
	
	function initialize(&$controller) {
		$this->controller =& $controller;
	}
	function init(&$controller) {
		$this->controller =& $controller;
	}
	
	function setConfig($conf){
		if(!empty($conf['to'])){
			$this->Email->to = $conf['to'];
		}
		if(!empty($conf['subject'])){
			$this->Email->subject = $conf['subject'];
		}
		if(!empty($conf['sender'])){
			$this->Email->from = $conf['sender'];
		}
		if(!empty($conf['replyTo'])){
			$this->Email->replyTo = $conf['replyTo'];
		}else{
			$this->Email->replyTo = $conf['sender'];
		}
		if(!empty($conf['template'])){
			$this->Email->template = $conf['template'];
		}
		if(!empty($conf['layout'])){
			$this->Email->layout = $conf['layout'];
		}
		if(!empty($conf['sendAs'])){
			$this->Email->sendAs = $conf['sendAs'];
		}
	}
	function set( $one, $two = NULL ){
		$this->controller->set($one, $two);
	}
	
	
	function defaultEmail($firstPart='info'){
		return $firstPart.'@'.$this->get_base_server_name();
	}
	function get_base_server_name(){
		$server_name = $_SERVER['SERVER_NAME'];
		if(substr_count($server_name,'.')>1){
			$server_name = preg_replace('!^[^.]*\\.!','', $server_name);
		}
		return $server_name;
	}
}
?>