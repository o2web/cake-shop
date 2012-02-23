<?php 
class ShopFunctComponent extends Object
{
	var $components = array();
	var $controller = null;
	
	function initialize(&$controller) {
		$this->controller =& $controller;
	}
	function init(&$controller) {
		$this->controller =& $controller;
	}
	
	function callExternalfunction($component,$functName=null,$options=array()){
		$defaultOptions = array(
			'component'=>'',
			'functName'=>'',
			'params'=>array()
		);
		if(is_array($component) && $functName==null && empty($options)){
			$options = $component;
		}else{
			$options['component'] = $component;
			$options['functName'] = $functName;
		}
		if(!empty($options['function'])){
			$options['functName'] = $options['function'];
		}
		App::import('Lib', 'Shop.SetMulti');
		if(SetMulti::isAssoc($options)){
			$options = array_merge($defaultOptions,$options);
		}else{
			$options = array_merge($defaultOptions,$options);
		}
		$component =& $this->loadComponent($options['component']);
		
		$params = array();
		if(isset($options['params'])){
			if(!is_array($options['params'])){
				$options['params'] = array($options['params']);
			}
			$params = array_merge($params,$options['params']);
		}
		return call_user_func_array(array($component, $options['functName']),$params);
		
	}
	
	function loadComponent($componentName){
		$component = null;
		$pathing = $this->componentPathing($componentName);
		if(!isset($this->controller->{$pathing['name']})){
			if (!class_exists($pathing['className'])) {
				if (is_null($pathing['plugin']) || !App::import('Component', $pluginPt . $componentName)) {
					if (!App::import('Component', $componentName)) {
						$this->cakeError('missingComponentFile', array(array(
							'className' => $pathing['className'],
							'component' => $pathing['name'],
							'file' => Inflector::underscore($componentName) . '.php',
							'base' => $componentName,
							'code' => 500
						)));
						return false;
					}
				}
	
				if (!class_exists($pathing['className'])) {
					$this->cakeError('missingComponentClass', array(array(
						'className' => $pathing['className'],
						'component' => $pathing['name'],
						'file' => Inflector::underscore($componentName) . '.php',
						'base' => $componentName,
						'code' => 500
					)));
					return false;
				}
			}
			$component =& new $pathing['className']();
			//$component->startup($this->controller);
			if (isset($component->components) && is_array($component->components) ) {
				foreach($component->components as $cmp){
					$component->{$cmp} = $this->loadComponent($cmp);
				}
			}
			if (method_exists($component, 'initialize')/* && $component->enabled === true*/) {
				$component->initialize($this->controller);
			}
		}else{
			$component =& $this->controller->{$componentName};
		}
		return $component;
	}
	
	function componentPathing($componentName){
		$pathing = array(
			'componentName' => $componentName,
			'plugin' => null,
			'className' => null,
			'name' => null
		);
		$extract = explode('.', $componentName);
		if(count($extract)>1){
			$pathing['plugin'] = $extract[0];
		}
		$pathing['name'] = $extract[count($extract)-1];
		$pathing['className'] = $pathing['name'].'Component';
		return $pathing;
	}
	
	function taxeReady($order){
		$extract_data = array(
			'country' => array('country','ShopOrder.billing_country','ShopOrder.shipping_country'),
			'region' => array('region','ShopOrder.billing_region','ShopOrder.shipping_region'),
		);
		App::import('Lib', 'Shop.SetMulti');
		if(isset($order['order'])){
			$order['ShopOrder'] = $order['order'];
		}
		$data = SetMulti::extractHierarchicMulti($extract_data,$order,array('extractNull'=>false));
		return !empty($data['country']) && !empty($data['region']);
	}
	
