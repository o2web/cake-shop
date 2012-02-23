<?php
class ShopConfig {
	/*
		App::import('Lib', 'Shop.ShopConfig');
		ShopConfig::load();
	*/
	
	var $loaded = false;
	var $defaultConfig = array(
		'cart' => array(
			'inlineSubProduct' => true,
		),
		'order' => array(
			'ACL' => array(
				'aroProvider' => 'session',
				'defaultAro' => 'users/guest',
				'deniedRedirect' => array('plugin' => 'auth', 'controller' => 'users', 'action' => 'permission_denied'),
			),
		),
		'address' => array(
			'ACL' => array(
				'aroProvider' => 'session',
				'defaultAro' => 'users/guest',
			),
		),
		'payment' => array(
			'ACL' => array(
				'aroProvider' => 'session',
				'defaultAro' => 'users/guest',
			),
			'enabled' => true,
			'available' => array('paypal'),
		),
		
		'billingAddressRequired' => true,
		'defaultShippingRequired' => true,
		'groupShippingBilling' => true,
		'emailBuyer' => true,
	);
	
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
			$config = Configure::read('Shop');
			$config = Set::merge($_this->defaultConfig,$config);
			Configure::write('Shop',$config);
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
			'min'=>0,
			'max'=>1,
			'minQtyEach'=>1,
			'maxQtyEach'=>1,
		);
		foreach($types as $key => $type){
			$type = array_merge($def,(array)$type);
			if(empty($type['label'])){
				$type['label'] = __($key,true);
			}
			if(empty($type['name'])){
				$type['name'] = $key;
			}
			$type['operators'] = (array)$type['operators'];
			$types[$key] = $type;
		}
		return $types;
	}
	
}
?>

		