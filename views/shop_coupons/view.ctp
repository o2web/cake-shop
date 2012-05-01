<div class="shopCoupons view">
<h2><?php  __('Shop Coupon');?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Id'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $shopCoupon['ShopCoupon']['id']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Code'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $shopCoupon['ShopCoupon']['code']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Shop Promotion'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($shopCoupon['ShopPromotion']['code'], array('controller' => 'shop_promotions', 'action' => 'view', $shopCoupon['ShopPromotion']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Shop Order'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $this->Html->link($shopCoupon['ShopOrder']['id'], array('controller' => 'shop_orders', 'action' => 'view', $shopCoupon['ShopOrder']['id'])); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Status'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $shopCoupon['ShopCoupon']['status']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Active'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $shopCoupon['ShopCoupon']['active']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $shopCoupon['ShopCoupon']['created']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php __('Modified'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class;?>>
			<?php echo $shopCoupon['ShopCoupon']['modified']; ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Shop Coupons', true)), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Shop Promotions', true)), array('controller' => 'shop_promotions', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Shop Orders', true)), array('controller' => 'shop_orders', 'action' => 'index')); ?> </li>
	</ul>
</div>
