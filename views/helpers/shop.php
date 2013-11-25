<?php
class ShopHelper extends AppHelper {

	var $helpers = array('Html','Number','Form','O2form.O2form');
	
	var $currencyFormats = array(
		'default'=> array('before'=>'$', 'thousands' => ',', 'decimals'=>'.', 'places'=>2),
		'fre' => array('before'=>false, 'after'=>' $', 'thousands' => ' ', 'decimals'=>',', 'places'=>2),
		'eng'=> array('before'=>'$', 'thousands' => ',', 'decimals'=>'.', 'places'=>2),
	);
	
	function beforeRender(){
		App::import('Lib', 'Shop.ShopConfig');
		$currencyFormats = ShopConfig::load('currencyFormats');
		foreach( $currencyFormats as $formatName => $options ){
			$this->addFormat($formatName, $options);
		}
		
		parent::beforeRender();
	}
	
	function addFormat( $formatName, $options ){
		$this->currencyFormats[$formatName] = $options;
		//$this->Number->addFormat($formatName, $options);
	}
	
	function editForm($model = null,$options = array()){
		App::import('Lib', 'Shop.ShopConfig');
		$config = ShopConfig::load();
		
		if(is_array($model)){
			$options = $model;
		}elseif(!empty($model)){
			$options['model'] = $model;
		}
		$defOpt = array(
			'model' => $this->model(),
			'fieldset' => true,
			'legend' => __('Shop',true),
		);
		$opt = array_merge($defOpt,$options);
		
		$types = ShopConfig::getSubProductTypes();
		if(empty($this->data['ShopSubproduct']) && !empty($this->data['ShopProduct']['ShopSubproduct'])){
			App::import('Lib', 'SetMulti');
			$this->data['ShopSubproduct'] = $this->data['ShopProduct']['ShopSubproduct'];
			$this->O2form->data['ShopSubproduct'] = $this->data['ShopSubproduct'];
			$this->Form->data['ShopSubproduct'] = $this->data['ShopSubproduct'];
		}
		$typeFields = array();
		if(!empty($types)){
			foreach($types as $key =>$type){
				$fields = set::normalize(array('id','code','label_fre','label_eng', 'operator','price'));
				if(count($type['operators']==1)){
					$fields['operator']['type'] = 'hidden';
					$fields['operator']['value'] = $type['operators'][0];
				}else{
					$fields['operator']['options'] = $type['operators'];
				}
				if(isset($type['adminFields'])){
					$fields = set::merge($fields,$type['adminFields']);
				}
				$typeFields['ShopSubproduct.'.$key] = array('type'=>'multiple','fields'=>$fields,'div'=>array('class'=>'type'));
			}
		}
		
		$view =& ClassRegistry::getObject('view');
		$html = $view->element('edit_form',array('plugin'=>'shop','opt'=>$opt,'typeFields'=>$typeFields,'currencies'=>$config['currencies']));
		
		return $html;
	}
	
	function countryInput($fieldName, $options = array() ){
		$options['type'] = 'country';
		App::import('Lib', 'Shop.ShopConfig');
		$countries = ShopConfig::load('countries');
		if(is_array($countries)){
			foreach ($countries as $key => $country) {
				if(is_array($country)){
					if(isset($country['label'])){
						$countries[$key] = $country['label'];
					}else{
						$countries[$key] = array();
					}
				}
			}
		}
		$def = ShopConfig::load('defaultCountry');
		if(!empty($def)){
			$options['default'] = $def;
		}
		$options['options'] = $countries;
		return $this->O2form->input($fieldName, $options);
	}
	
	function regionInput($fieldName, $options = array() ){
		$options['type'] = 'region';
		App::import('Lib', 'Shop.ShopConfig');
		$countries = ShopConfig::load('countries');
		$options['options'] = $countries;
		$def = ShopConfig::load('defaultRegion');
		if(!empty($def)){
			$options['default'] = $def;
		}
		return $this->O2form->input($fieldName, $options);
	}
	function shippingTypeInput($options = array()){
		return $this->supplementInput('shipping',$options);
	}
	function supplementInput($type,$options = array()){
		if(!isset($options['options'])){
			$options['options'] = array();
			$choices = ShopConfig::getSupplementOpts($type);
			foreach($choices as $name =>$opt){
				$options['options'][$name] = $opt['title'];
			}
		}
		if(!isset($options['label'])){
			$options['label'] = __(Inflector::humanize($type).' Type',true);
		}
		$exportedSupplements = ShopConfig::getExportedSupplements();
		if(in_array($type,$exportedSupplements)){
			$fieldName = $type.'_type';
		}else{
			$fieldName = 'ShopOrder.supplement_choices.'.$type;
		}
		return $this->O2form->input($fieldName, $options);
	}
	
	function currency($number,$currency=null){
		if(is_null($currency)){
			$currency = Configure::read('Shop.currency');
		}
		$lang = Configure::read('Config.language');
		$find = array();
		if(!empty($currency)){			$find[] = $currency.'-'.$lang;
			$find[] = $currency;
		}
		$find[] = $lang;
		$find[] = 'default';
		
		App::import('Lib', 'Shop.SetMulti');
		
		$format = SetMulti::extractHierarchic($find,$this->currencyFormats);
		return $this->Number->format($number,$format);
	}
	
