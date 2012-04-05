<?php 
class SupplementMethodComponent extends Object
{
	var $controller = null;
	
	function initialize(&$controller) {
		$this->controller =& $controller;
	}
	
	function rangesOpt($supplementItem,$order,$supplement_choice,$calcul){
		$opt = array(
			'rangedValue' => 'calcul.total_items',
			'defModif' => 'price',
		);
		$data = compact('supplementItem', 'order', 'supplement_choice', 'calcul');
		$rangedValue = Set::extract($opt['rangedValue'], $data);
		if(!empty($supplementItem['ranges'])){
			foreach($supplementItem['ranges'] as $range => $setting){
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
	
}
?>