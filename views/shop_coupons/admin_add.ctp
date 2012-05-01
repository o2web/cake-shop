<div class="shopCoupons form">
	<?php echo $this->Form->create('ShopCoupon');?>
		<fieldset>
			<legend><?php printf(__('Add %s', true), __('Shop Coupon', true)); ?></legend>
			<?php
				echo $this->Form->input('active', array('checked' => 'checked'));
				echo $this->Form->input('code');
				echo $this->Form->input('shop_promotion_id');
				echo $this->Form->input('shop_order_id');
				echo $this->Form->input('status');
			?>
		</fieldset>
	<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<ul>

		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Shop Coupons', true)), array('action' => 'index'));?></li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Shop Promotions', true)), array('controller' => 'shop_promotions', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Shop Promotion', true)), array('controller' => 'shop_promotions', 'action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Shop Orders', true)), array('controller' => 'shop_orders', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Shop Order', true)), array('controller' => 'shop_orders', 'action' => 'add')); ?> </li>
	</ul>
</div>