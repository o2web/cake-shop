<?php
	////////// params //////////
	// $methodPath
	// $methods
	// $paramPath
	// $default
	// $label
	if(empty($default)){
		$default = null;
	}
?>
<div class="methodDef">
	<?php
		if(empty($default) || count($methods) > 1){
			$labels = array();
			foreach($methods as $key => $obj){
				$labels[$key] = __($obj->label,true);
			}
			$inputOpt = array('type'=>'select','label'=>$label,'default'=>'Shop.operation','class'=>'methodSelect','options'=>$labels);
			if(empty($default)){
				$inputOpt['empty'] = __('None',true);
			}else{
				$inputOpt['default'] = $default;
			}
		}else{
			$inputOpt = array('type'=>'hidden','value'=>$default);
		}
		echo $this->Form->input($methodPath,$inputOpt);
		$curMethod = null;
		if(!empty($methodPath) && Set::check($this->data,$methodPath)){
			$curMethod = Set::extract($methodPath,$this->data);
		}
		if(empty($curMethod) && !empty($default)){
			$curMethod = $default;
		}
		$prefix = $paramPath;
		if(!empty($curMethod)){
	?>
	<div class="<?php echo str_replace('.','',$curMethod); ?>MethodParams preloadedMethodParams">
		<?php
			$method = $methods[$curMethod];
			echo $method->editForm($prefix,$this);
		?>
	</div>
	<?php } ?>
	<div class="methodParams" field="<?php echo $prefix ?>">
		
	</div>
</div>