<div class="shopTaxes index">
	<?php
		echo $this->Form->create('Shop Tax', array('class' => 'search', 'url' => array('action' => 'index')));
		echo $this->Form->input('q', array('class' => 'keyword', 'label' => false, 'after' => $form->submit(__('Search', true), array('div' => false))));
		echo $this->Form->end();
	?>	
	<h2><?php __('Shop Taxes');?></h2>
	
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>			
			<th><?php echo $this->Paginator->sort('country');?></th>			
			<th><?php echo $this->Paginator->sort('province');?></th>			
			<th><?php echo $this->Paginator->sort('name');?></th>			
			<th><?php echo $this->Paginator->sort('rate');?></th>			
			<th><?php echo $this->Paginator->sort('apply_prev');?></th>			
			<th><?php echo $this->Paginator->sort('default');?></th>			
			<th class="actions"><?php __('Actions');?></th>
		</tr>
		<?php
			$i = 0;
			$bool = array(__('No', true), __('Yes', true));
			foreach ($shopTaxes as $shopTax) {
				$class = null;
				if ($i++ % 2 == 0) {
					$class = ' class="altrow"';
				}
				?>
					<tr<?php echo $class;?>>
						<td class="id"><?php echo $shopTax['ShopTax']['id']; ?>&nbsp;</td>
						<td class="country"><?php echo $shopTax['ShopTax']['country']; ?>&nbsp;</td>
						<td class="province"><?php echo $shopTax['ShopTax']['province']; ?>&nbsp;</td>
						<td class="name"><?php echo $shopTax['ShopTax']['name']; ?>&nbsp;</td>
						<td class="rate"><?php echo $shopTax['ShopTax']['rate']; ?>&nbsp;</td>
						<td class="apply_prev"><?php echo $bool[$shopTax['ShopTax']['apply_prev']]; ?>&nbsp;</td>
						<td class="default"><?php echo $bool[$shopTax['ShopTax']['default']]; ?>&nbsp;</td>
						<td class="actions">
							<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $shopTax['ShopTax']['id']), array('class' => 'edit')); ?>
							<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $shopTax['ShopTax']['id']), array('class' => 'delete'), sprintf(__('Are you sure you want to delete # %s?', true), $shopTax['ShopTax']['id'])); ?>
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
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Shop Tax', true)), array('action' => 'add')); ?></li>
	</ul>
</div>