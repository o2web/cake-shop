<?php
class ShopHelper extends AppHelper {

	var $helpers = array('Number','Form');
	
	var $currencyFormats = array(
		'fre' => array('before'=>false, 'after'=>' $', 'thousands' => ' ', 'decimals'=>',', 'places'=>2),
		'eng'=> array('before'=>'$ ', 'thousands' => ',', 'decimals'=>'.', 'places'=>2)
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
	
	function editForm($model,$options = array()){
		App::import('Lib', 'Shop.ShopConfig');
		$config = ShopConfig::load();
		
		if(is_array($model)){
			$options = $model;
		}else{
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
		
		if(!empty($types)){
			$html .= '	<div class="shopSubProductSelector">'."\n";
			$html .= '		<p class="label">'.__('subProduct',true).'</p>'."\n";
			foreach($types as $key =>$type){
				$html .= '		<div class="type">'."\n";
				$html .= '			<p class="title">'.$type['label'].'</p>'."\n";
				$html .= '			<a href="#" class="btAdd">+</a>'."\n";
				$html .= '			<table class="subItems" cellspacing="0" cellpadding="0">'."\n";
				$html .= '				<tr>'."\n";
				$html .= '					<th>'.__('Code',true).'</th>'."\n";
				$html .= '					<th>'.__('Label',true).'</th>'."\n";
				if(count($type['operators'])>1){
					$html .= '					<th>'.__('Operator',true).'</th>'."\n";
				}
				$html .= '					<th>'.__('Price',true).'</th>'."\n";
				$html .= '					<th>'.__('Delete',true).'</th>'."\n";
				$html .= '				</tr>'."\n";
				$html .= '				<tr>'."\n";
				$html .= '					<td>'."\n";
				$html .= '						'.$this->Form->input('SubProduct.'.$key.'.code',array('div'=>false,'label'=>false))."\n";
				$html .= '					</td>'."\n";
				$html .= '					<td>'."\n";
				$html .= '						'.$this->Form->input('SubProduct.'.$key.'.label',array('div'=>false,'label'=>false))."\n";
				$html .= '					</td>'."\n";
				if(count($type['operators'])>1){
					$html .= '					<td>'."\n";
					$html .= '						'.$this->Form->input('SubProduct.'.$key.'.operator',array('options'=>$type['operators'],'div'=>false,'label'=>false))."\n";
					$html .= '					</td>'."\n";
				}
				$html .= '					<td>'."\n";
				$html .= '						'.$this->Form->input('SubProduct.'.$key.'.price',array('div'=>false,'label'=>false))."\n";
				$html .= '					</td>'."\n";
				$html .= '					<td>'."\n";
				if(count($type['operators']==1)){
					$html .= '						'.$this->Form->input('SubProduct.'.$key.'.operator',array('type'=>'hidden','value'=>$type['operators'][0]))."\n";
				}
				$html .= '						<a href="#" class="btDelete">-</a>'."\n";
				$html .= '					</td>'."\n";
				$html .= '				</tr>'."\n";
				$html .= '			</table>'."\n";
				$html .= '		</div>'."\n";
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
	
	
}

?>