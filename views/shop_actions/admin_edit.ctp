<div class="shopActions form">
	<?php echo $this->Form->create('ShopAction');?>
		<fieldset>
			<legend><?php printf(__('Edit %s', true), __('Shop Action', true)); ?></legend>
			<?php
				echo $this->Form->input('active');
				echo $this->Form->input('id');
				echo $this->Form->input('code');
				echo $this->Form->input('status');
				echo $this->Form->input('component');
				echo $this->Form->input('function');
				echo $this->Form->input('params');
				echo $this->Form->input('form_element');
				echo $this->Form->input('ui');
			?>
		</fieldset>
	<?php echo $this->Form->end(__('Submit', true));?>
</div>
<div class="actions">
	<ul>

		<li><?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('ShopAction.id')), null, sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('ShopAction.id'))); ?></li>
		<li><?php echo $this->Html->link(sprintf(__('List %s', true), __('Shop Actions', true)), array('action' => 'index'));?></li>
	</ul>
</div>