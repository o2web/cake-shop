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
		$success = $model->ShopProduct->save($data);
		if($success && !empty($model->data['ShopSubproduct'])){
			$subProducts = $model->data['ShopSubproduct'];
			$def = array(
				'active' => 1,
				'operator' => '=',
			);
			$toSave = array();
			$toDelete = array();
			foreach ($subProducts as $type => $subs) {
				foreach ($subs as $sub) {
					if(!empty($sub['delete'])){
						$toDelete = $sub;
					}else{
						$sub['type'] = $type;
						$sub['ShopProductSubproduct'] = array('shop_product_id'=>$model->ShopProduct->id);
						$sub = array_merge($def,$sub);
						$toSave[] = $sub;
					}
				}
			}
			debug($toSave);
			foreach ($toSave as $sub) {
				$model->ShopProduct->ShopSubproduct->create();
				if($model->ShopProduct->ShopSubproduct->save($sub)){
					$sub['ShopProductSubproduct']['shop_subproduct_id'] = $model->ShopProduct->ShopSubproduct->id;
					$unique = array('shop_product_id','shop_subproduct_id');
					$existant = $model->ShopProduct->ShopProductSubproduct->find('first',array('conditions'=>array_intersect_key($sub['ShopProductSubproduct'],array_flip($unique))));
					if($existant){
						$sub['ShopProductSubproduct']['id'] = $existant['ShopProductSubproduct']['id'];
					}
					$model->ShopProduct->ShopProductSubproduct->create();
					$model->ShopProduct->ShopProductSubproduct->save($sub['ShopProductSubproduct']);
				}
			}
		}
	}

}
?>