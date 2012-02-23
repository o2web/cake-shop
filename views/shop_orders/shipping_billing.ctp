<?php
	$this->Html->scriptBlock('
		(function( $ ) {
			$(function(){
				$("#ShopOrderShippingBillingForm #ShopOrderUseBilling").click(function(){
					ShippingBillingUpdateSection();
				});
				$("#ShopOrderShippingBillingForm #ShopOrderUseShipping").click(function(){
					ShippingBillingUpdateSection();
				});
				ShippingBillingUpdateSection();
			})
			window.ShippingBillingUpdateSection = function(){
				var $checkbox = $("#ShopOrderShippingBillingForm #ShopOrderUseBilling");
				if($checkbox.length)
				if($checkbox.is(":checked")){
					$("#ShopOrderShippingBillingForm div.shipping_info").hide();
				} else{
					$("#ShopOrderShippingBillingForm div.shipping_info").show();
				}
				var $checkbox = $("#ShopOrderShippingBillingForm #ShopOrderUseShipping");
				if($checkbox.length)
				if($checkbox.is(":checked")){
					$("#ShopOrderShippingBillingForm div.billing_info").hide();
				} else{
					$("#ShopOrderShippingBillingForm div.billing_info").show();
				}
			}
		})( jQuery );
	',array('inline'=>false));
?>
<div class="shopOrders form">
	<?php echo $this->Form->create('ShopOrder');?>
		<fieldset>
			<legend><?php __d('shop','Shipping address'); ?></legend>
			<?php
				echo $form->input('id');
			?>
			<div class="shipping_info">
			<?php
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
			</div>
		</fieldset>
		<fieldset>
			<legend><?php __d('shop','Billing address'); ?></legend>
			<?php
				echo $form->input('use_shipping', array('type'=>'checkbox', 'label'=>__('Use shipping informations', true))); 
			?>
			<div class="billing_info">
			<?php
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
			</div>
		</fieldset>
	<?php echo $this->Form->end(__('Submit', true));?>
</div>