<?php
class PromoMethod extends Object {
	var $params;
	var $promo;
	
	function __construct($promo = null,$params = null) {
		$this->promoData = $promo;
		$this->params = $params;
		if(empty($this->label)){
			$label = get_class($this);
			$label = preg_replace('/Promo$/','',$label);
			$this->label = Inflector::humanize(Inflector::underscore($label));
		}
	}
}