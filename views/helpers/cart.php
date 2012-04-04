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
		$defmodel = null;
		//$defmodel = $this->model();
		if($defmodel == null){
			$defmodel = Inflector::classify($this->params['controller']);
		}
		$localOpt = array('routed');
		$defaultOptions = array(
			'model' => $defmodel,
			'nb' => 1,
			'routed' => true,
		);
		$options = array_merge($defaultOptions,$options);
		$urlOpt = array('plugin'=>'shop','controller'=>'shop_cart','action'=>'add');
		$urlOpt = array_merge(array_diff_key($options,array_flip($localOpt)),$urlOpt);
		if($options['routed']){
			return $this->Html->url($urlOpt);
		}else{
			return $urlOpt;
		}
	}
	
	function buyLink($label=null,$id=null,$nb=null,$model=null,$options=array()){
		App::import('Lib', 'Shop.ShopConfig');
		$enabled = ShopConfig::load('enabled');
		if(!$enabled){
			return '';
		}
		
		if(is_array($label)){
			$options = $label;
		}else{
			if(!empty($label)){
				$options['label'] = $label;
			}
			if(!empty($id)){
				$options['id'] = $id;
			}
			if(!empty($nb)){
				$options['nb'] = $nb;
			}
			if(!empty($model)){
				$options['model'] = $model;
			}
		}
		if(empty($options['label'])){
			$options['label'] = __('Add to your cart',true);
		}
		$localOpt = array('label','id','nb','model','routed');
		$options['routed'] = false;
		$url = $this->buyUrl($options);
		return $this->Html->link($options['label'],$url,$this->O2form->normalizeAttributesOpt($options,$localOpt));
	}
	
	function subitemInput($type, $prod = null, $options = array(), $no = null){
		$view =& ClassRegistry::getObject('view');
		if(!is_array($type)){
			App::import('Lib', 'Shop.ShopConfig');
			$types = ShopConfig::getSubProductTypes();
			if(empty($types[$type])){
				return null;
			}
			$type = $types[$type];
		}
		$defOpt = array(
			'label'=>$type['label'],
		);
		$opt = array_merge($defOpt, $options);
		if(!is_array($prod)){
			$prod = null;
			$no = $prod;
		}
		$sources = array(
			'prod.ShopProduct.ShopSubproduct.'.$type['name'],
			'prod.ShopSubproduct.'.$type['name'],
			'data.ShopProduct.ShopSubproduct.'.$type['name'],
			'vars.'.Inflector::singularize($this->params['controller']).'.ShopProduct.ShopSubproduct.'.$type['name'],
		);
		App::import('Lib', 'Shop.SetMulti');
		$data = array('prod'=>$prod, 'data'=>$this->data,'vars'=>$view->viewVars,'params'=>$this->params);
		$subProducts = SetMulti::extractHierarchic($sources, $data);
		if(empty($subProducts)){
			return null;
		}
		if(!is_null($no) && is_numeric($no)){
			$name = 'ShopCart.products.'.$no.'.SubItem.'.$type['name'];
		}else{
			$name = 'ShopCart.SubItem.'.$type['name'];
		}
		return $view->element('subproduct_select',array('plugin'=>'shop','type'=>$type,'subProducts'=>$subProducts,'name'=>$name,'options'=>$opt));
	}
	
	function qteInput($no = null, $options = array()){
		if(is_array($no)){
			$options = $no;
			$no = null;
		}
		$defOpt = array(
			'default'=>1
		);
		$opt = array_merge($defOpt, $options);
		if(!is_null($no) && is_numeric($no)){
			$name = 'ShopCart.products.'.$no.'.nb';
		}else{
			$name = 'ShopCart.nb';
		}
		return $this->O2form->input($name,$opt);
	}
	
	function cartUrl(){
		return $this->Html->url(array('plugin'=>'shop','controller'=>'shop_cart','action'=>'index'));
	}
	
	function cartLink($options = array()){
		App::import('Lib', 'Shop.ShopConfig');
		$enabled = ShopConfig::load('enabled');
		if(!$enabled){
			return '';
		}
		
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