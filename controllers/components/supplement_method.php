<?php 
class SupplementMethodComponent extends Object
{
	var $controller = null;
	
	function initialize(&$controller) {
		$this->controller =& $controller;
	}
	
	//////////////// Applicable Function ////////////////
	
	function checkShippingReq($options,$supplementItem,$order,$supplement_choice,$calcul){
		if(!empty($order['ShopOrdersItem'])){
			foreach($order['ShopOrdersItem'] as $item){
				if(!isset($item['shipping_req']) || $item['shipping_req']){
					return true;
				}
			}
		}
		return false;
	}
	
	//////////////// Calcul Function ////////////////
	
	function rangesOpt($options,$supplementItem,$order,$supplement_choice,$calcul){
		$defOpt = array(
			'rangedValue' => 'calcul.total_items',
			'defModif' => 'total',
			'ranges' => array()
		);
		if(!count(array_intersect_key($options,$defOpt))){
			$options = array('ranges' =>$options);
		}
		if(!empty($supplementItem['ranges'])){
			$options['ranges'] = $supplementItem['ranges'];
		}
		$opt = array_merge($defOpt,$options);
		$data = compact('supplementItem', 'order', 'supplement_choice', 'calcul');
		$rangedValue = Set::extract($opt['rangedValue'], $data);
		if(!empty($opt['ranges'])){
			foreach($opt['ranges'] as $range => $setting){
				$min = PHP_INT_MAX;
				$max = 0;
				if(preg_match('/^([0-9]+)-([0-9]+)$/', $range, $matches)){
					$min = $matches[1];
					$max = $matches[2];
				}elseif(preg_match('/^-([0-9]+)$/', $range, $matches)){
					$min = 0;
					$max = $matches[1];
				}elseif(preg_match('/^([0-9]+)\+$/', $range, $matches)){
					$min = $matches[1];
					$max = PHP_INT_MAX;
				}
				if($rangedValue >= $min && $rangedValue <= $max){
					if(!is_array($setting)){
						$setting = array($opt['defModif']=>$setting);
					}
					$supplementItem = array_merge($supplementItem, $setting);
				}
			}
		}
		return $supplementItem;
	}
	function multiplyByNb($options,$supplementItem,$order,$supplement_choice,$calcul){
		return $supplementItem['total'] * $calcul['nb_total'];
	}
	function byCountry($options,$supplementItem,$order,$supplement_choice,$calcul){
		$defOpt = array(
			'keyPath' => array('order.ShopOrder.shipping_country','settings.defaultCountry'),
			'modifProp' => 'total',
			'list' => array()
		);
		if(!count(array_intersect_key($options,$defOpt))){
			$options = array('list' =>$options);
		}
		$opt = array_merge($defOpt,$options);
		
		
		App::import('Lib', 'Shop.ShopConfig');
		$settings = ShopConfig::load();
		$dataSource = array('settings'=>$settings,'order'=>$order,'calcul'=>$calcul);
		
		App::import('Lib', 'Shop.SetMulti');
		$country = SetMulti::extractHierarchic($opt['keyPath'], $dataSource);
			
		if(array_key_exists($country,$opt['list'])){
			$supplementItem[$opt['modifProp']] = $opt['list'][$country];
		}else{
			App::import('Lib', 'O2form.Geography');
			$continent = Geography::getContinent($country);
			if(array_key_exists($continent,$opt['list'])){
				$supplementItem[$opt['modifProp']] = $opt['list'][$continent];
			}
		}
		
		
		return $supplementItem;
	}
	
}
?>