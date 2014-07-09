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
		$Model->Behaviors->attach('Shop.AssociationEvent');
		
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
		
		if(Configure::read('admin') == true) {
			App::import('Lib', 'Shop.ShopConfig');
			$types = ShopConfig::getSubProductTypes();
			if(!empty($types)){
				$this->productContain($Model);
			}
		}
	}
	
	function productContain(&$model, $opt = array()){
		$model->Behaviors->attach('Containable');
		$contains = Set::merge($opt,array('ShopProduct'=>array('ShopSubproduct')));
		$model->contain($contains);
		return $contains;
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
				$data['needed_data'] = $this->settings[$model->alias]['needed_data'];
			}else{
				$data['needed_data'] = array_filter(array_merge((array)$data['needed_data'],(array)$this->settings[$model->alias]['needed_data']));
			}
		}
		$model->ShopProduct->create();
		$success = $model->ShopProduct->save($data);
		if($success && !empty($model->data['ShopSubproduct'])){
			$subProducts = $model->data['ShopSubproduct'];
			$this->saveSubproducts($model, $subProducts, $model->ShopProduct->id);
		}
	}
	function saveSubproducts(&$model, $subProducts,$productId){
		$toSave = array();
		$toDelete = array();
		
		$nil = null;
		$this->_saveSubproducts($model, $subProducts,$productId, $nil, $toSave, $toDelete);
		
		//debug($toSave);
		//debug($toDelete);
		//exit();
		
		$unique = array('shop_product_id','shop_subproduct_id');
		foreach ($toSave as &$sub) {
			$model->ShopProduct->ShopSubproduct->create();
			if($model->ShopProduct->ShopSubproduct->save($sub)){
				if(empty($sub['id'])) $sub['id'] = $model->ShopProduct->ShopSubproduct->id;
				$sub['ShopProductSubproduct']['shop_subproduct_id'] = $model->ShopProduct->ShopSubproduct->id;
				if(!empty($sub['parent'])){
					$sub['ShopProductSubproduct']['parent_subproduct_id'] = $sub['parent']['id'];
				}
				$existant = $model->ShopProduct->ShopProductSubproduct->find('first',array('conditions'=>array_intersect_key($sub['ShopProductSubproduct'],array_flip($unique))));
				if($existant){
					$sub['ShopProductSubproduct']['id'] = $existant['ShopProductSubproduct']['id'];
				}
				$model->ShopProduct->ShopProductSubproduct->create();
				$model->ShopProduct->ShopProductSubproduct->save($sub['ShopProductSubproduct']);
			}
		}
		foreach ($toDelete as &$sub) {
			if(!empty($sub['id'])){
				$sub['ShopProductSubproduct']['shop_subproduct_id'] = $sub['id'];
				$existant = $model->ShopProduct->ShopProductSubproduct->find('first',array('conditions'=>array_intersect_key($sub['ShopProductSubproduct'],array_flip($unique))));
				if($existant){
					$model->ShopProduct->ShopProductSubproduct->delete($existant['ShopProductSubproduct']['id']);
				}
				$model->ShopProduct->ShopSubproduct->delete($sub['id']);
			}
		}
	}
	function _saveSubproducts(&$model, $subProducts, $productId, &$parent, &$toSave, &$toDelete){
		$def = array(
			'active' => 1,
			'operator' => '=',
		);
		foreach ($subProducts as $type => $subs) {
			foreach ($subs as $sub) {
				$sub['type'] = $type;
				$sub['ShopProductSubproduct'] = array('active'=>1, 'shop_product_id'=>$productId);
				$sub = array_merge($def,$sub);
				if(!empty($parent)) $sub['parent'] = &$parent;
				if(!empty($parent['delete'])) $sub['delete'] = $parent['delete'];
				if(!empty($sub['delete'])){
					$toDelete[] = &$sub;
				}else{
					$toSave[] = &$sub;
				}
				if(!empty($sub['children'])){
					$this->_saveSubproducts($model, $sub['children'], $productId, $sub, $toSave, $toDelete);
				}
				unset($sub['children']);
				unset($sub);
			}
		}
	}
}
?>