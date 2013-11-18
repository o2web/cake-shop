<?php
class CartContainFilterPromo extends PromoMethod {
	var $type = 'condition';
	
	
	function beforeForm($controller){
		if(!empty($controller->viewVars['products'])){
			$this->products = $controller->viewVars['products'];
		}else{
			$this->ShopProduct = ClassRegistry::init('Shop.ShopProduct');
			$this->products = $this->ShopProduct->generateAroList();
		}
	}
	
	function editForm($prefix,$view){
		$out = '';
		$out .= $view->Form->input($prefix.'.aroProduct',array('type'=>'select','label'=>__('Look For',true),'options'=>$this->products));
		$out .= $view->Form->input($prefix.'.min',array('type'=>'text','label'=>__('Minimum Quantity',true),'default'=>1));
		$out .= $view->Form->input($prefix.'.max',array('type'=>'text','label'=>__('Maximum Quantity',true)));
		return $out;
	}
	
	function validate($prod,$order){
		//debug($this->params);
		//debug($order);
		if(empty($order['ShopOrdersItem'])) return false;
		$ids = Set::extract('ShopOrdersItem.{n}.product_id',$order);
		$this->ShopProduct = ClassRegistry::init('Shop.ShopProduct');
		$this->ShopProduct->Aro = ClassRegistry::init('Aro');
		if(!empty($this->params['aroProduct'])){
			$findOpt = array(
				'fields'=>array(
					'Aro.foreign_key'
				),
				'conditions'=>array(
					'Aro.model' => 'ShopProduct',
					'Aro.foreign_key' => $ids,
				),
				'joins' => array(
					array(
						'alias' => 'Parent',
						'table'=> $this->ShopProduct->Aro->useTable,
						'type' => 'INNER',
						'conditions' => array(
							'Parent.id' => $this->params['aroProduct'],
							'Aro.lft BETWEEN Parent.lft AND Parent.rght'
						)
					)
				)
			);
			$match = $this->ShopProduct->Aro->find('list',$findOpt);
			$match = array_values($match);
		}else{
			$match = $ids;
		}
		$qty = 0;
		foreach($order['ShopOrdersItem'] as $item){
			if(!empty($item['product_id']) && in_array($item['product_id'],$match) ){
				$qty += $item['nb'];
			}
		}
		return (!is_numeric($this->params['min']) || $qty >= $this->params['min'])
			&& (!is_numeric($this->params['max']) || $qty <= $this->params['max']);
	}
}