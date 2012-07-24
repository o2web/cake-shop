<?php
class ShopConfig {
	/*
		App::import('Lib', 'Shop.ShopConfig');
		ShopConfig::load();
	*/
	
	var $loaded = false;
	var $defaultConfig = array(
		'cart' => array(
			'inlineSubProduct' => true, //If true, sub products can be set within the cart
			'clearOnCompleted' => true, //If true, the cart will be clean when e transaction is complete
			'qtyInNbItem' => true,		//If true, the number of item in the cart will include the quantity of each product
			'defaultReturn' => null,
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
		'promo'=> array(
			'codeLen'=>16,	//Length of promotions codes
			'max'=>null,	//Maximum number of promotions that can be applied to an order
		),
		
		'gaAccount' => 'var::googleAnaliticsCode',	//Google Analytics account name. "var::" and "conf::" are special prefixes allowing to retrieve the value from view variables or Configure::read
		
		'billingAddressRequired' => true,
		'defaultShippingRequired' => true,
		'groupShippingBilling' => true,
		'emailBuyer' => true,
		'plugComponent' => null,
		'currencies' => null,
		'enabled' => true,
		'defaultTaxes' => true,  //set to false to remove automatic taxes
		
		'countries' => true,	//list of available countries. If true, all countries are available.
		
		'defaultCountry' => null,
		'defaultRegion' => null,
		
		'devMode' => false,
		'dev' => array(),	//If devMode is true or a response is sent from sandbox.paypal, anysetting defined here will override other settings
	);
	
	//$_this =& ShopConfig::getInstance();
	function &getInstance() {
		static $instance = array();
		if (!$instance) {
			$instance[0] =& new ShopConfig();
		}
		return $instance[0];
	}
	
	function load($path = true, $devMode = null){
		$_this =& ShopConfig::getInstance();
		$config = null;
		if(!$_this->loaded){
			config('plugins/shop');
			$config = Configure::read('Shop');
			$config = Set::merge($_this->defaultConfig,$config);
			Configure::write('Shop',$config);
			$_this->loaded = true;
		}else{
			$config = Configure::read('Shop');
		}
		if(!empty($path)){
			if(is_null($devMode)){
				$devMode = $config['devMode'];
			}
			if($devMode){
				$config = Set::merge($config,$config['dev']);
				if(isset($config['dev']['emailAdmin']['to'])){
					$config['emailAdmin']['to'] = $config['dev']['emailAdmin']['to'];
				}
			}
			if($path===true){
				return $config;
			}else{
				return Set::extract($path, $config);
			}
		}
	}
	
	function getSubProductTypes(){
		$types = ShopConfig::load('SubProductTypes');
		if(empty($types)){
			return null;
		}
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
			}else{
				$type['label'] = __($type['label'],true);
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