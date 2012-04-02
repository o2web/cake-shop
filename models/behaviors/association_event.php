<?php
class AssociationEventBehavior extends ModelBehavior {
	
	var $callback = 'afterFindAssoc';
	
	function afterFind(&$model, $results, $primary){
		$results = $this->_filterResults($model,$results);
		return $results;
	}
	
	function _filterResults(&$model,$results,$exclude = array(),$fullData = null,$path=''){
		if(is_null($fullData)){
			$fullData = $results;
		}
		$multiple = Set::numeric(array_keys($results));
		if(!$multiple){
			$results = array($results);
		}
		foreach($results as &$result){
			if(is_array($result)){
				foreach($result as $className => $val){
					if(!empty($val)
							&& $className == Inflector::classify($className)
							&& is_array($val) && isset($model->{$className})
							&& is_object($model->{$className})
							&& !in_array($className, (array)$exclude)
						) {
						$newModel = $model->{$className};
						$newPath = (!empty($path)?$path.'.':'').$className;
						$format = array();
						
						$vals = $this->unifiedResult($newModel, $val, null, null, $format);
						
						//$vals[0][$newModel->alias]['processed'] = 1;
						
						foreach($vals as $key => $res){
							$vals[$key][$newModel->alias] = $this->_filterResults($newModel,$res[$newModel->alias],$exclude,$fullData,$newPath);
						}
						
						//debug(array('result'=>$vals, 'path'=>$newPath, 'format'=> $format));
						$res = $this->behaviorsTrigger($newModel, $this->callback,array($vals,$fullData,$newPath), array('modParams' => true));
						if ($res !== true) {
							$vals = $res;
						}
						if(method_exists($newModel,$this->callback)){
							$res = $newModel->{$this->callback}($vals,$fullData,$newPath);
							if ($res !== true) {
								$vals = $res;
							}
						}	
						
						$val = $this->unifiedResult($newModel, $vals, null, $format);
						
						$result[$className] = $val;
					}
				}
			}
		}
		if(!$multiple){
			$results = $results[0];
		}
		return $results;
	}
	
	function unifiedResult($model, $results, $alias = null, $format = array(), &$oldFormat = null){
		$defFormat = array(
			'multiple' => true,
			'named' => true,
		);
		$format = array_merge($defFormat,(array)$format);
		if(is_null($alias)){
			$alias = $model->alias;
		}
		$oldFormat['multiple'] = Set::numeric(array_keys($results));
		if(!$oldFormat['multiple']){
			$oldFormat['named'] = isset($results[$alias]);
			if($oldFormat['named'] == $format['named']){
				$uResults = $results;
			}elseif($format['named']){
				$uResults = array($alias=>$results);
			}else{
				$uResults = $results[$alias];
			}
			if($format['multiple']){
				$uResults = array($uResults);
			}
		}else{
			$uResults = array();
			foreach($results as $key => &$val){
				$oldFormat['named'] = isset($val[$alias]);
				if($oldFormat['named'] == $format['named']){
					$uResults[$key] = $val;
				}elseif($format['named']){
					$uResults[$key][$alias] = $val;
				}else{
					$uResults[$key] = $val[$alias];
				}
			}
			if(!$format['multiple']){
				$uResults = $uResults[0];
			}
		}
		return $uResults;
	}
	
	function behaviorsTrigger(&$model, $callback, $params = array(), $options = array()) {
		
		if (empty($model->Behaviors->_attached)) {
			return true;
		}
		$defOpt = array(
			'break' => false,
			'breakOn' => array(null, false),
			'modParams' => false
		);
		$options = array_merge($defOpt, $options);
		$count = count($model->Behaviors->_attached);

		for ($i = 0; $i < $count; $i++) {
			$name = $model->Behaviors->_attached[$i];
			if (in_array($name, $model->Behaviors->_disabled)) {
				continue;
			}
			if(method_exists($model->Behaviors->{$name},$callback)){
				$result = $model->Behaviors->{$name}->dispatchMethod($model, $callback, $params);
				
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