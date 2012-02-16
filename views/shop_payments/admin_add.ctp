<div class="shopPayments form">
	<?php echo $this->Form->create('ShopPayment');?>
		<fieldset>
			<legend><?php printf(__('Add %s', true), __('Shop Payment', true)); ?></legend>
			<?php
				echo $this->Form->input('active', array('checked' => 'checked'));
				echo $this->Form->input('type', array('options' => $types, 'default'=>'money'));
				foreach($this->data['ShopOrdersPayment'] as $key => $ordersPayment){
					echo $this->Form->input('ShopOrdersPayment.'.$key.'.order_id',array('type'=>'hidden'));
					echo $this->Form->input('ShopOrdersPayment.'.$key.'.amount',array('label'=>sprintf(__('Amount for order #%s', true), $ordersPayment['order_id'])));
				}
				echo $this->Form->input('status', array('options' => $status, 'default'=>'received'));
				echo $this->Form->input('comment');
			?>
		</fieldset>
	<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<ul>

		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Shop Payments', true)), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Shop Orders', true)), array('controller' => 'shop_orders', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Shop Order', true)), array('controller' => 'shop_orders', 'action' => 'add')); ?> </li>
	</ul>
</div>