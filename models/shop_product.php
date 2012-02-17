<?php
class ShopProduct extends ShopAppModel {
	var $name = 'ShopProduct';
	var $displayField = 'code';
	
	
	var $actsAs = array('Acl' => array('type' => 'requester'),'Shop.Serialized'=>array('needed_data','tax_applied'));
	
	var $rootNodeAlias = "shopProducts";
	var $dynamicFields = array(
							'price'=>array('ShopProduct.price','(Related)','Related.price_(currency)','Related.price'),
							'title'=>array('(Related)','(Related.displayField)','Related.title_fre','Related.title','ShopProduct.code'),
							'descr'=>array('(Related)','Related.descr')
						);
	var $extractDataCache = array();
	var $possiblePromoCache = array();
	var $fullDataEnabled = true;
	var $getPromos = true;
	
	var $hasMany = array(
		'ShopProductSubproduct' => array(
			'className' => 'ShopProductSubproduct',
			'foreignKey' => 'shop_product_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
	
	var $hasAndBelongsToMany = array(
		'ShopSubproduct' => array(
			'className' => 'ShopSubproduct',
			'joinTable' => 'shop_product_subproducts',
			'foreignKey' => 'shop_subproduct_id',
			'associationForeignKey' => 'shop_product_id',
			'unique' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'finderQuery' => '',
			'deleteQuery' => '',
			'insertQuery' => ''
		)
	);
	
	
	
	function afterFind($results,$primary){
		$results = parent::afterFind($results,$primary);
		if(is_array($results) && $this->recursive > -1 && $this->fullDataEnabled){
			$results = $this->getFullData($results);
			if(!$primary){
				App::import('Lib', 'Shop.SetMulti');
				if(!SetMulti::isAssoc($results)){
					foreach($results as &$result){
						if(isset($result[$this->alias])){
							$more = $result;
							unset($more[$this->alias]);
							$result[$this->alias] = array_merge($result[$this->alias],$more);
						}
					}
				}
				//debug($results);
			}
		}
		return $results;
	}
	
	
	function possiblePromo($product = null){
		if(is_numeric($product)){
			$id = $product;
		}elseif(isset($product['id'])){
			$id = $product['id'];
		}elseif(isset($product[$this->alias]['id'])){
			$id = $product[$this->alias]['id'];
		}else{
			$id = $this->id;
		}
		if(empty($id)){
			return null;
		}
		if(!empty($this->possiblePromoCache[$id])){
			return $this->possiblePromoCache[$id];
		}
		$this->Promotion = ClassRegistry::init('Shop.ShopPromotion'); 
		$promotions = $this->Promotion->find('all',array(
			'fields'=>array(
			),
			'joins'=> array(
				array(
					'table' => $this->Aro->Aco->useTable,
					'alias' => $this->Aro->Aco->alias,
					'type' => 'INNER',
					'conditions' => array(
						$this->Aro->Aco->alias.'.foreign_key = '.$this->Promotion->alias.'.id',
						$this->Aro->Aco->alias.'.model'=>'ShopPromotion',
					)
				),
				array(
					'table' => $this->Aro->Permission->useTable,
					'alias' => $this->Aro->Permission->alias,
					'type' => 'INNER',
					'conditions' => array(
						$this->Aro->Permission->alias.'._read' => 1,
						$this->Aro->Permission->alias.'.aco_id = '.$this->Aro->Aco->alias.'.id'
					)
				),
				array(
					'table' => $this->Aro->useTable,
					'alias' => $this->Aro->alias,
					'type' => 'INNER',
					'conditions' => array(
						$this->Aro->Permission->alias.'.aro_id = '.$this->Aro->alias.'.id'
					)
				),
				array(
					'table' => $this->Aro->useTable,
					'alias' => $this->Aro->alias.'2',
					'type' => 'INNER',
					'conditions' => array(
						$this->Aro->alias.'2.foreign_key'=>$id,
						$this->Aro->alias.'2.model'=>$this->alias,
						$this->Aro->alias.'.lft <= '.$this->Aro->alias.'2.lft',
						$this->Aro->alias.'.rght >= '.$this->Aro->alias.'2.rght',
					)
				),
			),
			'conditions'=>array(
			)
		));
		/*$this->Aro->Aco->recursive = -1;
		debug($this->Aro->Aco->find('all',array(
			'fields'=>array(
			),
			'joins'=> array(
				array(
					'table' => $this->Aro->Permission->useTable,
					'alias' => $this->Aro->Permission->alias,
					'type' => 'INNER',
					'conditions' => array(
						$this->Aro->Permission->alias.'._read' => 1,
						$this->Aro->Permission->alias.'.aco_id = '.$this->Aro->Aco->alias.'.id'
					)
				),
				array(
					'table' => $this->Aro->useTable,
					'alias' => $this->Aro->alias,
					'type' => 'INNER',
					'conditions' => array(
						$this->Aro->Permission->alias.'.aro_id = '.$this->Aro->alias.'.id'
					)
				),
				array(
					'table' => $this->Aro->useTable,
					'alias' => $this->Aro->alias.'2',
					'type' => 'INNER',
					'conditions' => array(
						$this->Aro->alias.'2.foreign_key'=>$id,
						$this->Aro->alias.'2.model'=>$this->alias,
						$this->Aro->alias.'.lft <= '.$this->Aro->alias.'2.lft',
						$this->Aro->alias.'.rght >= '.$this->Aro->alias.'2.rght',
					)
				),
			),
			'conditions'=>array(
			)
		)));*/
		
		/*$this->Aro->recursive = -1;
		debug($this->Aro->find('all',array(
			'fields'=>array(
			),
			'joins'=> array(
				array(
					'table' => $this->Aro->useTable,
					'alias' => $this->Aro->alias.'2',
					'type' => 'INNER',
					'conditions' => array(
						$this->Aro->alias.'2.foreign_key'=>$id,
						$this->Aro->alias.'2.model'=>$this->alias,
						$this->Aro->alias.'.lft <= '.$this->Aro->alias.'2.lft',
						$this->Aro->alias.'.rght >= '.$this->Aro->alias.'2.rght',
					)
				),
			),
			'conditions'=>array(
			)
		)));*/
		
		
		foreach($promotions as &$promo){
			$more = $promo;
			$promo = $promo[$this->Promotion->alias];
			unset($more[$this->Promotion->alias]);
			$promo = array_merge($promo,$more);
		}
		
		$this->possiblePromoCache[$id] = $promotions;
		//debug($promotions);
		return $promotions;
	}
	
	function generateAroList($spacer="---"){
		$prodList = $this->generateProductList();
		//debug($prodList);
		$aros = $this->Aro->find('threaded',array(
			'fields'=>array(
				'DISTINCT '.$this->Aro->alias.'.id',
				$this->Aro->alias.'.parent_id',
				$this->Aro->alias.'.model',
				$this->Aro->alias.'.foreign_key',
				$this->Aro->alias.'.alias',
			),
			'joins'=> array(array(
				'table' => $this->Aro->useTable,
				'alias' => 'Aro2',
				'type' => 'INNER',
				'conditions' => array(
					'Aro2.foreign_key'=>array_keys($prodList),
					'Aro2.model'=>'ShopProduct',
					$this->Aro->alias.'.lft <= Aro2.lft',
					$this->Aro->alias.'.rght >= Aro2.rght',
				)
			))
		));
		App::import('Lib', 'Shop.SetMulti');
		$aros = SetMulti::threadedToLeveled($aros);
		//debug($aros);
		$list = array();
		foreach($aros as $aro){
			$val = '';
			if(!empty($aro[$this->Aro->alias]['model']) && !empty($aro[$this->Aro->alias]['foreign_key']) && $aro[$this->Aro->alias]['model'] == $this->alias){
				$val = $prodList[$aro[$this->Aro->alias]['foreign_key']];
			}elseif(!empty($aro[$this->Aro->alias]['alias'])){
				$val = $aro[$this->Aro->alias]['alias'];
				if($val == $this->rootNodeAlias){
					$val = __('All Products',true);
				}
			}elseif(!empty($aro[$this->Aro->alias]['model']) && !empty($aro[$this->Aro->alias]['foreign_key'])){
				$val = $aro[$this->Aro->alias]['model'].':'. $aro[$this->Aro->alias]['foreign_key'];
			}else{
				$val = $aro[$this->Aro->alias]['id'];
			}
			$list[$aro[$this->Aro->alias]['id']] = str_repeat($spacer,$aro['lvl']) . $val;
		}
		//debug($list);
		return $list;
	}
	
	function generateProductList($conditions = 1){
		$this->recursive = 0;
		$tmp = $this->fullDataEnabled;
		$this->fullDataEnabled = false;
		$tmp2 = $this->getPromos;
		$this->getPromos = false;
		$products = $this->find('all',array('fields'=>array('id','model','foreign_id','code'), 'conditions'=>$conditions));
		$products = $this->getFullData($products,array('minField'=>true));
		$this->fullDataEnabled = $tmp;
		$this->getPromos = $tmp2;
		//debug($products);
		App::import('Lib', 'Shop.SetMulti');
		$list = SetMulti::group($products,'ShopProduct.id', array('singleArray' => false, 'valPath' => 'DynamicField.title'));
		$list = array_filter($list);
		//debug($list);
		return $list;
	}
	
	
	function getFullData($products=null,$opt=array()){
		App::import('Lib', 'Shop.SetMulti');
		$single = false;
		if(!is_array($products) || SetMulti::isAssoc($products)){
			$products = array($products);
			$single = true;
		}
		
		$products = $this->getAllRelated($products,$opt);
		
		
		foreach($products as &$product){
			$dynamicField = $this->getDynamicFields($product);
			$product['DynamicField'] = $dynamicField;
			if($this->getPromos){
				$product['ShopPromotion'] = $this->possiblePromo($product);
			}
		}
		//debug($products);
		if($single){
			return $products[0];
		}else{
			return $products;
		}
	}
	
	function getAllRelated($products=null,$opt=array()){
		$defOpt = array(
			'minField' => false
		);
		$opt = array_merge($defOpt,$opt);
		$relatedModels = array();
		foreach($products as $key => $product){
			$relatedRef = $this->getRelatedRef($product);
			$relatedRef['key'] = $key;
			if(empty($relatedModels[$relatedRef['model']])){
				$relatedModel = $this->getRelatedClass($relatedRef);
				//if(!empty($relatedModel->data[$relatedModel->alias]['id']) && $relatedModel->data[$relatedModel->alias]['id'] == $relatedRef['foreign_id']){
				//	$products[$key]['Related'] = $relatedModel->data[$relatedModel->alias];
				//}else{
					if($relatedModel){
						$relatedModels[$relatedRef['model']]['class'] = $relatedModel;
						$relatedModels[$relatedRef['model']][] = $relatedRef;
					}
				//}
			}else{
				$relatedModels[$relatedRef['model']][] = $relatedRef;
			}
		}
		foreach($relatedModels as $alias => $refs){
			$relatedModel = $refs['class'];
			$tmp = $relatedModel->recursive;
			unset($refs['class']);
			App::import('Lib', 'Shop.SetMulti');
			$relatedModel->recursive = -1;
			$ids = Set::extract('/foreign_id',$refs);
			$findOpt = array('conditions'=>array('id'=>$ids));
			if($opt['minField']){
				$fields = $this->dynamicFieldsExtractData($relatedModel);
				$fields = array_values(Set::flatten($fields));
				$fields = SetMulti::pregFilter('/^Related\./',$fields);
				$fields = str_replace('Related.',$relatedModel->alias.'.',$fields);
				$cpy = $fields;
				$fields = array();
				foreach($cpy as $field){
					if($relatedModel->hasField(str_replace($relatedModel->alias.'.','',$field))){
						$fields[] = $field;
					}
				}
				//debug($fields);
				$findOpt['fields'] = $fields;
			}
			$manyRelated = $relatedModel->find('all',$findOpt);
			$relatedModel->recursive = $tmp;
			$manyRelated = SetMulti::group($manyRelated,$relatedModel->alias.'.id', array('singleArray' => false));
			//debug($manyRelated);
			foreach($refs as $ref){
				if(!empty($manyRelated[$ref['foreign_id']])){
					$products[$ref['key']] = (array)$products[$ref['key']];
					$products[$ref['key']]['Related'] = $manyRelated[$ref['foreign_id']][$relatedModel->alias];
				}
			}
		}
		return $products;
	}
	function getRelated($product=null,$opt=array()){
		/*$relatedRef = $this->getRelatedRef($product);
		$relatedModel = $this->getRelatedClass($relatedRef);
		if($relatedModel){
			$relatedModel->recursive = -1;
			$related = $relatedModel->read(null,$relatedRef['foreign_id']);
			$relatedModel->recursive = 1;
			return $related[$relatedModel->name];
		}
		return null;*/
		$res = getAllRelated(array($product),$opt);
		if(!empty($res[0]['Related'])){
			return $res[0]['Related'];
		}
		return null;
	}
	function dynamicFieldsExtractData($product=null){
		if(isset($product['ShopProduct'][0])){
			$product['ShopProduct'] = $product['ShopProduct'][0];
		}
		if (is_object($product) && is_a($product, 'Model')) {
			$relatedModel = $product;
		}else{
			$relatedModel = $this->getRelatedClass($product);
		}
		
		if(empty($relatedModel)){
			debug($product);
			debug('wtf');
		}
		if(!empty($this->extractDataCache[$relatedModel->name])){
			return $this->extractDataCache[$relatedModel->name];
		}
		$extract_data = $this->dynamicFields;
		
		
		$fieldNameReplace = array();
		if($relatedModel && $relatedModel->displayField){
			$fieldNameReplace['(Related.displayField)'] = 'Related.'.$relatedModel->displayField;
		}
		if(Configure::read('Shop.currency')){
			$fieldNameReplace['(currency)'] = strtolower(Configure::read('Shop.currency'));
		}
		
		foreach($extract_data as $fieldName => &$paths){
			//vas chercher les nom de champs spécifié dans les options du model conserné.
			$retatedPos = array_search('(Related)',$paths);
			if($retatedPos !== false){
				$relatedFields = array();
				if($relatedModel){
					if(!empty($relatedModel->productOptions[$fieldName.'_field'])){
						$relatedFields = (array)$relatedModel->productOptions[$fieldName.'_field'];
					}
					//$relatedFields[] = $fieldName;
					foreach($relatedFields as &$relatedField){
						$relatedField = 'Related.'.$relatedField;
					}
				}
				array_splice($paths,$retatedPos,1,$relatedFields);
			}
			foreach($paths as &$realFieldName){
				$realFieldName = str_replace(array_keys($fieldNameReplace),array_values($fieldNameReplace),$realFieldName);
			}
		}
		//debug($extract_data);
		$this->extractDataCache[$relatedModel->name] = $extract_data;
		return $extract_data;
	}
	function getDynamicFields($product=null){
		$res = SetMulti::extractHierarchicMulti($this->dynamicFieldsExtractData($product),$product);
		return $res;
	}
	function getRelatedClass($product=null){
		$relatedRef = $this->getRelatedRef($product);
		if(!empty($relatedRef['model']) && !empty($relatedRef['foreign_id'])){
			$relatedModel =& ClassRegistry::init($relatedRef['model']);
			return $relatedModel;
		}
		return null;
	}
	function getRelatedRef($product=null){
		if(!is_array($product)){
			$id = $product;
			if(!$id){
				$id = $this->id;
			}
			$this->recursive = -1;
			$product = $this->read(array('model','foreign_id'),$id);
		}
		$extract_data = array(
			'model' => array('model',$this->name.'.model','Options.model'),
			'foreign_id' => array('foreign_id',$this->name.'.foreign_id','Options.foreign_id'),
			'product_id' => array('id',$this->name.'.id','Options.id'),
		);
		App::import('Lib', 'Shop.SetMulti');
		$res = SetMulti::extractHierarchicMulti($extract_data,$product);
		return $res;
	}
	
	function parentNode() {
		$ref = $this->rootNodeAlias;
		$rootNode = $this->node($this->rootNodeAlias);
		if(empty($rootNode)){
			$data["alias"] = $this->rootNodeAlias;
			$this->Aro->create();
			$this->Aro->save($data);
		}
		$model = null;
		if(isset($this->data['ShopProduct']['model'])){
			$model = $this->data['ShopProduct']['model'];
		}
		if(isset($this->data['model'])){
			$model = $this->data['model'];
		}
		if(!empty($model)){
			$ref = $ref . "/" . $model;
			$parent = $this->node($ref);
			if(empty($parent)){
				if(empty($rootNode)){
					$rootNode = $this->node($this->rootNodeAlias);
				}
				$data["parent_id"] = $rootNode[0]["Aro"]["id"];
				$data["alias"] = $model;
				$this->Aro->create();
				$this->Aro->save($data);
			}
		}
		//debug($ref);
		return $ref;
	}
	
	function GetActionsList(){
	}


}
?>