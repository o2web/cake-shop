<?php
	if(!empty($type) && !empty($type['element'])){
		echo $this->element($type['element']['name'],array_merge((array)$type['element']['option'],$type));
	}
?>