	function calculSubItem($products){
		App::import('Lib', 'Shop.SetMulti');
		if(SetMulti::isAssoc($products)){
			$prods =  array(&$products);
		}else{
			$prods = &$products;
		}
		foreach($prods as &$prod){
			$orderItemMode = isset($prod['item_price']);
			if($orderItemMode){
				$p = &$prod;
			}else{
				$p = $this->extractOrderItemData($p2 = $prod);
			}
			$cur_price = $p['item_price'];
			$p['item_alone_price'] = $cur_price;
			$subItems = $this->extractSubItemData($prod);
			if(!empty($subItems)) { 
				//============ calculate ============//
				App::import('Lib', 'Shop.Operations');
				foreach($subItems as &$subItem){
					$new_price = $cur_price;
					$subPrice = $subItem['item_price'] * $subItem['nb'];
					if($subItem['item_operator'] == '='){
						$new_price = $subPrice;
					}else{
						$new_price = Operations::simpleOperation($cur_price,$subItem['item_operator'],$subPrice);
					}
					$subItem['modif'] = $new_price-$cur_price;
					$cur_price = $new_price;
				}
				$p['SubItem'] = $subItems;
			}
			$p['subitems_modif'] = $cur_price - $p['item_price'];
			$p['item_price'] = $cur_price;
			
			if(!$orderItemMode){
				if(isset($prod['ShopProduct']['DynamicField'])){
					$dprod = &$prod['ShopProduct'];
				}else{
					$dprod = &$prod;
				}
				$dprod['DynamicField']['subitems_modif'] = $p['subitems_modif'];
				$dprod['DynamicField']['alone_price'] = $p['item_alone_price'];
				$dprod['DynamicField']['price'] = $p['item_price'];
			}
		}
		return $products;
	}

	function calculPromo($products){
		App::import('Lib', 'Shop.SetMulti');
		if(SetMulti::isAssoc($products)){
			$prods =  array(&$products);
		}else{
			$prods = &$products;
		}
		$this->ShopPromotion = ClassRegistry::init('Shop.ShopPromotion'); 
		foreach($prods as &$prod){
			$orderItemMode = isset($prod['item_price']);
			if($orderItemMode){
				$p = &$prod;
			}else{
				$p = $this->extractOrderItemData($p2 = $prod);
			}
			if(empty($p['item_original_price'])){
				$price = $p['item_price'];
				//debug($p);
				if(!empty($p['ShopPromotion'])){
					foreach($p['ShopPromotion'] as &$promo){
						$newPrice = $this->ShopPromotion->applyOperator($price,$promo['operator'],$promo['val']);
						if(!empty($promo['ShopAction'])){
							$action = $this->ShopPromotion->ShopAction->toCallBack($promo['ShopAction'],array($p,$newPrice,$promo['action_params']));
							$res = $this->callExternalfunction($action);
							if($res === true){
							}elseif($res === false){
								$newPrice = $price;
							}elseif(is_numeric($res)){
								$newPrice = $res;
							}else{
								$newPrice = $price;
							}
						}
						$promo['rebate'] = $price - $newPrice;
						$price = $newPrice;
					}
				}
				$p['item_rebate'] = $p['item_price'] - $price;
				$p['item_original_price'] = $p['item_price'];
				$p['item_price'] = $price;
				if(!$orderItemMode){
					if(isset($prod['ShopProduct']['DynamicField'])){
						$dprod = &$prod['ShopProduct'];
					}else{
						$dprod = &$prod;
					}
					$dprod['DynamicField']['rebate'] = $p['item_rebate'];
					$dprod['DynamicField']['original_price'] = $p['item_original_price'];
					$dprod['DynamicField']['price'] = $p['item_price'];
				}
			}
			unset($p);
		}
		
		return $products;
	}
	
