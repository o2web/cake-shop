<div class="shopAddresses index">
	<?php
		echo $this->Form->create('Shop Address', array('class' => 'search', 'url' => array('action' => 'index')));
		echo $this->Form->input('q', array('class' => 'keyword', 'label' => false, 'after' => $form->submit(__('Search', true), array('div' => false))));
		echo $this->Form->end();
	?>	
	<h2><?php __('Shop Addresses');?></h2>
	
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>			
			<th><?php echo $this->Paginator->sort('address');?></th>			
			<th><?php echo $this->Paginator->sort('apt');?></th>			
			<th><?php echo $this->Paginator->sort('city');?></th>			
			<th><?php echo $this->Paginator->sort('province');?></th>			
			<th><?php echo $this->Paginator->sort('postal_code');?></th>			
			<th><?php echo $this->Paginator->sort('country');?></th>			
			<th><?php echo $this->Paginator->sort('phone');?></th>			
			<th class="actions"><?php __('Actions');?></th>
		</tr>
		<?php
			$i = 0;
			$bool = array(__('No', true), __('Yes', true));
			foreach ($shopAddresses as $shopAddress) {
				$class = null;
				if ($i++ % 2 == 0) {
					$class = ' class="altrow"';
				}
				?>
					<tr<?php echo $class;?>>
						<td class="id"><?php echo $shopAddress['ShopAddress']['id']; ?>&nbsp;</td>
						<td class="address"><?php echo $shopAddress['ShopAddress']['address']; ?>&nbsp;</td>
						<td class="apt"><?php echo $shopAddress['ShopAddress']['apt']; ?>&nbsp;</td>
						<td class="city"><?php echo $shopAddress['ShopAddress']['city']; ?>&nbsp;</td>
						<td class="province"><?php echo $shopAddress['ShopAddress']['province']; ?>&nbsp;</td>
						<td class="postal_code"><?php echo $shopAddress['ShopAddress']['postal_code']; ?>&nbsp;</td>
						<td class="country"><?php echo $shopAddress['ShopAddress']['country']; ?>&nbsp;</td>
						<td class="phone"><?php echo $shopAddress['ShopAddress']['phone']; ?>&nbsp;</td>
						<td class="actions">
							<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $shopAddress['ShopAddress']['id']), array('class' => 'edit')); ?>
							<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $shopAddress['ShopAddress']['id']), array('class' => 'delete'), sprintf(__('Are you sure you want to delete # %s?', true), $shopAddress['ShopAddress']['id'])); ?>
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
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Shop Address', true)), array('action' => 'add')); ?></li>
	</ul>
</div>