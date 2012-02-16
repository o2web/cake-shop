<div class="shopTaxes form">
	<?php echo $this->Form->create('ShopTax');?>
		<fieldset>
			<legend><?php printf(__('Add %s', true), __('Shop Tax', true)); ?></legend>
			<?php
				echo $this->Form->input('active', array('checked' => 'checked'));
				echo $this->Form->input('country');
				echo $this->Form->input('province');
				echo $this->Form->input('name');
				echo $this->Form->input('rate');
				echo $this->Form->input('apply_prev');
				echo $this->Form->input('default');
			?>
		</fieldset>
	<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<ul>

		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Shop Taxes', true)), array('action' => 'index'));?></li>
	</ul>
</div>