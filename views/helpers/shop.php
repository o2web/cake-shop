<?php
class ShopHelper extends AppHelper {

	var $helpers = array('Number','Form','O2form.O2form');
	
	var $currencyFormats = array(
		'fre' => array('before'=>false, 'after'=>' $', 'thousands' => ' ', 'decimals'=>',', 'places'=>2),
		'eng'=> array('before'=>'$', 'thousands' => ',', 'decimals'=>'.', 'places'=>2)
	);
	
	function beforeRender(){
		/*foreach( $this->currencyFormats as $formatName => $options ){
			$this->Number->addFormat($formatName, $options);
		}*/
		
		parent::beforeRender();
	}
	
	function addFormat( $formatName, $options ){
		$this->currencyFormats[$formatName] = $options;
		//$this->Number->addFormat($formatName, $options);
	}
	
	function editForm($model = null,$options = array()){
		App::import('Lib', 'Shop.ShopConfig');
		$config = ShopConfig::load();
		
		if(is_array($model)){
			$options = $model;
		}elseif(!empty($model)){
			$options['model'] = $model;
		}
		$defOpt = array(
			'model' => $this->model(),
			'fieldset' => true,
			'legend' => __('Shop',true),
		);
		$opt = array_merge($defOpt,$options);
		
		$html = '';
		if($opt['fieldset']){
			$html .= '<fieldset>'."\n";
			$html .= '	<legend>'.$opt['legend'].'</legend>'."\n";
		}
		$html .= '	'.$this->Form->input('ShopProduct.price')."\n";
		
		$types = ShopConfig::getSubProductTypes();
		if(empty($this->data['ShopSubproduct']) && !empty($this->data['ShopProduct']['ShopSubproduct'])){
			App::import('Lib', 'SetMulti');
			$this->data['ShopSubproduct'] = $this->data['ShopProduct']['ShopSubproduct'];
			$this->O2form->data['ShopSubproduct'] = $this->data['ShopSubproduct'];
			$this->Form->data['ShopSubproduct'] = $this->data['ShopSubproduct'];
		}
		if(!empty($types)){
			$html .= '	<div class="shopSubProductSelector">'."\n";
			$html .= '		<p class="label">'.__('subProduct',true).'</p>'."\n";
			foreach($types as $key =>$type){
				$fields = set::normalize(array('id','code','label_fre','label_eng', 'operator','price'));
				if(count($type['operators']==1)){
					$fields['operator']['type'] = 'hidden';
					$fields['operator']['value'] = $type['operators'][0];
				}else{
					$fields['operator']['options'] = $type['operators'];
				}
				if(isset($type['adminFields'])){
					$fields = set::merge($fields,$type['adminFields']);
				}
				$html .= $this->O2form->input('ShopSubproduct.'.$key,array('type'=>'multiple','fields'=>$fields,'div'=>array('class'=>'type')));
			}
			
			$html .= '	<div>'."\n";
		}
		
		
		if($opt['fieldset']){
			$html .= '</fieldset>'."\n";
		}
		return $html;
	}
	
	function currency($number){
		$currency = Configure::read('Shop.currency');
		$lang = Configure::read('Config.language');
		$find = array();
		if(!empty($currency)){
			$find[] = $currency.'-'.$lang;
			$find[] = $currency;
		}
		$find[] = $lang;
		
		App::import('Lib', 'Shop.SetMulti');
		$cur = SetMulti::extractHierarchic($find,array_combine(array_keys($this->currencyFormats),array_keys($this->currencyFormats)));
		return $this->Number->format($number,$this->currencyFormats[$cur]);
	}
	
	function fullPrice($product=null,$options=array()){
		if(is_array($product) && empty($product['ShopProduct']) &&  empty($product[0]['ShopProduct'])){
			$options = $product;
		}else{
			if(!empty($product)){
				$options['product'] = $product;
			}
		}
		$defOpt = array(
			'product'=>null,
			'sources'=> array('product','viewVars.product','viewVars.product'),
			'paths'=>array(
				'original_price' => array(
					'ShopProduct.DynamicField.original_price','DynamicField.original_price','item_original_price',
				),
				'rebate' => array(
					'ShopProduct.DynamicField.rebate','DynamicField.rebate','item_rebate',
				),
				'price' => array(
					'ShopProduct.DynamicField.price','DynamicField.price','item_price',
				),
			),
			'dataOnly' => false,
		);
		$opt = array_merge($defOpt,$options);
		$view =& ClassRegistry::getObject('view');
		$source = array('product'=>$opt['product'], 'viewVars'=>$view->viewVars, 'params'=>$this->params);
		$extract_data = array();
		foreach ($opt['paths'] as $prop_name => $paths) {
			foreach ($opt['sources'] as $sname) {
				foreach ($paths as $path) {
					$extract_data[$prop_name][] = $sname.'.'.$path;
				}
			}
		}
		App::import('Lib', 'Shop.SetMulti');
		$data = SetMulti::extractHierarchicMulti($extract_data,$source,array('extractNull'=>false));
		//debug($data);
		if($opt['dataOnly']){
			return $data;
		}else{
			return $view->element('qualified_price',array('plugin'=>'shop','fullPrice'=>$data));
		}
	}
	
}

?>