	function calculate($order){
		//============ format data ============//
		$result = array();
		$default = array(
			'country' => Configure::read('Shop.defaultCountry'),
			'region' => Configure::read('Shop.defaultRegion')
		);
		$extract_data = array(
			'country' => array('country','ShopOrder.billing_country','ShopOrder.shipping_country'),
			'region' => array('region','ShopOrder.billing_region','ShopOrder.shipping_region'),
			'shipping_type' => array('shipping_type','ShopOrder.shipping_type')
		);
		if(isset($order['order'])){
			$order['ShopOrder'] = $order['order'];
		}
		App::import('Lib', 'Shop.SetMulti');
		$data = SetMulti::extractHierarchicMulti($extract_data,$order,array('extractNull'=>false));
    	
		
		$data = array_merge($default,$data);
		//debug($data);
		
		if(isset($order['ShopOrdersItem'])){
			$rawItems = $order['ShopOrdersItem'];
		}else if($order['items']){
			$rawItems = $order['items'];
		}
		
		if(empty($rawItems)){
			return false;
		}
		
		foreach($rawItems as $orderItem){
			$orderItems[] = $this->extractOrderItemData($orderItem);
		}
		//============ calcul subItems ============//
		$orderItems = $this->calculSubItem($orderItems);
		//debug($orderItems);
		
		
		//============ calcul promos ============//
		$orderItems = $this->calculPromo($orderItems);
		//debug($orderItems);
		
		//============ total_items ============//
		$result['total_items'] = 0;
		$result['nb_total'] = 0;
		if(!empty($orderItems)){
			foreach($orderItems as &$orderItem){
				$result['nb_total'] += $orderItem['nb'];
				$orderItem['total'] = $orderItem['item_price']*$orderItem['nb'];
				$result['total_items'] += $orderItem['total'];
			}
		}
		
		//============ sub_total ============//
		$result['sub_total'] = 0;
		$result['sub_total'] = $result['total_items'];
		
		//============ Supplements (Shipping, Packing, etc) ============//
		$exportedSupplements = array('shipping');
		$result['total_supplements'] = 0;
		$supplements = (array)Configure::read('Shop.supplements');
		$default_supplement_opt = array(
			'descr'=>'',
			'price'=>0,
			'calculFunction'=>null,
			'tax_applied'=>false
		);
		$defaultType = 'default';
		
		$supplementsData = array();
		if(!empty($data['supplement_choices'])){
			$supplementsData = (array)$data['supplement_choices'];
		}
		
		foreach($exportedSupplements as $name){
			$exportConf = Configure::read('Shop.'.$name.'Types');
			if(!empty($exportConf)){
				$supplements[$name] = $exportConf;
			}
			if(!empty($data[$name.'_type'])){
				$supplementsData[$name] = $data[$name.'_type'];
			}
		}
		
		
		
		
		foreach($supplements as $sName => $supplement){
			$supplement_choice = array('type'=>$defaultType);
			if(!empty($supplementsData[$sName]) ){
				if(!is_array($supplementsData[$sName])){
					$supplementsData[$sName] = array('type'=>$supplementsData[$sName]);
				}
				if(empty($supplementsData[$sName]['type'])){
					$supplementsData[$sName]['type'] = $defaultType;
				}
				if(isset($supplements[$sName][$supplementsData[$sName]['type']])){
					$supplement_choice = $supplementsData[$sName];
				}
			}
			
			$supplementItem = $supplement[$supplement_choice['type']];
			if(!is_array($supplementItem)){
				$supplementItem = array('price'=>$supplementItem);
			}
			$supplementItem = array_merge($default_supplement_opt,$supplementItem);
			
			if(!isset($supplementItem['label']) || (empty($supplementItem['label']) && $supplementItem['label'] !== false)){
				$supplementItem['label'] = Inflector::humanize($sName);
			}
			if(isset($supplementItem['label'])){
				$supplementItem['label'] = __($supplementItem['label'],true);
			}
			
			if(empty($supplementItem['descr']) && $supplementItem['descr'] !== false && $supplement_choice['type'] != $defaultType){
				$supplementItem['descr'] = Inflector::humanize($supplement_choice['type']);
			}
			if(isset($supplementItem['descr'])){
				$supplementItem['descr'] = __($supplementItem['descr'],true);
			}
			
			if(!empty($supplementItem['calculFunction'])){
				if($supplementItem['calculFunction'] == 'multiplyByNb'){
					$supplementItem['total'] = $supplementItem['price'] * $result['nb_total'];
				}else{
					$supplementItem['calculFunction']['params'][] = $supplementItem;
					$supplementItem['calculFunction']['params'][] = $order;
					$supplementItem['calculFunction']['params'][] = $supplement_choice;
					$res = $this->callExternalfunction($supplementItem['calculFunction']);
					if(!is_array($res)){
						$res = array('total' => $res);
					}
					if(empty($res['total'])){
						$res['total'] = 0;
					}
					$supplementItem = array_merge($supplementItem,$res);
					unset($supplementItem['calculFunction']);
				}
			}else{
				$supplementItem['total'] = $supplementItem['price'];
			}
			$result['supplements'][$sName] = $supplementItem;
			$result['total_supplements'] += $supplementItem['total'];
		}
		
		foreach($exportedSupplements as $name){
			if(isset($result['supplements'][$name]['total'])){
				$result['total_'.$name] = $result['supplements'][$name]['total'];
			}
		}
		
		//============ calcul taxes ============//
		$result['total_taxes'] = 0;
		$this->ShopTax = ClassRegistry::init('Shop.ShopTax');
		$taxes = $this->ShopTax->find('all',array('conditions'=>array('active' => 1,'country'=>$data['country'],'OR'=>array('region'=>$data['region'],'region IS NULL')),'order'=>array('region'=>'ASC','code'=>'ASC')));
		if(!empty($taxes)){
			/*$general_taxes = array();
			foreach($taxes as $taxe){
				$general_taxes[$taxe['ShopTax']['code']] = $taxe['ShopTax'];
			}
			
			$tmpTotal = $result['sub_total'];
			foreach($general_taxes as $taxe){
				if($taxe['apply']){
					if($taxe['apply_prev']){
						$amount = $tmpTotal * $taxe['rate'];
					}else{
						$amount = $result['sub_total'] * $taxe['rate'];
					}
					$result['taxes'][$taxe['code']] = $amount;
					$tmpTotal += $amount;
					$result['total_taxes'] += $amount;
				}
			}*/
			$tcheckableItems = array_merge($orderItems,array_values($result['supplements']));
			foreach($tcheckableItems as $item){
				$item['tmpTotal'] = $item['total'];
				if(isset($item['item_tax_applied'])){
					$item['tax_applied'] = $item['item_tax_applied'];
				}
				if(!empty($item['tax_applied'])){
					foreach($taxes as $taxe){
						$taxe = $taxe['ShopTax'];
						$apply = $taxe['apply'];
						if($item['tax_applied'] === false){
							$apply = false;
						}elseif(is_array($item['tax_applied'])){
							$apply = in_array($taxe['code'],$item['tax_applied']);
						}
						if($apply){
							if($taxe['apply_prev']){
								$apply_to = $item['tmpTotal'];
							}else{
								$apply_to = $item['total'];
							} 
							if(!isset($result['taxes'][$taxe['code']]) || !isset($result['taxe_subs'][$taxe['code']])){
								$result['taxe_subs'][$taxe['code']] = 0;
								$result['taxes'][$taxe['code']] = 0;
							}
							$result['taxe_subs'][$taxe['code']] += $apply_to;
							$amount = $apply_to * $taxe['rate'];
							$result['taxes'][$taxe['code']] += $amount;
							$item['tmpTotal'] += $amount;
							$result['total_taxes'] += $amount;
						}
					}
				}
			}
		}
		
		
		//============ total ============//
		$result['total'] = 0;
		$result['total'] += $result['sub_total'];
		$result['total'] += $result['total_supplements'];
		$result['total'] += $result['total_taxes'];
		$result['total'] = round($result['total'], 2);
		
		//debug($result);
		return $result;
	}
	
