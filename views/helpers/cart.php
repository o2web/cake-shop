<?php
class CartHelper extends AppHelper {

	var $helpers = array('Html', 'Form', 'Javascript');
	
	function buyUrl($id,$nb=null,$model=null,$options=array()){
		if(is_array($id)){
			$options = $id;
		}else{
			$options['id'] = $id;
			if(!empty($nb)){
				$options['nb'] = $nb;
			}
			if(!empty($model)){
				$options['model'] = $model;
			}
		}
		$defmodel = $this->model();
		if($defmodel == null){
			$defmodel = Inflector::classify($this->params['controller']);
		}
		$defaultOptions = array(
			'model' => $defmodel,
			'nb' => 1
		);
		$options = array_merge($defaultOptions,$options);
		return $this->Html->url(array('plugin'=>'shop','controller'=>'shop_cart','action'=>'add', 'model'=>$options['model'], 'id'=>$options['id'], 'nb'=>$options['nb']));
	}
	

}

?>