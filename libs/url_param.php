<?php
class UrlParam extends Object {

	// App::import('Lib', 'Shop.UrlParam');
	
	var $escape = array(
		'.2e.' => '.',
		//'.2d.' => '-',
		'.7c.' => '|',
		'.3d.' => '=',
		'.3a.' => ':',
		'.25.' => '%',
		'.3f.' => '?',
		'.23.' => '#',
		'|' => '/',//-2f-
	);
	var $serializedFlag = '.se.';
	var $humanFlag = '.x8.';
	
	
	//$_this =& UrlParam::getInstance();
	function &getInstance() {
		static $instance = array();
		if (!$instance) {
			$instance[0] =& new UrlParam();
		}
		return $instance[0];
	}
	
	function encode($url,$human=true) {
		$_this =& UrlParam::getInstance();
		$serialized = false;
		$encoded = $url;
		if($encoded === true){
			$encoded = array();
		}
		if($human){
			if(is_array($encoded)){
				$encoded['base'] = false;
				$encoded = Router::url($encoded);
			}
			$encoded = str_replace(array_values($_this->escape),array_keys($_this->escape),$encoded);
		}
		if(is_array($encoded)){
			$serialized = true;
			$encoded = serialize($encoded);
		}
		if($serialized){
			$encoded = $_this->serializedFlag.$encoded;
		}
		if(!$human){
			$encoded = base64_encode($encoded);
			$encoded = $_this->humanFlag.$encoded;
		}
		return $encoded;
	}
	function decode($url) {
		$_this =& UrlParam::getInstance();
		$serialized = false;
		$human = true;
		$decoded = $url;
		if(substr($decoded,0,strlen($_this->humanFlag)) == $_this->humanFlag){
			$human = false;
			$decoded = substr($decoded,strlen($_this->humanFlag));
			$decoded = base64_decode($decoded);
		}
		if(substr($decoded,0,strlen($_this->serializedFlag)) == $_this->serializedFlag){
			$serialized = true;
			$decoded = substr($decoded,strlen($_this->serializedFlag));
		}
		if($human){
			$escape = array_reverse($_this->escape,true);
			$decoded = str_replace(array_keys($escape),array_values($escape),$decoded);
		}
		if($serialized){
			$decoded = unserialize($decoded);
		}
		return $decoded;
	}
}
?>