	function formatProductAddOption($productOpt){
		$defProductOpt = array(
			'id' => null,
			'model' => null,
			'foreign_id' => null,
			'nb' => 1,
			'comment' => null,
			'data' => array()
		);
		if(!is_array($productOpt)){
			$productOpt = array('key'=>$productOpt);
		}
		if(!empty($productOpt['key'])){
			if(is_numeric($productOpt['key'])){
				$productOpt['id'] = $productOpt['key'];
			}elseif(strpos($productOpt['key'],'.')!==false){
				list($productOpt['model'],$productOpt['foreign_id']) = explode('.',$productOpt['key'],2);
			}
		}
		$productOpt = array_merge($defProductOpt,$productOpt);
		return $productOpt;
	}
	function productFindConditions($productOpt,$options = array()){
		$productOpt = $this->formatProductAddOption($productOpt);
		
		$defaultOptions = array(
			'tcheckActive' => true,
			'defaultCondition' => false
		);
		$options = array_merge($defaultOptions,$options);
		
		$conditions = array();
		if($options['tcheckActive']){
			$conditions['active'] = 1;
		}
		if(!empty($productOpt['id'])){
			$conditions['id'] = $productOpt['id'];
		}elseif(!empty($productOpt['model']) && !empty($productOpt['foreign_id'])){
			$conditions['model'] = $productOpt['model'];
			$conditions['foreign_id'] = $productOpt['foreign_id'];
		}else{
			$conditions = $options['defaultCondition'];
		}
		return $conditions;
	}
	
