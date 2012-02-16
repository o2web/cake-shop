<?php
class ShopAddress extends ShopAppModel {
	var $name = 'ShopAddress';
	var $displayField = 'id';
	
	
	var $rootNodeAlias = "ShopAddress";

	function parentNode() {
		$ref = $this->rootNodeAlias;
		$parent = $this->node($ref);
		//debug($parent);
		if(empty($parent)){
			//debug($rootNode);
			$data["alias"] = $this->rootNodeAlias;
			$this->Aco->create();
			$this->Aco->save($data);
		}
		//debug($ref);
		return $ref;
	}
}
?>