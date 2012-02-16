<p><?php __d('shop','Pleaze choose a payment method.'); ?></p>
<?php
	if(!empty($types)){
		foreach($types as $typeName => $type) {
			echo $this->element($type['listElement']['name'],array_merge((array)$type['listElement']['option'],$type));
		}
	}else{
		__d('shop',"Please contact us to discuss of a payment method. Your payment's reference number is %s.");
	}
?>
