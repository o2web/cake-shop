<?php 
class OrderMakerComponent extends Object
{
	var $components = array('Shop.OrderFunct','Shop.ShopFunct','Shop.ProductMaker','Acl');
	
	
	var $ShopOrder = null;
	var $controller = null;
	
	function initialize(&$controller) {
		$this->controller =& $controller;
		if(!empty($controller->ShopOrder)){
			$this->ShopOrder = $controller->ShopOrder;
		}
	}
	function init(&$controller) {
		debug('test');
		$this->controller =& $controller;
		if(!empty($controller->ShopOrder)){
			$this->ShopOrder = $controller->ShopOrder;
		}
	}
	
	function initShopOrder(){
		if(!$this->ShopOrder){
			$this->ShopOrder = $this->OrderFunct->initShopOrder();
		}
		return $this->ShopOrder;
	}
	
	function add($options){//séparer option et produits ?
		$defaultOptions = array(
			'id' => null,
			'order' => array(),
			'products' => array(),
			'redirect' => true
		);
		if(!is_array($options)){
			$options = array('products'=>array($options));
		}
		if(!empty($options['products']) && is_array($options['products']) && empty($options['products'][0])){
			$options['products'] = array($options['products']);
		}
		$options = array_merge($defaultOptions,$options);
		
		if (!empty($options['products'])) {
			foreach((array)$options['products'] as $productOpt){
				$productOpt = $this->ShopFunct->formatProductAddOption($productOpt);
				$conditions = $this->ShopFunct->productFindConditions($productOpt);
				$this->initShopOrder();
				$this->ShopOrder->ShopProduct->Behaviors->attach('Containable');
				$this->ShopOrder->ShopProduct->contain(array('ShopSubproduct'));
				if($conditions !== false){
					$product = array();
					$product = $this->ShopOrder->ShopProduct->find('first',array('conditions'=>$conditions));
				}
				if(empty($product) && empty($productOpt['id'])){
					$product_id = $this->ProductMaker->add($productOpt);
					if($product_id){
						$product = $this->ShopOrder->ShopProduct->read(null,$product_id);
						
					}
				}
				if(!empty($product)){
					/*$optToData = array('nb','comment','data');
					foreach($optToData as $optName){
						if(!empty($productOpt[$optName])){
							$product[$optName] = $productOpt[$optName];
						}
					}*/
					$product['Options'] = $productOpt;
					$products[] = $product;
				}else{
					//product not found
				}
			}
		}
		
		
		if(!$options['id']){
			if(empty($products)){
				if($options['redirect']){
					$this->controller->redirect(array('plugin'=>'shop', 'controller'=>'shop_orders', 'action' => 'empty_error'));
				}else{
					return false;
				}
			}
			$this->initShopOrder();
			$this->ShopOrder->create();
			$orderData = $this->ShopOrder->filterExposedfields($options['order']);
			$orderData['status'] = 'input';
			$this->ShopOrder->save($orderData);
			$order_id = $this->ShopOrder->id;
			$aro = $this->OrderFunct->getAro();
			$this->Acl->allow($aro, $this->ShopOrder);
		}else{
			$order_id = $options['id'];
			$this->OrderFunct->tcheckAcl($order_id);
			$orderData = $this->ShopOrder->filterExposedfields($options['order']);
			if(!empty($orderData)){
				$orderData['id'] = $order_id;
				if(!$this->ShopOrder->save($orderData)){
					$this->log('Could not save Order',LOG_DEBUG);
				}
			}
		}
		
		if (!empty($products)) {
			foreach($products as $product){
				$this->ShopOrder->ShopOrdersItem->create();
				$data = $this->ShopFunct->extractOrderItemData($product);
				$subItems = $this->ShopFunct->extractSubItemData($data);//extract sub items
				//debug($data);
				//debug($subItems);
				//exit();
				$data['order_id'] = $order_id;
				////////// save sub items //////////
				if($this->ShopOrder->ShopOrdersItem->save($data)){
					if(!empty($subItems)){
						foreach($subItems as $subItem){
							$subItem['shop_orders_item_id'] = $this->ShopOrder->ShopOrdersItem->id;
							if($this->ShopOrder->ShopOrdersItem->ShopOrdersSubitem->save($subItem)){
							}else{
								$this->log('Could not save OrderSubitem',LOG_DEBUG);
							}
						}
					}
				}else{
					$this->log('Could not save OrderItem',LOG_DEBUG);
				}
			}
		}
		if($options['redirect'] && ($options['redirect'] != 'add' || !$options['id'])){
			$this->controller->redirect(array('plugin'=>'shop', 'controller'=>'shop_orders', 'action' => 'add', $order_id, 'lang'=>$this->controller->lang));
		}else{
			return $order_id;
		}
	}
	
