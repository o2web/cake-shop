<?php
class ShopOrder extends ShopAppModel {
	var $name = 'ShopOrder';
	var $displayField = 'id';
	
	var $actsAs = array('Acl' => array('type' => 'controlled'),'Shop.Serialized'=>array('taxes','taxe_subs','supplements','supplement_choices'));
	
	var $status = array('input','ready','ordered','paid','shipped');
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $hasMany = array(
		'ShopOrdersItem' => array(
			'className' => 'Shop.ShopOrdersItem',
			'foreignKey' => 'order_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);


	var $hasAndBelongsToMany = array(
		'ShopProduct' => array(
			'className' => 'Shop.ShopProduct',
			'joinTable' => 'shop_orders_items',
			'foreignKey' => 'order_id',
			'associationForeignKey' => 'product_id',
			'unique' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		),
		'ShopPayment' => array(
			'className' => 'Shop.ShopPayment',
			'joinTable' => 'shop_orders_payments',
			'foreignKey' => 'order_id',
			'associationForeignKey' => 'payment_id',
			'unique' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		)
	);
	
	var $rootNodeAlias = "shopOrders";

	function parentNode() {
		$ref = $this->rootNodeAlias;
		$parent = $this->node($ref);
		//debug($parent);
		if(empty($parent)){
			//debug($rootNode);
			$data["alias"] = $this->rootNodeAlias;
			$this->Aco->create();
			$this->Aco->save($data);
		}
		//debug($ref);
		return $ref;
	}
	
	var $exposedFilters = array(
		'/shipping_.*/',
		'/billing_.*/'
	);
	function filterExposedfields($data){
		if(!empty($data)){
			$data = $data;
			$normalized = &$data;
			if(isset($data[$this->alias])){
				$normalized = &$data[$this->alias];
			}
			foreach($normalized as $key => $val){
				foreach($this->exposedFilters as $filter){
					if(preg_match($filter,$key)){
						continue 2;
					}
				}
				//if the loop was not broken
				unset($normalized[$key]);
			}
		}
		return $data;
	}
	
	function setupForFullData(){
		$this->checkActive = false;
		$this->Behaviors->attach('Containable');
		$this->contain(array('ShopOrdersItem'=>array('ShopProduct','ShopOrdersSubitem'=>'ShopSubproduct'),'ShopProduct'));
	}
	
	function beforeSave(){
		if(isset($this->data[$this->name])){
			$data = &$this->data[$this->name];
		}else{
			$data = &$this->data;
		}
		if(isset($data['id'])){
			$id = $data['id'];
		}else{
			$id = $this->id;
		}
		if(!empty($data['use_shipping'])){
			if(!empty($data['shipping_address'])){
				$shipping_data = $data;
			}elseif(!empty($id)){
				$shipping_data = $this->read(null,$id);
				$shipping_data = $shipping_data[$this->name];
			}
			if(!empty($shipping_data)){
				$prefix_from = "shipping_";
				$prefix_to = "billing_";
				foreach($shipping_data as $key => $val){
					if(substr($key,0,strlen($prefix_from)) == $prefix_from){
						$sufix = substr($key,strlen($prefix_from));
						if(empty($data[$prefix_to.$sufix])){
							$data[$prefix_to.$sufix] = $shipping_data[$key];
						}
					}
				}
			}
		}
		if(!empty($data['use_billing'])){
			if(!empty($data['billing_address'])){
				$billing_data = $data;
			}elseif(!empty($id)){
				$billing_data = $this->read(null,$id);
				$billing_data = $billing_data[$this->name];
			}
			if(!empty($billing_data)){
				$prefix_from = "billing_";
				$prefix_to = "shipping_";
				foreach($billing_data as $key => $val){
					if(substr($key,0,strlen($prefix_from)) == $prefix_from){
						$sufix = substr($key,strlen($prefix_from));
						if(empty($data[$prefix_to.$sufix])){
							$data[$prefix_to.$sufix] = $billing_data[$key];
						}
					}
				}
			}
		}
		App::import('Lib', 'Shop.SetMulti');
		$both_keys = SetMulti::pregFilterKey('/^both_/',$data);
		foreach($both_keys as $key => $val){
			$key = preg_replace('/^both_/','',$key);
			if(empty($data["billing_".$key])){
				$data["billing_".$key] = $val;
			}
			if(empty($data["shipping_".$key])){
				$data["shipping_".$key] = $val;
			}
		}
		
		$fieldsToAlias = array(
			'billing_country' => 'country',
			'billing_region' => 'region',
			'shipping_country' => 'country',
			'shipping_region' => 'region'
		);
		App::import('lib','Shop.Alias');
		$data = Alias::applyAliasMulti($data, $fieldsToAlias);
		
		return true;
	}

}
?>