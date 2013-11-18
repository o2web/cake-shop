<?php 
if(!isset($taxed)) 			$taxed = null;
if(!isset($indent)) 		$indent = 3;
if(!isset($titleClass)) 	$titleClass = 'title';
if(!isset($amountClass)) 	$amountClass = 'amount';
if(!isset($rowClass)) 		$rowClass = 'totals';


if(!empty($supplements)){
	foreach($supplements as $supplementName => $supplement){  
		if($taxed === null || $taxed == !empty($supplement['tax_applied'])){
?>
		<tr class="<?php echo $rowClass.' '.$supplementName ?>">
            <td colspan="<?php echo $indent+1 ?>" class="<?php echo $titleClass ?>"><?php echo $supplement['label'].(!empty($supplement['descr'])?'<br />'.$supplement['descr']:''); ?></td>
            <td class="<?php echo $amountClass ?>"><?php echo $this->Shop->currency($supplement['total']); ?></td>
        </tr>
<?php 	
		}
	}
} 
?>