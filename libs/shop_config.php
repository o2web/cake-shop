<?php
class ShopConfig {
	/*
		App::import('Lib', 'Shop.ShopConfig');
		ShopConfig::load();
	*/
	
	var $loaded = false;
	var $defaultConfig = array(
		array(
			//--- Common ---//
			'currencies' => null,
			'enabled' => true,
			'defaultTaxes' => true,  //set to false to remove automatic taxes
			'countries' => true,	//list of available countries and regions. If true, all countries are available. Example to limit to Canada and Quebec : array('CA'=>array('regions'=>'QC')))
			'defaultCountry' => null,
			'defaultRegion' => null,
		),
		array(
			//--- Presentation config ---//
			'cart' => array(
				'inlineSubProduct' => true, //If true, sub products can be set within the cart
				'qtyInNbItem' => true,		//If true, the number of item in the cart will include the quantity of each product
			),
			'groupShippingBilling' => true,
		),
		array(
			//--- Feature activation ---//
			'promo' => array(
				'coupons' => false,
				'complexConditions' => false,
				'complexBehavior' => false,
			),
		),
		array(
			//--- Behavior config ---//
			'cart' => array(
				'clearOnCompleted' => true, //If true, the cart will be clean when e transaction is complete
				'defaultReturn' => null,
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
			'billingAddressRequired' => true,
			'defaultShippingRequired' => true,
			'emailBuyer' => true,
			),
		array(
			//--- Connections / data providers ---//
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
			'gaAccount' => 'var::googleAnaliticsCode',	//Google Analytics account name. "var::" and "conf::" are special prefixes allowing to retrieve the value from view variables or Configure::read
		),
		array(
			//--- Extensions ---//
			'plugComponent' => array(),
			
			//--- Dev ---//
			'devMode' => false,
			'dev' => array(),	//If devMode is true or a response is sent from sandbox.paypal, anysetting defined here will override other settings
		)
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
			$def = array();
			foreach($_this->defaultConfig as $group){
				$def = Set::merge($def,$group);
			}
			$config = Set::merge($def,$config);
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
	
	var $exportedSupplements = array('shipping');
	var $default_supplement_opt = array(
			'label'=>'',
			'title'=>'',
			'descr'=>'',
			'price'=>0,
			'calcul'=>null,
			'applicable'=>null,
			'tax_applied'=>false
		);
	var $specific_default_sup_opt = array(
			'shipping'=>array(
				'applicable'=>array('checkShippingReq'=>array())
			)
		);
	var $default_supplement_name = 'default';
	
	
	function getExportedSupplements(){
		$_this =& ShopConfig::getInstance();
		return $_this->exportedSupplements;
	}
	function getDefaultSupplementName(){
		$_this =& ShopConfig::getInstance();
		return $_this->default_supplement_name;
	}
	
	function getSupplementOpts($type=null,$name=null){
		$_this =& ShopConfig::getInstance();
		$exportedSupplements = $_this->exportedSupplements;
		$default_supplement_opt = $_this->default_supplement_opt;
		$specific_default_sup_opt = $_this->specific_default_sup_opt;
		$default_supplement_name = $_this->default_supplement_name;
		
		$supplements = (array)ShopConfig::load('supplements');
		
		foreach($exportedSupplements as $sName){
			$exportConf = ShopConfig::load($sName.'Types');
			if(!empty($exportConf)){
				$supplements[$sName] = $exportConf;
			}
		}
		$opts = array();
		foreach($supplements as $tName => $supplementTypes){
			if(is_null($type) || in_array($tName,(array)$type)){
				$tOpts = array();
				$specific_def = array();
				if(!empty($specific_default_sup_opt[$tName])){
					$specific_def = $specific_default_sup_opt[$tName];
				}
				if(empty($supplementTypes)){
					$supplementTypes[$default_supplement_name] = array();
				}
				foreach($supplementTypes as $sName => $setting){
					if(is_null($name) || in_array($sName,(array)$name)){
					
						if(!is_array($setting)){
							$setting = array('price'=>$setting);
						}
						$sOpts = Set::merge($default_supplement_opt,$specific_def,$setting);
						if(empty($sOpts['label']) && $sOpts['label'] !== false){
							$sOpts['label'] = Inflector::humanize($tName);
						}
						if(isset($sOpts['label'])){
							$sOpts['label'] = __($sOpts['label'],true);
						}
						if(empty($sOpts['title'])){
							if(!empty($sOpts['descr'])){
								$sOpts['title'] = $sOpts['descr'];
							}else{
								$sOpts['title'] = Inflector::humanize($sName);
							}
						}
						if(isset($sOpts['title'])){
							$sOpts['title'] = __($sOpts['title'],true);
						}
						if(empty($sOpts['descr']) && $sOpts['descr'] !== false && $sName != $default_supplement_name){
							$sOpts['descr'] = Inflector::humanize($sName);
						}
						if(isset($sOpts['descr'])){
							$sOpts['descr'] = __($sOpts['descr'],true);
						}
						
						if(!empty($sOpts['calculFunction'])){
							$sOpts['calcul'] = $sOpts['calculFunction'];
						}
						$sOpts['name'] = $sName;
						
						if(!is_null($name) && !is_array($name)){
							return $sOpts;
						}
						
						$tOpts[$sName] = $sOpts;
					}
				}
				
				if(!is_null($type) && !is_array($type)){
					return $tOpts;
				}
				
				$opts[$tName] = $tOpts;
			}
		}
		return $opts;
	}
	
}
?>