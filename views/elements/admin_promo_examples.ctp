
<?php $this->Html->script('/shop/js/fancybox/jquery.fancybox-1.3.4.pack',array('inline'=>false)); ?>
<?php $this->Html->css('/shop/js/fancybox/jquery.fancybox-1.3.4',null,array('inline'=>false)); ?>
<?php
	$this->Html->scriptBlock('
		(function( $ ) {
			$(function(){
				$(".btBehaviorhelp").fancybox()
			})
		})( jQuery );
	',array('inline'=>false));
?>
				<a href="#behaviorhelp" class="btBehaviorhelp"><?php __('Examples'); ?></a>
				<div class="hidepopup">
					<div id="behaviorhelp" class="popup helpPopup">
						<h2>Examples</h2>
						<h3>$2 reduction on all products</h3>
						<table>
							<tr class="odd">
								<th><?php __('Rebate'); ?></th>
								<td>-2</td>
							</tr>
							<tr class="even">
								<th><?php __('Operator'); ?></th>
								<td>Add</td>
							</tr>
							<tr class="odd">
								<th><?php __('Code'); ?></th>
								<td>(<?php __('Empty'); ?>)</td>
							</tr>
							<tr class="even">
								<th><?php __('Code needed'); ?></th>
								<td><?php __('No'); ?></td>
							</tr>
							<tr class="odd">
								<th><?php __('Qty coupons'); ?></th>
								<td>(<?php __('Empty'); ?>)</td>
							</tr>
							<tr class="even">
								<th><?php __('Individal coupons'); ?></th>
								<td><?php __('No'); ?></td>
							</tr>
							<tr class="odd">
								<th><?php __('Product'); ?></th>
								<td><?php __('All Products'); ?></td>
							</tr>
						</table>
						<h3>By entering the code "ABCDEFG" buyers will have a rabate of 15% on the product "Test1"</h3>
						<table>
							<tr class="odd">
								<th><?php __('Rebate'); ?></th>
								<td>0.85</td>
							</tr>
							<tr class="even">
								<th><?php __('Operator'); ?></th>
								<td><?php __('Multiply'); ?></td>
							</tr>
							<tr class="odd">
								<th><?php __('Code'); ?></th>
								<td>ABCDEFG</td>
							</tr>
							<tr class="even">
								<th><?php __('Code needed'); ?></th>
								<td><?php __('Yes'); ?></td>
							</tr>
							<tr class="odd">
								<th><?php __('Qty Coupons'); ?></th>
								<td>(<?php __('Empty'); ?>)</td>
							</tr>
							<tr class="even">
								<th><?php __('Individal coupons'); ?></th>
								<td><?php __('No'); ?></td>
							</tr>
							<tr class="odd">
								<th><?php __('Product'); ?></th>
								<td>Test1</td>
							</tr>
						</table>
						<h3>The first 50 Buyers will pay $50 for the product "Test1" instead of the usual price</h3>
						<table>
							<tr class="odd">
								<th><?php __('Rebate'); ?></th>
								<td>50</td>
							</tr>
							<tr class="even">
								<th><?php __('Operator'); ?></th>
								<td><?php __('Equal'); ?></td>
							</tr>
							<tr class="odd">
								<th><?php __('Code'); ?></th>
								<td>(<?php __('Empty'); ?>)</td>
							</tr>
							<tr class="even">
								<th><?php __('Code needed'); ?></th>
								<td><?php __('No'); ?></td>
							</tr>
							<tr class="odd">
								<th><?php __('Qty Coupons'); ?></th>
								<td>50</td>
							</tr>
							<tr class="even">
								<th><?php __('Individal coupons'); ?></th>
								<td><?php __('No'); ?></td>
							</tr>
							<tr class="odd">
								<th><?php __('Product'); ?></th>
								<td>Test1</td>
							</tr>
						</table>
						<h3>The first 50 Buyers that enters the code "ABCDEFG" will have a rabate of 15% on the product "Test1"</h3>
						<table>
							<tr class="odd">
								<th><?php __('Rebate'); ?></th>
								<td>0.85</td>
							</tr>
							<tr class="even">
								<th><?php __('Operator'); ?></th>
								<td><?php __('Multiply'); ?></td>
							</tr>
							<tr class="odd">
								<th><?php __('Code'); ?></th>
								<td>ABCDEFG</td>
							</tr>
							<tr class="even">
								<th><?php __('Code needed'); ?></th>
								<td><?php __('Yes'); ?></td>
							</tr>
							<tr class="odd">
								<th><?php __('Qty Coupons'); ?></th>
								<td>50</td>
							</tr>
							<tr class="even">
								<th><?php __('Individal coupons'); ?></th>
								<td><?php __('No'); ?></td>
							</tr>
							<tr class="odd">
								<th><?php __('Product'); ?></th>
								<td>Test1</td>
							</tr>
						</table>
						<h3>50 coupons will be created that give a $10 reduction on all products. Those coupons can be consulted once the promotion is created. They have a code the buyer needs to enter.</h3>
						<table>
							<tr class="odd">
								<th><?php __('Rebate'); ?></th>
								<td>-10</td>
							</tr>
							<tr class="even">
								<th><?php __('Operator'); ?></th>
								<td><?php __('Add'); ?></td>
							</tr>
							<tr class="odd">
								<th><?php __('Code'); ?></th>
								<td>(<?php __('Empty'); ?>)</td>
							</tr>
							<tr class="even">
								<th><?php __('Code needed'); ?></th>
								<td><?php __('No'); ?></td>
							</tr>
							<tr class="odd">
								<th><?php __('Qty Coupons'); ?></th>
								<td>50</td>
							</tr>
							<tr class="even">
								<th><?php __('Individal coupons'); ?></th>
								<td><?php __('Yes'); ?></td>
							</tr>
							<tr class="odd">
								<th><?php __('Product'); ?></th>
								<td><?php __('All Products'); ?></td>
							</tr>
						</table>
					</div>
				</div>