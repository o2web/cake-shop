<?php
class CartHelper extends AppHelper {

	var $helpers = array('Html', 'Form', 'Javascript', 'O2form.O2form');
	
	function defID(){
		if(!empty($this->params['id'])){
			return $options['id'] = $this->params['id'];
		}else if(!empty($this->params['named']['id'])){
			return $options['id'] = $this->params['named']['id'];
		}else if(!empty($this->params['pass'][0]) && is_numeric($this->params['pass'][0])){
			return $options['id'] = $this->params['pass'][0];
		}
		$model = $this->defModel();
		if($model){
			if(!empty($this->data[$model]['id'])){
				return $this->data[$model]['id'];
			}
			$view =& ClassRegistry::getObject('view');
			if(!empty($view->viewVars[Inflector::variable($model)][$model]['id'])){
				return $view->viewVars[Inflector::variable($model)][$model]['id'];
			}
		}
		return null;
	}
	
	function defModel(){
		$defmodel = null;
		//$defmodel = $this->model();
		if($defmodel == null){
			$defmodel = Inflector::classify($this->params['controller']);
		}
		return $defmodel;
	}
	
	function buyUrl($id=null,$nb=null,$model=null,$options=array()){
		if(is_array($id) && !is_array($id)){
			$options = $id;
		}else{
			if(is_array($nb)){
				$options = $nb;
			}elseif(!empty($nb)){
				$options['nb'] = $nb;
			}
			if(is_array($id)){
				$options['product'] = $id;
			}else{
				$options['id'] = $id;
			}
			
			if(!empty($model)){
				$options['model'] = $model;
			}
		}
		if(empty($options['id']) && !empty($options['product'])){
			if(!empty($options['product']['id'])){
				$options['id'] = $options['product']['id'];
			}else{
				if(empty($options['model'])){
					$options['model'] = $this->defModel();
				}
				if(!empty($options['product'][$options['model']]['id'])){
					$options['id'] = $options['product'][$options['model']]['id'];
				}
			}
		}
		/*if(is_array($options['id'])){
			
		}*/
		if(empty($options['id']) && $defID = $this->defID()){
			$options['id'] = $defID;
		}
		if(empty($options['id']) || !is_numeric($options['id'])){
			return null;
		}
		$localOpt = array('routed','product');
		$defaultOptions = array(
			'model' => $this->defModel(),
			'nb' => null,
			'routed' => true,
			'back' => null,
			'redirect' => null,
		);
		$options = array_merge($defaultOptions,$options);
		if(!empty($options['back'])){
			App::import('Lib', 'Shop.UrlParam');
			//debug($options['back']);
			$options['back'] = UrlParam::encode($options['back']);
			//debug($options['back']);
			//debug(UrlParam::decode($options['back']));
		}
		if(!empty($options['redirect'])){
			App::import('Lib', 'Shop.UrlParam');
			$options['redirect'] = UrlParam::encode($options['redirect']);
		}
		$urlOpt = array('plugin'=>'shop','controller'=>'shop_cart','action'=>'add');
		$urlOpt = array_merge(array_diff_key($options,array_flip($localOpt)),$urlOpt);
		//debug($urlOpt);
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
		$localOpt = array('label');
		$options['routed'] = false;
		$url = $this->buyUrl(array_diff_key($options,array_flip($localOpt)));
		$localOpt = array('label','id','nb','model','routed');
		return $this->Html->link($options['label'],$url,$this->O2form->normalizeAttributesOpt($options,$localOpt));
	}
	
	function subitemInputs($prod = null, $options = array(), $no = null){
		App::import('Lib', 'Shop.ShopConfig');
		$types = empty($options['types']) ? ShopConfig::getSubProductTypes() : $options['types'];
		if(!empty($types)) { 
			$localOpt = array('list');
			$inputOpt = array_diff_key($options,array_flip($localOpt));
			$myTypes = array();
			foreach($types as $key => $type){
				$type['input'] = $this->subitemInput($type,$prod,$inputOpt,$no);
				if($type['input']){
					$myTypes[$key] = $type;
				}
			}
			if(!empty($options['list'])){
				return $myTypes;
			}
			$html='';
			foreach($myTypes as $myType){
				$html .= $myType['input'];
			}
			return $html;
		}
		return null;
	}
	