	function extractSubItemData($productAndOptions){
		if(!empty($productAndOptions['ShopOrdersSubitem'])){
			return $productAndOptions['ShopOrdersSubitem'];
		}
		
		$subItemDef = array(
			'nb' => 1,
		);
		
		$orderItemMode = isset($productAndOptions['item_price']);
		if($orderItemMode){
			$p = &$productAndOptions;
		}else{
			$p = $this->extractOrderItemData($p2 = $productAndOptions);
		}
		$cur_price = $p['item_price'];
		$p['item_alone_price'] = $cur_price;
		App::import('Lib', 'Shop.ShopConfig');
		$types = ShopConfig::getSubProductTypes();
		if(!empty($types) && !empty($p['SubItem'])) { 
			$this->ShopSubproduct = ClassRegistry::init('Shop.ShopSubproduct');
			$ids = array();
			//============ normalize data ============//
			$subItems = array();
			foreach($types as $type){
				$n = $type['name'];
				if(isset($p['SubItem'][$n])){
					$subItems[$n] = $p['SubItem'][$n];
					if(!is_array($subItems[$n]) || SetMulti::isAssoc($subItems[$n])){
						$subItems[$n] = array($subItems[$n]);
					}
					foreach($subItems[$n] as &$subItem){
						if(!is_array($subItem)){
							$subItem = array('id'=>$subItem);
						}
						$subItem = array_merge($subItemDef,$subItem);
					}
				}
				$ids = array_merge($ids, SetMulti::extractKeepKey('id',$subItems[$n]));
			}
			//debug($ids);
			
			//============ fetch SubProducts ============//
			$this->ShopSubproduct->ShopProductSubproduct->Behaviors->attach('Containable');
			$this->ShopSubproduct->ShopProductSubproduct->contain(array('ShopSubproduct'));
			$subProduct = $this->ShopSubproduct->ShopProductSubproduct->find('all', array(
				'conditions' => array(
						'ShopProductSubproduct.shop_product_id' => $p['product_id'],
						'ShopProductSubproduct.shop_SUBproduct_id' => $ids,
					)
			));
			$subProduct = SetMulti::group($subProduct,'ShopSubproduct.id',array('singleArray'=>false));
			//debug($subProduct);
			
			//============ merge data ============//
			$finalSubItems = array();
			foreach($subItems as $type => $items){
				foreach($items as $subItem){
					if(!empty($subProduct[$subItem['id']])){
						$data = array_merge($subProduct[$subItem['id']],$subItem);
						$extract_data = array(
							'shop_subproduct_id' => 'ShopSubproduct.id',
							'nb' => array('nb'),
							'item_price' => array('ShopSubproduct.price'),
							'item_operator' => 'ShopSubproduct.operator',
							'type' => 'ShopSubproduct.type',
						);
						$data = SetMulti::extractHierarchicMulti($extract_data,$data);
						$finalSubItems[] = $data;
					}
				}
			}
			return $finalSubItems;
		}
		return null;
	}
	function extractOrderItemData($productAndOptions){
		$extract_data = array(
			'product_id' => 'ShopProduct.id',
			'nb' => array('nb','Options.nb'),
			'comment' => 'Options.comment',
			'data' => 'Options.data',
			'descr' => array('DynamicField.title','ShopProduct.DynamicField.title','ShopProduct.code'),
			'item_price' => array('ShopProduct.DynamicField.price','DynamicField.price','Options.price','ShopProduct.price'),
			'item_tax_applied' => 'ShopProduct.tax_applied',
			'ShopPromotion' => array('ShopProduct.ShopPromotion','ShopPromotion'),
			'ShopSubproduct' => array('ShopProduct.ShopSubproduct','ShopSubproduct'),
			'SubItem' => array('Options.SubItem','SubItem'),
			'ShopOrdersSubitem' => array('ShopOrdersSubitem'),
			'item_rebate' => 'DynamicField.rebate',
			'item_original_price' => 'DynamicField.original_price',
			'product_related_id' => array('ShopProduct.Related.id','Related.id'),
		);
		$data = array();
		App::import('Lib', 'Shop.SetMulti');
		$data = SetMulti::extractHierarchicMulti($extract_data,$productAndOptions);
		if(!isset($data['item_tax_applied']) ||  $data['item_tax_applied'] === null){
			$data['item_tax_applied'] = true;
		}
		$data['active'] = 1;
		
		return $data;
	}
	

}


?>