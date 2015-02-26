<?php
class ShippingOperationPromo extends PromoMethod {
	var $label = "Operation on Shipping";

	function editForm($prefix,$view){
		$out = '';
		$out .= $view->Form->input('val',array('label'=>__('Rebate',true)));
		App::import('Lib', 'Shop.SetMulti');
		$this->ShopPromotion = ClassRegistry::init('Shop.ShopPromotion'); 
		$operators = $this->ShopPromotion->operators;
		$out .= $view->Form->input('operator',array('options'=>SetMulti::extractKeepKey('label',$operators)));
		return $out;
	}
		
	function apply(&$promo,&$order){
		//debug($order['Supplements']);
		
		$this->ShopPromotion = ClassRegistry::init('Shop.ShopPromotion'); 
		
		$order['Supplements']['shipping']['default']['promo'] = $promo['ShopPromotion']['id'];
		if($promo['ShopPromotion']['operator'] == 1){
			$order['Supplements']['shipping']['default']['calcul'] = null;
			$order['Supplements']['shipping']['default']['price'] = $promo['ShopPromotion']['val'];
		}else{
			$order['Supplements']['shipping']['default']['calcul'] = array(
				'mathOperation' => array(
					'operator' => $this->ShopPromotion->operators[$promo['ShopPromotion']['operator']]['sign'],
					'right' => $promo['ShopPromotion']['val'],
					'leftMethods' => $order['Supplements']['shipping']['default']['calcul'],
				)
			);
		}
		//debug($promo['ShopPromotion']);
		/*
		$order['Supplements']['promo_'.$promo['ShopPromotion']['id']]['default'] = array(
			'label' => $promo['ShopPromotion']['title'],
			'title' => $promo['ShopPromotion']['title'],
			'descr' => '',
			'price' => $promo['ShopPromotion']['val'],
			'tax_applied' => 1,
			'name' => 'default',
		);

		*/
		/*
		foreach($promo['ShopProduct'] as &$p){
			$this->ShopPromotion = ClassRegistry::init('Shop.ShopPromotion'); 
			$p['item_price'] = $this->ShopPromotion->applyOperator($p['item_price'],$promo['ShopPromotion']['operator'],$promo['ShopPromotion']['val']);
		}*/
	}
}