	function refresh($order_id){
		$this->initShopOrder();
		$this->ShopOrder->recursive = 1;
		$order = $this->ShopOrder->read(null,$order_id);
		$updateStatus = array('ready','ordered');
		$oldStatus = $order['ShopOrder']['status'];
		if(in_array($oldStatus,$updateStatus)){
			$total_paid = 0;
			foreach($order['ShopPayment'] as $shopPayment){
				if(in_array($shopPayment['status'],$this->ShopOrder->ShopPayment->okStatus)){
					$total_paid += $shopPayment['ShopOrdersPayment']['amount'];
				}
			}
			$data = array();
			$data['id'] = $order_id;
			$data['amount_paid'] = $total_paid;
			if($total_paid >= $order['ShopOrder']['total']){
				$data['status'] = 'ordered';
			}else{
				$data['status'] = 'ready';
			}
			$this->ShopOrder->save($data);
			if($data['status'] != $oldStatus){
				$this->statusUpdated($order_id,$data['status']);
			}
		}
		
	}
	
	function statusUpdated($order_id,$status){
	
		$callback = 'on'.Inflector::humanize($status).'Status';
		if(method_exists($this->OrderFunct,$callback)){
			$this->OrderFunct->{$callback}($order_id);
		}
		
		return;
		// call product events (prototype)
		$this->ShopOrder->ShopOrdersProduct->recursive = -1;
		$Aro = ClassRegistry::init("Aro");
		$Aco = ClassRegistry::init("Aco");
		$this->ShopOrder->ShopProduct->bindModel(array(
				'hasOne' => array(
					'ShopOrdersItem' => array(
						'className' => 'Shop.ShopOrdersItem',
						'foreignKey' => 'product_id',
					)
				)
			));
		$products = $this->ShopOrder->ShopProduct->find('all',array(
				'fields'=>array('ShopProduct.*','ShopOrdersItem.*','Aro.id','Aro.lft','Aro.rght'),
				'conditions'=>array(
					'ShopOrdersItem.order_id'=>$order_id
				),
				'joins' => array(array(
					'table' => 'aros',
					'alias' => 'Aro',
					'type' => 'INNER',
					'conditions' => array(
							'Aro.foreign_key = ShopProduct.id',
							'Aro.model' => 'ShopProduct'
						)
				))
			));
		if(!empty($products)){
			$products = $this->ShopOrder->ShopProduct->ShopOrdersItem->tcheckResults($products);
			$conditions = array();
			foreach($products as $product){
				$actions = $Aro->find('all',array(
						'fields'=>array('ShopAction.*','Aro.id','Aro.alias','Aro.model','Aro.foreign_key'),
						'conditions'=>array(
							$product['Aro']['lft'].' BETWEEN Aro.lft AND Aro.rght'
						),
						'joins' => array(
							array(
								'table' => 'aros_acos',
								'alias' => 'ArosAco',
								'type' => 'INNER',
								'conditions'=> array('Aro.id = ArosAco.aro_id')
							),
							array(
								'table' => 'acos',
								'alias' => 'Aco',
								'type' => 'INNER',
								'conditions'=> array(
									'Aco.id = ArosAco.aco_id',
									'Aco.model' => 'ShopAction'
								)
							),
							array(
								'table' => 'shop_actions',
								'alias' => 'ShopAction',
								'type' => 'INNER',
								'conditions'=> array(
									'ShopAction.id = Aco.foreign_key',
									'or' => array(
										'status IS NULL',
										'status' => $status
									)
								)
							)
						)
					));
				foreach($actions as $action){
					$context = array('ShopProduct'=>$product['ShopProduct'],'ShopOrdersItem'=>$product['ShopOrdersItem'],'status'=>$status,'action'=>$action['ShopAction']);
					$params = array_merge(array($context),(array)$action['ShopAction']['params']);
					$this->ShopFunct->callExternalfunction(array('component'=>$action['ShopAction']['component'],'functName'=>$action['ShopAction']['function'],'params'=>$params));
				}
			}
		}
	}
	
	
	
}
?>