	function productDispo($product=null,$options=array()){
		App::import('Lib', 'Shop.ShopConfig');
		$enabled = ShopConfig::load('enabled');
		if(!$enabled){
			return false;
		}
		if(is_array($product) && empty($product['ShopProduct']) &&  empty($product[0]['ShopProduct'])){
			$options = $product;
		}else{
			if(!empty($product)){
				$options['product'] = $product;
			}
		}
		$defOpt = array(
			'product'=>null,
			'sources'=> array('product','viewVars.product','viewVars','viewVars.'.Inflector::singularize($this->params['controller'])),
			'paths'=>array(
				'product' => array(
					'ShopProduct',
				),
				'price' => array(
					'ShopProduct.DynamicField.price','DynamicField.price','item_price',
				),
			),
			'dataOnly' => false,
		);
		$opt = array_merge($defOpt,$options);
		$view =& ClassRegistry::getObject('view');
		$source = array('product'=>$opt['product'], 'viewVars'=>$view->viewVars, 'params'=>$this->params);
		$extract_data = array();
		foreach ($opt['paths'] as $prop_name => $paths) {
			foreach ($opt['sources'] as $sname) {
				foreach ($paths as $path) {
					$extract_data[$prop_name][] = $sname.'.'.$path;
				}
			}
		}
		App::import('Lib', 'Shop.SetMulti');
		$data = SetMulti::extractHierarchicMulti($extract_data,$source,array('extractNull'=>false));
		return !empty($data['product']) && isset($data['price']);
	}
	
	function fullPrice($product=null,$options=array()){
		if(is_array($product) && empty($product['ShopProduct']) &&  empty($product[0]['ShopProduct'])){
			$options = $product;
		}else{
			if(!empty($product)){
				$options['product'] = $product;
			}
		}
		$defOpt = array(
			'product'=>null,
			'sources'=> array('product','viewVars.product','viewVars.'.Inflector::singularize($this->params['controller'])),
			'paths'=>array(
				'original_price' => array(
					'ShopProduct.DynamicField.original_price','DynamicField.original_price','item_original_price',
				),
				'rebate' => array(
					'ShopProduct.DynamicField.rebate','DynamicField.rebate','item_rebate',
				),
				'price' => array(
					'ShopProduct.DynamicField.price','DynamicField.price','item_price',
				),
			),
			'dataOnly' => false,
		);
		$opt = array_merge($defOpt,$options);
		$view =& ClassRegistry::getObject('view');
		$source = array('product'=>$opt['product'], 'viewVars'=>$view->viewVars, 'params'=>$this->params);
		$extract_data = array();
		foreach ($opt['paths'] as $prop_name => $paths) {
			foreach ($opt['sources'] as $sname) {
				foreach ($paths as $path) {
					$extract_data[$prop_name][] = $sname.'.'.$path;
				}
			}
		}
		App::import('Lib', 'Shop.SetMulti');
		$data = SetMulti::extractHierarchicMulti($extract_data,$source,array('extractNull'=>false));
		if($opt['dataOnly']){
			return $data;
		}else{
			if(!empty($data)){
				return $view->element('qualified_price',array('plugin'=>'shop','fullPrice'=>$data));
			}
		}
	}
	
	function analytics($order){
		if(!empty($order) && $order['ShopOrder']['status'] == 'ordered'){
			$gaAccount = null;
			App::import('Lib', 'Shop.ShopConfig');
			$gaAccountConf = ShopConfig::load('gaAccount');
			if(preg_match('/^(\w*)::(.*)$/', $gaAccountConf, $matches)){
				$prefix = $matches[1];
				$varName = $matches[2];
				if($prefix == 'var'){
					$view =& ClassRegistry::getObject('view');
					if(!empty($view->viewVars[$varName])){
						$gaAccount = $view->viewVars[$varName];
					}
				}elseif($prefix == 'conf'){
					$gaAccount = Configure::read($varName);
				}
			}else{
				$gaAccount = $gaAccountConf;
			}
			if(!empty($gaAccount)){
				$script = '
					var _gaq = _gaq || [];
					_gaq.push(["_setAccount", "'.$gaAccount.'"]);
					_gaq.push(["_addTrans",
					   "'.$order['ShopOrder']['id'].'",					// order ID - required
					   "'.$_SERVER['SERVER_NAME'].'",					// affiliation or store name
					   "'.$order['ShopOrder']['total'].'",				// total - required
					   "'.$order['ShopOrder']['total_taxes'].'",		// tax
					   "'.$order['ShopOrder']['total_shipping'].'",		// shipping
					   "'.$order['ShopOrder']['shipping_city'].'",		// city
					   "'.$order['ShopOrder']['shipping_region'].'",	// state or province
					   "'.$order['ShopOrder']['shipping_country'].'"	// country
					]);
				';
				foreach ($order['ShopOrdersItem'] as $item) {
					$script .= '
						_gaq.push(["_addItem",
						   "'.$order['ShopOrder']['id'].'",	// order ID - necessary to associate item with transaction
						   "'.$item['product_id'].'",		// SKU/code - required
						   "'.$item['item_title'].'",		// product name
						   "",								// category or variation
						   "'.$item['final_price'].'",		// unit price - required
						   "'.$item['nb'].'"				// quantity - required
						]);
					';
				}
				$script .= '_gaq.push(["_trackTrans"]);';
				$this->Html->scriptBlock($script,array('inline'=>false));
			}
		}
	}
	
}

?>