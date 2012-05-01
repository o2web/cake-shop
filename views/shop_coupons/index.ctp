<div class="shopCoupons index">
	<?php
		echo $this->Form->create('Shop Coupon', array('class' => 'search', 'url' => array('action' => 'index')));
		echo $this->Form->input('q', array('class' => 'keyword', 'label' => false, 'after' => $form->submit(__('Search', true), array('div' => false))));
		echo $this->Form->end();
	?>	
	<h2><?php __('Shop Coupons');?></h2>
	
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>			
			<th><?php echo $this->Paginator->sort('code');?></th>			
			<th><?php echo $this->Paginator->sort('shop_promotion_id');?></th>			
			<th><?php echo $this->Paginator->sort('shop_order_id');?></th>			
			<th><?php echo $this->Paginator->sort('status');?></th>			
			<th class="actions"><?php __('Actions');?></th>
		</tr>
		<?php
			$i = 0;
			$bool = array(__('No', true), __('Yes', true), null => __('No', true));
			foreach ($shopCoupons as $shopCoupon) {
				$class = null;
				if ($i++ % 2 == 0) {
					$class = ' class="altrow"';
				}
				?>
					<tr<?php echo $class;?>>
						<td class="id"><?php echo $shopCoupon['ShopCoupon']['id']; ?>&nbsp;</td>
						<td class="code"><?php echo $shopCoupon['ShopCoupon']['code']; ?>&nbsp;</td>
						<td>
							<?php echo $this->Html->link($shopCoupon['ShopPromotion']['code'], array('controller' => 'shop_promotions', 'action' => 'view', $shopCoupon['ShopPromotion']['id'])); ?>
						</td>
						<td>
							<?php echo $this->Html->link($shopCoupon['ShopOrder']['id'], array('controller' => 'shop_orders', 'action' => 'view', $shopCoupon['ShopOrder']['id'])); ?>
						</td>
						<td class="status"><?php echo $shopCoupon['ShopCoupon']['status']; ?>&nbsp;</td>
						<td class="actions">
							<?php echo $this->Html->link(__('View', true), array('action' => 'view', $shopCoupon['ShopCoupon']['id']), array('class' => 'view')); ?>
						</td>
					</tr>
				<?php
			}
		?>
	</table>
	
	<p class="paging">
		<?php
			echo $this->Paginator->counter(array(
				'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
			));
		?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('« '.__('previous', true), array(), null, array('class'=>'disabled'));?>
 |
		<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true).' »', array(), null, array('class' => 'disabled'));?>
	</div>
</div>
