<?php
class BonusQtyPromo extends PromoMethod {
	var $label = "X free For X bought";
	
	function isAvailable(){
		return false;
	}
	
}