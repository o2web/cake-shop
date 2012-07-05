<?php 
	if(!empty($shopOrder['ShopOrdersItem'])){
		$lines = array();
		foreach($shopOrder['ShopOrdersItem'] as $orderItem){ 
			$label = '???';
			if(!empty($orderItem['item_title'])){
				$label = $orderItem['item_title']; 
			}elseif(!empty($orderItem['ShopProduct']['code'])){
				$label = $orderItem['ShopProduct']['code']; 
			}elseif(!empty($orderItem['ShopProduct']['id'])){
				$label = sprintf(__d('shop','product id : %s',true),$orderItem['ShopProduct']['id']);
			}else{
				$label = sprintf(__d('shop','item id : %s',true),$orderItem['id']);
			}
			$url = null;
			if(!empty($orderItem['ShopProduct']['model']) && !empty($orderItem['ShopProduct']['foreign_id'])){
				$url = array('plugin'=>'','controller'=>Inflector::tableize($orderItem['ShopProduct']['model']),'action'=>'view',$orderItem['ShopProduct']['foreign_id']);
			}
			$label .= ' (x'.$orderItem['nb'].')';
			if(!empty($url)){
				$lines[] = $html->link($label,$url);
			}else{
				$lines[] = $label;
			}
		} 
		echo implode('<br />',$lines);
	}
?>