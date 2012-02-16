<div class="shopAddresses form">
	<?php echo $this->Form->create('ShopAddress');?>
		<fieldset>
			<legend><?php printf(__('Add %s', true), __('Shop Address', true)); ?></legend>
			<?php
				echo $this->Form->input('active', array('checked' => 'checked'));
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

		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Shop Addresses', true)), array('action' => 'index'));?></li>
	</ul>
</div>