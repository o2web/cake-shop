<?php
class OperationPromo extends Object {

	function editForm($view){
		$rebateHelp = $view->element('admin_promo_rebate_help',array());
		echo $view->Form->input('val',array('label'=>__('Rebate',true),'after'=>$rebateHelp));
		App::import('Lib', 'Shop.SetMulti');
		$this->ShopPromotion = ClassRegistry::init('Shop.ShopPromotion'); 
		$operators = $this->ShopPromotion->operators;
		echo $view->Form->input('operator',array('options'=>SetMulti::extractKeepKey('label',$operators)));
	}
		
	function apply($product,$newPrice,$promo){
		$this->ShopPromotion = ClassRegistry::init('Shop.ShopPromotion'); 
		$newPrice = $this->ShopPromotion->applyOperator($newPrice,$promo['operator'],$promo['val']);
		return $newPrice;
	}
}