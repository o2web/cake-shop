<div class="shopOrders form">
	<?php echo $this->Form->create('ShopOrder');?>
		<fieldset>
			<legend><?php __d('shop','Billing address'); ?></legend>
			<?php
				echo $form->input('id');
				echo $form->input('billing_first_name');
				echo $form->input('billing_last_name');
				echo $form->input('billing_address');
				echo $form->input('billing_apt');
				echo $form->input('billing_city');
				echo $form->input('billing_region');
				echo $form->input('billing_country');
				echo $form->input('billing_postal_code');
				echo $form->input('billing_tel');
				echo $form->input('billing_tel2');	
				echo $form->input('billing_email');
			?>
		</fieldset>
	<?php echo $this->Form->end(__('Submit', true));?>
</div>