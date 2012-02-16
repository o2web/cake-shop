<?php
class ShopAppModel extends AppModel {
	
	function afterFind($results,$primary){
		$results = parent::afterFind($results,$primary);
		
		if(!$primary){
			$return = $this->behaviorsTrigger($this, 'assocAfterFind', array($results, $primary), array('modParams' => true));
			if ($return !== true) {
				$results = $return;
			}
		}
		
		return $results;
	}
	
	function behaviorsTrigger(&$model, $callback, $params = array(), $options = array()) {
		
		if (empty($this->Behaviors->_attached)) {
			return true;
		}
		$options = array_merge(array('break' => false, 'breakOn' => array(null, false), 'modParams' => false), $options);
		$count = count($this->Behaviors->_attached);

		for ($i = 0; $i < $count; $i++) {
			$name = $this->Behaviors->_attached[$i];
			if (in_array($name, $this->Behaviors->_disabled)) {
				continue;
			}
			if(method_exists($this->Behaviors->{$name},$callback)){
				$result = $this->Behaviors->{$name}->dispatchMethod($model, $callback, $params);
			}else{
				$result = false;
			}

			if ($options['break'] && ($result === $options['breakOn'] || (is_array($options['breakOn']) && in_array($result, $options['breakOn'], true)))) {
				return $result;
			} elseif ($options['modParams'] && is_array($result)) {
				$params[0] = $result;
			}
		}
		if ($options['modParams'] && isset($params[0])) {
			return $params[0];
		}
		return true;
	}
}
?>