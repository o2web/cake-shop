<div class="shopPayments index">
	<?php
		echo $this->Form->create('Shop Payment', array('class' => 'search', 'url' => array('action' => 'index')));
		echo $this->Form->input('q', array('class' => 'keyword', 'label' => false, 'after' => $form->submit(__('Search', true), array('div' => false))));
		echo $this->Form->end();
	?>	
	<h2><?php __('Shop Payments');?></h2>
	
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>			
			<th><?php echo $this->Paginator->sort('type');?></th>			
			<th><?php echo $this->Paginator->sort('amount');?></th>			
			<th><?php echo $this->Paginator->sort('status');?></th>			
			<th><?php echo $this->Paginator->sort('data');?></th>			
			<th><?php echo $this->Paginator->sort('lock');?></th>			
			<th class="actions"><?php __('Actions');?></th>
		</tr>
		<?php
			$i = 0;
			$bool = array(__('No', true), __('Yes', true));
			foreach ($shopPayments as $shopPayment) {
				$class = null;
				if ($i++ % 2 == 0) {
					$class = ' class="altrow"';
				}
				?>
					<tr<?php echo $class;?>>
						<td class="id"><?php echo $shopPayment['ShopPayment']['id']; ?>&nbsp;</td>
						<td class="type"><?php echo $shopPayment['ShopPayment']['type']; ?>&nbsp;</td>
						<td class="amount"><?php echo $shopPayment['ShopPayment']['amount']; ?>&nbsp;</td>
						<td class="status"><?php echo $shopPayment['ShopPayment']['status']; ?>&nbsp;</td>
						<td class="data"><?php echo $text->truncate($shopPayment['ShopPayment']['data'], 150, array('exact' => false)); ?>&nbsp;</td>
						<td class="lock"><?php echo $shopPayment['ShopPayment']['lock']; ?>&nbsp;</td>
						<td class="actions">
							<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $shopPayment['ShopPayment']['id']), array('class' => 'edit')); ?>
							<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $shopPayment['ShopPayment']['id']), array('class' => 'delete'), sprintf(__('Are you sure you want to delete # %s?', true), $shopPayment['ShopPayment']['id'])); ?>
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
<div class="actions">
	<ul>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Shop Payment', true)), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Shop Orders', true)), array('controller' => 'shop_orders', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Shop Order', true)), array('controller' => 'shop_orders', 'action' => 'add')); ?> </li>
	</ul>
</div>