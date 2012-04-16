<?php

	$this->Html->script('/o2form/js/jquery.validate.min', array('inline'=>false)); 
	$this->Html->script('/o2form/js/jquery.maskedinput-1.3.min', array('inline'=>false)); 

	$this->Html->scriptBlock('
		(function( $ ) {
			$(function(){
				$("input.mask_phone").mask("(999) 999-9999");
				$("input.mask_postalcode").mask("a9a 9a9");
				
				
				$("#ShopOrderShippingBillingForm").validate({
							"rules": {
								
								"data[ShopOrder][shipping_first_name]" : "required",
								"data[ShopOrder][shipping_last_name]" : "required",
								"data[ShopOrder][shipping_address]" : "required",
								"data[ShopOrder][shipping_city]" : "required",
								"data[ShopOrder][shipping_region]" : "required",
								"data[ShopOrder][shipping_country]" : "required",
								"data[ShopOrder][shipping_postal_code]" : "required",
								"data[ShopOrder][shipping_tel]" : "required",
								"data[ShopOrder][shipping_email]" : {
									required: true,
									email: true
								},
								
								"data[ShopOrder][billing_first_name]" :{
									required : function(element){
										return $("#ShopOrderUseShipping").is(":not(:checked)");
									}
								},
								"data[ShopOrder][billing_last_name]" : {
									required : function(element){
										return $("#ShopOrderUseShipping").is(":not(:checked)");
									}
								},
								"data[ShopOrder][billing_address]" : {
									required : function(element){
										return $("#ShopOrderUseShipping").is(":not(:checked)");
									}
								},
								"data[ShopOrder][billing_city]" : {
									required : function(element){
										return $("#ShopOrderUseShipping").is(":not(:checked)");
									}
								},
								"data[ShopOrder][billing_region]" : {
									required : function(element){
										return $("#ShopOrderUseShipping").is(":not(:checked)");
									}
								},
								"data[ShopOrder][billing_country]" : {
									required : function(element){
										return $("#ShopOrderUseShipping").is(":not(:checked)");
									}
								},
								"data[ShopOrder][billing_postal_code]" : {
									required : function(element){
										return $("#ShopOrderUseShipping").is(":not(:checked)");
									}
								},
								"data[ShopOrder][billing_tel]" : {
									required : function(element){
										return $("#ShopOrderUseShipping").is(":not(:checked)");
									}
								},
								"data[ShopOrder][billing_email]" : {
									email: true,
									required : function(element){
										return $("#ShopOrderUseShipping").is(":not(:checked)");
									}
									
								},
								
							},
							"messages":{
								"data[ShopOrder][shipping_first_name]" : "* '.sprintf(__('%s obligatoire', true), __('Prénom', true)) .'",
								"data[ShopOrder][shipping_last_name]" : "* '.sprintf(__('%s obligatoire', true), __('Nom', true)) .'",
								"data[ShopOrder][shipping_address]" : "* '.sprintf(__('%s obligatoire', true), __('Adresse', true)) .'",
								"data[ShopOrder][shipping_city]" : "* '.sprintf(__('%s obligatoire', true), __('Ville', true)) .'",
								"data[ShopOrder][shipping_region]" : "* '.sprintf(__('%s obligatoire', true), __('Province / État', true)) .'",
								"data[ShopOrder][shipping_country]" : "* '.sprintf(__('%s obligatoire', true), __('Pays', true)) .'",
								"data[ShopOrder][shipping_postal_code]" : "* '.sprintf(__('%s obligatoire', true), __('Code Postal', true)) .'",
								"data[ShopOrder][shipping_tel]" : "* '.sprintf(__('%s obligatoire', true), __('Téléphone', true)) .'",
								"data[ShopOrder][shipping_email]" : "* '.sprintf(__('%s obligatoire', true), __('Courriel', true)) .'",
								
								"data[ShopOrder][billing_first_name]" : "* '.sprintf(__('%s obligatoire', true), __('Prénom', true)) .'",
								"data[ShopOrder][billing_last_name]" : "* '.sprintf(__('%s obligatoire', true), __('Nom', true)) .'",
								"data[ShopOrder][billing_address]" : "* '.sprintf(__('%s obligatoire', true), __('Adresse', true)) .'",
								"data[ShopOrder][billing_city]" : "* '.sprintf(__('%s obligatoire', true), __('Ville', true)) .'",
								"data[ShopOrder][billing_region]" : "* '.sprintf(__('%s obligatoire', true), __('Province / État', true)) .'",
								"data[ShopOrder][billing_country]" : "* '.sprintf(__('%s obligatoire', true), __('Pays', true)) .'",
								"data[ShopOrder][billing_postal_code]" : "* '.sprintf(__('%s obligatoire', true), __('Code Postal', true)) .'",
								"data[ShopOrder][billing_tel]" : "* '.sprintf(__('%s obligatoire', true), __('Téléphone', true)) .'",
								"data[ShopOrder][billing_email]" : "* '.sprintf(__('%s obligatoire', true), __('Courriel', true)) .'",
							},
							"errorElement": "span"
						});
			})
		})( jQuery );
	',array('inline'=>false));
	
	
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