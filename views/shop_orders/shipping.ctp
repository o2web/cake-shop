<div class="shopOrders form">
	<?php echo $this->Form->create('ShopOrder');?>
		<fieldset>
			<legend><?php __d('shop','Shipping address'); ?></legend>
			<?php
				echo $form->input('id');
				echo $form->input('shipping_first_name');
				echo $form->input('shipping_last_name');
				echo $form->input('shipping_address');
				echo $form->input('shipping_apt');
				echo $form->input('shipping_city');
				echo $form->input('shipping_region');
				echo $form->input('shipping_country');
				echo $form->input('shipping_postal_code');
				echo $form->input('shipping_tel');
				echo $form->input('shipping_tel2');	
				echo $form->input('shipping_email');
			?>
		</fieldset>
	<?php echo $this->Form->end(__('Submit', true));?>
</div>