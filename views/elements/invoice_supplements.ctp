<?php 
if(!isset($taxed)){
	$taxed = null;
}
if(!empty($supplements)){
	foreach($supplements as $supplementName => $supplement){  
		if($taxed === null || $taxed == !empty($supplement['tax_applied'])){
?>
		<tr class="totals <?php echo $supplementName ?>">
            <td colspan="4" class="title"><?php echo $supplement['label'].(!empty($supplement['descr'])?'<br />'.$supplement['descr']:''); ?></td>
            <td class="amount"><?php echo $this->Shop->currency($supplement['total']); ?></td>
        </tr>
<?php 	
		}
	}
} 
?>