<?php
class OperationPromo extends PromoMethod {
	var $label = "Operation on products price";

	function editForm($prefix,$view){
		$out = '';
		$rebateHelp = $view->element('admin_promo_rebate_help',array());
		$out .= $view->Form->input('val',array('label'=>__('Rebate',true),'after'=>$rebateHelp));
		App::import('Lib', 'Shop.SetMulti');
		$this->ShopPromotion = ClassRegistry::init('Shop.ShopPromotion'); 
		$operators = $this->ShopPromotion->operators;
		$out .= $view->Form->input('operator',array('options'=>SetMulti::extractKeepKey('label',$operators)));
		return $out;
	}
		
	function apply(&$promo,&$order){
		foreach($promo['ShopProduct'] as &$p){
			$this->ShopPromotion = ClassRegistry::init('Shop.ShopPromotion'); 
			$p['item_price'] = $this->ShopPromotion->applyOperator($p['item_price'],$promo['ShopPromotion']['operator'],$promo['ShopPromotion']['val']);
		}
	}
}