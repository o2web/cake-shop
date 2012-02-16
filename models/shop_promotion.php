<?php
class ShopPromotion extends ShopAppModel {
	var $name = 'ShopPromotion';
	var $actsAs = array(
		'Acl' => array('type' => 'controlled'),
		'Locale',
		'Shop.Serialized'=>array('action_params'),
	);
	var $displayField = 'code';
	
	var $belongsTo = array(
		'ShopAction' => array(
			'className' => 'Shop.ShopAction',
			'foreignKey' => 'action_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
	
	var $operators = array(
		1 => array(
			'label' => 'equal',
			'sign' => '=',
		),
		2 => array(
			'label' => 'add',
			'sign' => '+',
		),
		3 => array(
			'label' => 'multiply',
			'sign' => '*',
		)
	);
	
	var $rootNodeAlias = "shopPromotions";
	
	function applyOperator($price,$operator,$val){
		switch($operator){
			case 1 :
				return $val;
			case 2:
				return $price + $val;
			case 3:
				return $price * $val;
		}
		return $price;
	}
	function productAros($promo = null){
		if(is_numeric($promo)){
			$id = $promo;
		}elseif(isset($promo['id'])){
			$id = $promo['id'];
		}elseif(isset($promo[$this->alias]['id'])){
			$id = $promo[$this->alias]['id'];
		}else{
			$id = $this->id;
		}
		if(empty($id)){
			return null;
		}
		$this->Aco->Aro->recursive = -1;
		$aros = $this->Aco->Aro->find('all',array(
			'fields'=>array(
				'DISTINCT '.$this->Aco->Aro->alias.'.id',
				$this->Aco->Aro->alias.'.parent_id',
				$this->Aco->Aro->alias.'.model',
				$this->Aco->Aro->alias.'.foreign_key',
				$this->Aco->Aro->alias.'.alias',
			),
			'joins'=> array(
				array(
					'table' => $this->Aco->Aro->useTable,
					'alias' => $this->Aco->Aro->alias.'2',
					'type' => 'INNER',
					'conditions' => array(
						'Aro2.model'=>'ShopProduct',
						$this->Aco->Aro->alias.'.lft <= '.$this->Aco->Aro->alias.'2.lft',
						$this->Aco->Aro->alias.'.rght >= '.$this->Aco->Aro->alias.'2.rght',
					)
				),
				array(
					'table' => $this->Aco->Permission->useTable,
					'alias' => $this->Aco->Permission->alias,
					'type' => 'INNER',
					'conditions' => array(
						$this->Aco->Permission->alias.'._read' => 1,
						$this->Aco->Permission->alias.'.aro_id = '.$this->Aco->Aro->alias.'.id'
					)
				),
				array(
					'table' => $this->Aco->useTable,
					'alias' => $this->Aco->alias,
					'type' => 'INNER',
					'conditions' => array(
						$this->Aco->alias.'.foreign_key'=>$id,
						$this->Aco->alias.'.model'=>$this->alias,
						$this->Aco->Permission->alias.'.aco_id = '.$this->Aco->alias.'.id'
					)
				),
			),
			'conditions'=>array(
			)
		));
		//debug($aros);
		return $aros;
	}
	
	function parentNode(){
		$ref = $this->rootNodeAlias;
		$rootNode = $this->node($this->rootNodeAlias);
		if(empty($rootNode)){
			$data["alias"] = $this->rootNodeAlias;
			$this->Aco->create();
			$this->Aco->save($data);
		}
		//debug($ref);
		return $ref;
	}
}
?>