	function subitemInput($type, $prod = null, $options = array(), $no = null){
		$view =& ClassRegistry::getObject('view');
		if(!is_array($type)){
			App::import('Lib', 'Shop.ShopConfig');
			$types = empty($options['types']) ? ShopConfig::getSubProductTypes() : $options['types'];
			if(empty($types[$type])){
				return null;
			}
			$type = $types[$type];
		}
		$localOpt = array('prefix','recursive','types','subProducts');
		$defOpt = array(
			'prefix'=> ((!is_null($no) && is_numeric($no)) 
				? 'ShopCart.products.'.$no.'.SubItem.'
				: 'ShopCart.SubItem.'),
			'label'=>$type['label'],
			'class'=>'subItem',
			'recursive'=>true,
		);
		$opt = array_merge($defOpt, $options);
		
		if(empty($opt['subProducts'])){
			//debug($prod);
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
		}elseif(!empty($opt['subProducts'][$type['name']])){
			$subProducts = $opt['subProducts'][$type['name']];
		}else{
			return null;
		}
		$children = array();
		if(empty($subProducts)){
			return null;
		}
		$name = $opt['prefix'].$type['name'];
		if($opt['recursive']){
			foreach($subProducts as $subProduct){
				if(!empty($subProduct['children'])){
					$children[$subProduct['id']] = $this->O2form->conditionalBlock(
						$this->subitemInputs($prod,array(
							'types'=>$type['children'],
							'subProducts'=>$subProduct['children'],
							'prefix'=>$opt['prefix'].'children.'.$subProduct['id'].'.'
						), $no),
						$name,
						$subProduct['id']
					);
				}
			}
		}
		return $view->element('subproduct_select',array(
			'plugin'=>'shop',
			'type'=>$type,
			'subProducts'=>$subProducts,
			'name'=>$name,
			'options'=>array_diff_key($opt,array_flip($localOpt)),
			'children'=>$children
		));
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
	
	function addedQty($id=null,$model=null){
		if(empty($model)) $model = $this->defModel();
		if(is_array($id)){
			if(!empty($id['id'])){
				$id = $id['id'];
			}
			if(!empty($id[$model]['id'])){
				$id = $id[$model]['id'];
			}
		}
		if(empty($id)) $id = $this->defID();
		if(!empty($this->params['Shop']['qtys'][$model][$id])){
			return $this->params['Shop']['qtys'][$model][$id];
		}
		return 0;
	}
	
	
	function cartUpdateScript($options = array()){
		$defOpt = array(
			'autoSubmit' => false,
			'timeout' => 4 * 1000,
		);
		$opt = array_merge($defOpt,$options);
		$this->Html->scriptBlock('
			(function( $ ) {
				'.($opt['autoSubmit'] && $opt['timeout'] ? 'var timeout;' : '').'
				function should_update(now,triggerer){
					'.(
						$opt['autoSubmit'] 
						? '	if(now){ 
								$("#ShopCartIndexForm").submit();
							}else{
								$("#ShopCartIndexForm .btUpdate").show();
								'.($opt['timeout'] ? '
								if(timeout) clearTimeout(timeout);
								timeout = setTimeout(function(){
									should_update(true);
								}, '.$opt['timeout'].');
								' : '').'
							}'
						: '$("#ShopCartIndexForm .btUpdate").show();'
					).'
				}
				$(function(){
					$("#ShopCartIndexForm .btUpdate").hide();
					$("body").delegate("#ShopCartIndexForm select","change",function(){
						should_update(true,this);
					});
					$("body").delegate("#ShopCartIndexForm input","keyup",function(){
						should_update(false,this);
					});
					$("body").delegate("#ShopCartIndexForm input","change",function(){
						should_update(true,this);
					});
				});
			})( jQuery );
		',array('inline'=>false));
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