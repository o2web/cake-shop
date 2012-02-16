<div class="shopOrders form">
	<?php echo $this->Form->create('ShopOrder');?>
		<fieldset>
			<legend><?php printf(__('Edit %s', true), __('Shop Order', true)); ?></legend>
			<?php
				echo $this->Form->input('active');
				echo $this->Form->input('id');
				echo $this->Form->input('discount');
				echo $this->Form->input('discount_prc');
				echo $this->Form->input('sub_total');
				echo $this->Form->input('taxes');
				echo $this->Form->input('total_taxes');
				echo $this->Form->input('total');
				echo $this->Form->input('amount_paid');
				echo $this->Form->input('paid');
				echo $this->Form->input('owner_model');
				echo $this->Form->input('owner_foreign_id');
				echo $this->Form->input('billing_first_name');
				echo $this->Form->input('billing_last_name');
				echo $this->Form->input('billing_enterprise');
				echo $this->Form->input('billing_adress');
				echo $this->Form->input('billing_appart_num');
				echo $this->Form->input('billing_city');
				echo $this->Form->input('billing_region');
				echo $this->Form->input('billing_postal_code');
				echo $this->Form->input('billing_tel');
				echo $this->Form->input('billing_tel2');
				echo $this->Form->input('billing_email');
				echo $this->Form->input('shipping_first_name');
				echo $this->Form->input('shipping_last_name');
				echo $this->Form->input('shipping_enterprise');
				echo $this->Form->input('shipping_adress');
				echo $this->Form->input('shipping_appart_num');
				echo $this->Form->input('shipping_city');
				echo $this->Form->input('shipping_region');
				echo $this->Form->input('shipping_postal_code');
				echo $this->Form->input('shipping_tel');
				echo $this->Form->input('shipping_tel2');
				echo $this->Form->input('shipping_email');
				echo $this->Form->input('lock');
				echo $this->Form->input('ShopProduct');
				echo $this->Form->input('ShopPayment');
			?>
		</fieldset>
	<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('ShopOrder.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('ShopOrder.id'))); ?></li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Shop Orders', true)), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Shop Orders Items', true)), array('controller' => 'shop_orders_items', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Shop Orders Item', true)), array('controller' => 'shop_orders_items', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Shop Products', true)), array('controller' => 'shop_products', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Shop Product', true)), array('controller' => 'shop_products', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Shop Payments', true)), array('controller' => 'shop_payments', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Shop Payment', true)), array('controller' => 'shop_payments', 'action' => 'add')); ?> </li>
	</ul>
</div>