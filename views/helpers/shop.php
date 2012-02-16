<?php
class ShopHelper extends AppHelper {

	var $helpers = array('Number');
	
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