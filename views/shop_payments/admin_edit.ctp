<div class="shopPayments form">
	<?php echo $this->Form->create('ShopPayment');?>
		<fieldset>
			<legend><?php printf(__('Edit %s', true), __('Shop Payment', true)); ?></legend>
			<?php
				echo $this->Form->input('active');
				echo $this->Form->input('id');
				echo $this->Form->input('type');
				echo $this->Form->input('amount');
				echo $this->Form->input('status');
				echo $this->Form->input('data');
				echo $this->Form->input('lock');
				echo $this->Form->input('ShopOrder');
			?>
		</fieldset>
	<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('ShopPayment.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('ShopPayment.id'))); ?></li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Shop Payments', true)), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Shop Orders', true)), array('controller' => 'shop_orders', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Shop Order', true)), array('controller' => 'shop_orders', 'action' => 'add')); ?> </li>
	</ul>
</div>