<?php 
if(!empty($address)){
	if(empty($prefix)){
		$prefix = '';
	}
	echo implode(' ',array_filter(array($address[$prefix.'first_name'],$address[$prefix.'last_name'])));
	if(!empty($address[$prefix.'occupation'])){ 
		echo ', '.$address[$prefix.'occupation_fre'];
		if(!empty($address[$prefix.'enterprise'])){ 
			echo ', '.$address[$prefix.'enterprise'];
		}
	}
	echo "<br />\n";
	if(!empty($address[$prefix.'address'])){
		echo implode(', # ',array_filter(array($address[$prefix.'address'],$address[$prefix.'apt'])))."<br />\n";
		echo $address[$prefix.'city'].' ('.$address[$prefix.'region'].') '.$address[$prefix.'postal_code']."<br />\n";
		echo $address[$prefix.'country']."<br />\n";
	}
	echo "<br />\n";
	echo implode("<br />\n",array_filter(array($address[$prefix.'tel'],$address[$prefix.'tel2'],$address[$prefix.'email']))); 
}
?>