<div class="shopActions index">
	<?php
		echo $this->Form->create('Shop Action', array('class' => 'search', 'url' => array('action' => 'index')));
		echo $this->Form->input('q', array('class' => 'keyword', 'label' => false, 'after' => $form->submit(__('Search', true), array('div' => false))));
		echo $this->Form->end();
	?>	
	<h2><?php __('Shop Actions');?></h2>
	
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>			
			<th><?php echo $this->Paginator->sort('code');?></th>			
			<th><?php echo $this->Paginator->sort('status');?></th>			
			<th><?php echo $this->Paginator->sort('component');?></th>			
			<th><?php echo $this->Paginator->sort('function');?></th>			
			<th><?php echo $this->Paginator->sort('params');?></th>			
			<th><?php echo $this->Paginator->sort('form_element');?></th>			
			<th><?php echo $this->Paginator->sort('ui');?></th>			
			<th class="actions"><?php __('Actions');?></th>
		</tr>
		<?php
			$i = 0;
			$bool = array(__('No', true), __('Yes', true), null => __('No', true));
			foreach ($shopActions as $shopAction) {
				$class = null;
				if ($i++ % 2 == 0) {
					$class = ' class="altrow"';
				}
				?>
					<tr<?php echo $class;?>>
						<td class="id"><?php echo $shopAction['ShopAction']['id']; ?>&nbsp;</td>
						<td class="code"><?php echo $shopAction['ShopAction']['code']; ?>&nbsp;</td>
						<td class="status"><?php echo $shopAction['ShopAction']['status']; ?>&nbsp;</td>
						<td class="component"><?php echo $shopAction['ShopAction']['component']; ?>&nbsp;</td>
						<td class="function"><?php echo $shopAction['ShopAction']['function']; ?>&nbsp;</td>
						<td class="params"><?php echo $shopAction['ShopAction']['params']; ?>&nbsp;</td>
						<td class="form_element"><?php echo $shopAction['ShopAction']['form_element']; ?>&nbsp;</td>
						<td class="ui"><?php echo $text->truncate($shopAction['ShopAction']['ui'], 150, array('exact' => false)); ?>&nbsp;</td>
						<td class="actions">
							<?php echo $this->Html->link(__('Edit', true), array('action' => 'edit', $shopAction['ShopAction']['id']), array('class' => 'edit')); ?>
							<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $shopAction['ShopAction']['id']), array('class' => 'delete'), sprintf(__('Are you sure you want to delete # %s?', true), $shopAction['ShopAction']['id'])); ?>
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
		<li><?php echo $this->Html->link(sprintf(__('New %s', true), __('Shop Action', true)), array('action' => 'add')); ?></li>	</ul>
</div>