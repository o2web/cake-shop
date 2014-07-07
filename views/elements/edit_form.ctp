<?php if($opt['fieldset']){ ?>
<fieldset>
	<legend><?php echo $opt['legend'] ?></legend>
<?php } ?>
<?php
	$fOpt = array();
	if(!empty($currencies)){
		$fOpt['label'] = __('Default Price',true);
	}
	echo '	'.$this->Form->input('ShopProduct.price',$fOpt)."\n";
	
	if(!empty($currencies)){
		foreach($currencies as $currency){
		;
			$fOpt = array(
				'label' => str_replace('%currency%',$currency,__('Price %currency%',true)),
				'type' => 'text',
			);
			echo '	'.$this->Form->input('ShopProduct.currency_prices.'.$currency,$fOpt)."\n";
		}
	}
 
	if(!empty($typeFields)){
?>
		<div class="shopSubProductSelector">
			<p class="label"><?php __('subProduct') ?></p>
			<?php
			foreach($typeFields as $key =>$options){
				echo '				'.$this->O2form->input($key,$options)."\n";
			}
			?>
		
		<div>
<?php 
	}
?>		
		
<?php if($opt['fieldset']){ ?>
</fieldset>
<?php } ?>