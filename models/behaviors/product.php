<?php
class ProductBehavior extends ModelBehavior {
	
	var $defaultSettings = array(
		'needed_data'=>null
	);
	
	function setup(&$Model, $settings) {
		if (!isset($this->settings[$Model->alias])) {
			$this->settings[$Model->alias] = $this->defaultSettings;
		}
		$this->settings[$Model->alias] = array_merge($this->settings[$Model->alias], (array)$settings);
		if(empty($Model->productOptions)){
			$Model->productOptions = array();
		}
		$Model->productOptions = array_merge($Model->productOptions,$this->settings[$Model->alias]);
		
		$Model->bindModel(
			array('hasOne' => array(
					'ShopProduct' => array(
						'className'    => 'Shop.ShopProduct',
						'foreignKey'   => 'foreign_id',
						'conditions'   => array('ShopProduct.model' => $Model->alias),
						'dependent'    => true
					)
				)
			)
		,false);

	}
	
	function afterSave(&$model, $created) {
		if(!empty($model->data['ShopProduct'])){
			$data = $model->data['ShopProduct'];
		}
		$data['model'] = $model->alias;
		$data['foreign_id'] = $model->id;
		if(isset($model->data[$model->alias]['active'])){
			$data['active'] = $model->data[$model->alias]['active'];
		}elseif(!$model->hasField('active')) {
			$data['active'] = 1;
		}
		if (!$created) {
			$model->ShopProduct->recursive = -1;
			$product = $model->ShopProduct->find('first',array('conditions'=>array('model' => $model->alias, 'foreign_id' => $model->id)));
			if($product){
				$data['id']=$product['ShopProduct']['id'];
			}
		}else{
			if(empty($data['needed_data'])){
				$data['needed_data'] = $this->settings[$Model->alias]['needed_data'];
			}else{
				$data['needed_data'] = array_filter(array_merge((array)$data['needed_data'],(array)$this->settings[$Model->alias]['needed_data']));
			}
		}
		$model->ShopProduct->create();
		$model->ShopProduct->save($data);
	}

}
?>