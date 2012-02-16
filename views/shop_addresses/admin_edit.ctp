<div class="shopAddresses form">
	<?php echo $this->Form->create('ShopAddress');?>
		<fieldset>
			<legend><?php printf(__('Edit %s', true), __('Shop Address', true)); ?></legend>
			<?php
				echo $this->Form->input('active');
				echo $this->Form->input('id');
				echo $this->Form->input('address');
				echo $this->Form->input('apt');
				echo $this->Form->input('city');
				echo $this->Form->input('province');
				echo $this->Form->input('postal_code');
				echo $this->Form->input('country');
				echo $this->Form->input('phone');
			?>
		</fieldset>
	<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('ShopAddress.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('ShopAddress.id'))); ?></li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Shop Addresses', true)), array('action' => 'index'));?></li>
	</ul>
</div>