<?php $this->Form->create('ShopPromotion');
if(method_exists ($method,'editForm')){
	echo $method->editForm($prefix,$this);
}
$this->Form->end();