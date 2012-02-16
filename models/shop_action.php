<?php
class ShopAction extends ShopAppModel {
	var $name = 'ShopAction';
	var $displayField = 'code';
	
	//uses acl to link products instead of Associations
	var $actsAs = array(
		'Acl' => array('type' => 'controlled'),
		'Shop.Serialized'=>array('ui'),
	);
	
	var $rootNodeAlias = "shopActions";

	function toCallBack($action = null, $additionnalParams = array()){
		if(empty($action)){
			$action = $this->id;
		}
		if(is_numeric($action)){
			$tmp = $this->recursive;
			$this->recursive = -1;
			$action = $this->read(null,$action);
			$this->recursive = $tmp;
		}
		if(!empty($action[$this->alias])){
			$action = $action[$this->alias];
		}
		return array('component'=>$action['component'],'functName'=>$action['function'],'params'=>array_merge((array)$action['params'],$additionnalParams));
	}
	
	function parentNode() {
		$ref = $this->rootNodeAlias;
		
		return $ref;
	}
	
	
}
?>