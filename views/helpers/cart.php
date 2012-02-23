<?php
class CartHelper extends AppHelper {

	var $helpers = array('Html', 'Form', 'Javascript', 'O2form.O2form');
	
	function buyUrl($id=null,$nb=null,$model=null,$options=array()){
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
		if(empty($options['id'])){
			if(!empty($this->params['id'])){
				$options['id'] = $this->params['id'];
			}else if(!empty($this->params['named']['id'])){
				$options['id'] = $this->params['named']['id'];
			}else if(!empty($this->params['pass'][0]) && is_numeric($this->params['pass'][0])){
				$options['id'] = $this->params['named']['id'];
			}
		}
		if(empty($options['id']) || !is_numeric($options['id'])){
			return null;
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
	
	function qteInput($options = array()){
		$defOpt = array(
			'default'=>1
		);
		$opt = array_merge($defOpt, $options);
		return $this->O2form->input('ShopCart.nb',$opt);
	}
	
	function cartUrl(){
		return $this->Html->url(array('plugin'=>'shop','controller'=>'shop_cart','action'=>'index'));
	}
	
	function cartLink($options = array()){
		$defaultOptions = array(
			'label' => __("Your cart (%nbItem%)",true),
			'class' => array('cart'),
		);
		if(!empty($options) && !is_array($options)){
			$options = array('label' => $options);
		}
		$opt = array_merge($defaultOptions,$options);
		$label = $opt['label'];
		if(!empty($this->params['Shop'])){
			foreach($this->params['Shop'] as $key => $val){
				if(!is_array($val)){
					$label = str_replace('%'.$key.'%',$val,$label);
				}
			}
		}
		return $this->Html->link($label,array('plugin'=>'shop','controller'=>'shop_cart','action'=>'index'),$this->O2form->normalizeAttributesOpt($opt,array('label')));
	}
	
	

}

?>