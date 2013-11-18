<?php
class ActionPromo extends PromoMethod {
	var $label = "Action (Deprecated)";
	
	function isAvailable(){
		$ShopAction = ClassRegistry::init('Shop.ShopAction'); 
		$actions = $ShopAction->find('count',array('conditions'=>array('status'=>'checkPromo')));
		return $actions>0;
	}
	
	function beforeForm($controller){
		$data = $controller->data;
		if(!empty($data['ShopAction']['ui']['beforeForm'])){
			$controller->ShopFunct->callExternalfunction($data['ShopAction']['ui']['beforeForm']);
		}
		$this->ShopAction = ClassRegistry::init('Shop.ShopAction'); 
		$actions = $this->ShopAction->find('list',array('conditions'=>array('status'=>'checkPromo')));
		$controller->set('actions', $actions);
	}
	
	function editForm($prefix,$view){
		$out = '';
		$view->Html->scriptBlock('
			(function( $ ) {
				$(function(){
					$("#ShopPromotionActionId").change(function(){
						$("#ActionSubForm .loader").show();
						$("#ActionSubForm").load(root+"admin/shop/shop_actions/form/"+$(this).val());
					});
				})
			})( jQuery );
		',array('inline'=>false));
		$out .= $view->Form->input('action_id',array('empty'=>__('None',true)));
		$out .= '<div id="ActionSubForm" class="ajaxContainer"><div class="loader"></div></div>';
		return $out;
	}
	
	
	function apply($product,$newPrice,$promo,$ShopFunct){
		if(!empty($promo['ShopAction']) && !empty($promo['ShopAction']['id'])){
			$this->ShopAction = ClassRegistry::init('Shop.ShopAction'); 
			$action = $this->ShopAction->toCallBack($promo['ShopAction'],array($p,$newPrice,$promo['action_params']));
			$res = $ShopFunct->callExternalfunction($action);
			return $res;
		}
		return true;
	}
}