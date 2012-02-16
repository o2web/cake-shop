
<?php
class ShopConfig {
	/*
		App::import('Lib', 'Shop.ShopConfig');
		ShopConfig::load();
	*/
	
	var $loaded = false;
	
	//$_this =& ShopConfig::getInstance();
	function &getInstance() {
		static $instance = array();
		if (!$instance) {
			$instance[0] =& new ShopConfig();
		}
		return $instance[0];
	}
	
	function load($path = true){
		$_this =& ShopConfig::getInstance();
		if(!$_this->loaded){
			config('plugins/shop');
			$_this->loaded = true;
		}
		if(!empty($path)){
			return Configure::read('Shop'.($path!==true?'.'.$path:''));
		}
	}
	
	function getSubProductTypes(){
		$types = ShopConfig::load('SubProductTypes');
		$def = array(
			'operators'=>array('=','+','*','-','%','-%'),
		);
		foreach($types as $key => $type){
			$type = array_merge($def,(array)$type);
			if(empty($type['label'])){
				$type['label'] = __($key,true);
			}
			$type['operators'] = (array)$type['operators'];
			$types[$key] = $type;
		}
		return $types;
	}
	
}
?>

		