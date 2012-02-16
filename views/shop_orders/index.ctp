<div class="shopOrders index">
	<?php
		echo $this->Form->create('Shop Order', array('class' => 'search', 'url' => array('action' => 'index')));
		echo $this->Form->input('q', array('class' => 'keyword', 'label' => false, 'after' => $form->submit(__('Search', true), array('div' => false))));
		echo $this->Form->end();
	?>	
	<h2><?php __('Shop Orders');?></h2>
	
	<table cellpadding="0" cellspacing="0">
		<tr>
			<th><?php echo $this->Paginator->sort('id');?></th>			
			<th><?php echo $this->Paginator->sort('discount');?></th>			
			<th><?php echo $this->Paginator->sort('discount_prc');?></th>			
			<th><?php echo $this->Paginator->sort('sub_total');?></th>			
			<th><?php echo $this->Paginator->sort('taxes');?></th>			
			<th><?php echo $this->Paginator->sort('total_taxes');?></th>			
			<th><?php echo $this->Paginator->sort('total');?></th>			
			<th><?php echo $this->Paginator->sort('amount_paid');?></th>			
			<th><?php echo $this->Paginator->sort('paid');?></th>			
			<th><?php echo $this->Paginator->sort('owner_model');?></th>			
			<th><?php echo $this->Paginator->sort('owner_foreign_id');?></th>			
			<th><?php echo $this->Paginator->sort('billing_first_name');?></th>			
			<th><?php echo $this->Paginator->sort('billing_last_name');?></th>			
			<th><?php echo $this->Paginator->sort('billing_enterprise');?></th>			
			<th><?php echo $this->Paginator->sort('billing_adress');?></th>			
			<th><?php echo $this->Paginator->sort('billing_appart_num');?></th>			
			<th><?php echo $this->Paginator->sort('billing_city');?></th>			
			<th><?php echo $this->Paginator->sort('billing_region');?></th>			
			<th><?php echo $this->Paginator->sort('billing_postal_code');?></th>			
			<th><?php echo $this->Paginator->sort('billing_tel');?></th>			
			<th><?php echo $this->Paginator->sort('billing_tel2');?></th>			
			<th><?php echo $this->Paginator->sort('billing_email');?></th>			
			<th><?php echo $this->Paginator->sort('shipping_first_name');?></th>			
			<th><?php echo $this->Paginator->sort('shipping_last_name');?></th>			
			<th><?php echo $this->Paginator->sort('shipping_enterprise');?></th>			
			<th><?php echo $this->Paginator->sort('shipping_adress');?></th>			
			<th><?php echo $this->Paginator->sort('shipping_appart_num');?></th>			
			<th><?php echo $this->Paginator->sort('shipping_city');?></th>			
			<th><?php echo $this->Paginator->sort('shipping_region');?></th>			
			<th><?php echo $this->Paginator->sort('shipping_postal_code');?></th>			
			<th><?php echo $this->Paginator->sort('shipping_tel');?></th>			
			<th><?php echo $this->Paginator->sort('shipping_tel2');?></th>			
			<th><?php echo $this->Paginator->sort('shipping_email');?></th>			
			<th><?php echo $this->Paginator->sort('lock');?></th>			
			<th class="actions"><?php __('Actions');?></th>
		</tr>
		<?php
			$i = 0;
			$bool = array(__('No', true), __('Yes', true));
			foreach ($shopOrders as $shopOrder) {
				$class = null;
				if ($i++ % 2 == 0) {
					$class = ' class="altrow"';
				}
				?>
					<tr<?php echo $class;?>>
						<td class="id"><?php echo $shopOrder['ShopOrder']['id']; ?>&nbsp;</td>
						<td class="discount"><?php echo $shopOrder['ShopOrder']['discount']; ?>&nbsp;</td>
						<td class="discount_prc"><?php echo $shopOrder['ShopOrder']['discount_prc']; ?>&nbsp;</td>
						<td class="sub_total"><?php echo $shopOrder['ShopOrder']['sub_total']; ?>&nbsp;</td>
						<td class="taxes"><?php echo $text->truncate($shopOrder['ShopOrder']['taxes'], 150, array('exact' => false)); ?>&nbsp;</td>
						<td class="total_taxes"><?php echo $shopOrder['ShopOrder']['total_taxes']; ?>&nbsp;</td>
						<td class="total"><?php echo $shopOrder['ShopOrder']['total']; ?>&nbsp;</td>
						<td class="amount_paid"><?php echo $shopOrder['ShopOrder']['amount_paid']; ?>&nbsp;</td>
						<td class="paid"><?php echo $bool[$shopOrder['ShopOrder']['paid']]; ?>&nbsp;</td>
						<td class="owner_model"><?php echo $shopOrder['ShopOrder']['owner_model']; ?>&nbsp;</td>
						<td class="owner_foreign_id"><?php echo $shopOrder['ShopOrder']['owner_foreign_id']; ?>&nbsp;</td>
						<td class="billing_first_name"><?php echo $shopOrder['ShopOrder']['billing_first_name']; ?>&nbsp;</td>
						<td class="billing_last_name"><?php echo $shopOrder['ShopOrder']['billing_last_name']; ?>&nbsp;</td>
						<td class="billing_enterprise"><?php echo $shopOrder['ShopOrder']['billing_enterprise']; ?>&nbsp;</td>
						<td class="billing_adress"><?php echo $text->truncate($shopOrder['ShopOrder']['billing_adress'], 150, array('exact' => false)); ?>&nbsp;</td>
						<td class="billing_appart_num"><?php echo $shopOrder['ShopOrder']['billing_appart_num']; ?>&nbsp;</td>
						<td class="billing_city"><?php echo $shopOrder['ShopOrder']['billing_city']; ?>&nbsp;</td>
						<td class="billing_region"><?php echo $shopOrder['ShopOrder']['billing_region']; ?>&nbsp;</td>
						<td class="billing_postal_code"><?php echo $shopOrder['ShopOrder']['billing_postal_code']; ?>&nbsp;</td>
						<td class="billing_tel"><?php echo $shopOrder['ShopOrder']['billing_tel']; ?>&nbsp;</td>
						<td class="billing_tel2"><?php echo $shopOrder['ShopOrder']['billing_tel2']; ?>&nbsp;</td>
						<td class="billing_email"><?php echo $shopOrder['ShopOrder']['billing_email']; ?>&nbsp;</td>
						<td class="shipping_first_name"><?php echo $shopOrder['ShopOrder']['shipping_first_name']; ?>&nbsp;</td>
						<td class="shipping_last_name"><?php echo $shopOrder['ShopOrder']['shipping_last_name']; ?>&nbsp;</td>
						<td class="shipping_enterprise"><?php echo $shopOrder['ShopOrder']['shipping_enterprise']; ?>&nbsp;</td>
						<td class="shipping_adress"><?php echo $text->truncate($shopOrder['ShopOrder']['shipping_adress'], 150, array('exact' => false)); ?>&nbsp;</td>
						<td class="shipping_appart_num"><?php echo $shopOrder['ShopOrder']['shipping_appart_num']; ?>&nbsp;</td>
						<td class="shipping_city"><?php echo $shopOrder['ShopOrder']['shipping_city']; ?>&nbsp;</td>
						<td class="shipping_region"><?php echo $shopOrder['ShopOrder']['shipping_region']; ?>&nbsp;</td>
						<td class="shipping_postal_code"><?php echo $shopOrder['ShopOrder']['shipping_postal_code']; ?>&nbsp;</td>
						<td class="shipping_tel"><?php echo $shopOrder['ShopOrder']['shipping_tel']; ?>&nbsp;</td>
						<td class="shipping_tel2"><?php echo $shopOrder['ShopOrder']['shipping_tel2']; ?>&nbsp;</td>
						<td class="shipping_email"><?php echo $shopOrder['ShopOrder']['shipping_email']; ?>&nbsp;</td>
						<td class="lock"><?php echo $shopOrder['ShopOrder']['lock']; ?>&nbsp;</td>
						<td class="actions">
							<?php echo $this->Html->link(__('View', true), array('action' => 'view', $shopOrder['ShopOrder']['id']), array('class' => 'view')); ?>
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
