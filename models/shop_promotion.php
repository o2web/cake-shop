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
	
	var $hasMany = array(
		'ShopCoupon' => array(
			'className' => 'Shop.ShopCoupon',
			'foreignKey' => 'shop_promotion_id',
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
	
	function beforeSave($options){
		if(!empty($this->data[$this->alias]['add_coupons'])){
			$this->data[$this->alias]['limited_coupons'] = 1;
		}
		return true;
	}
	
	function afterSave($created){
		if(!empty($this->data[$this->alias]['add_coupons'])){
			$codes = $this->generateCodes($this->data[$this->alias]['add_coupons']);
			foreach($codes as $code){
				$coupon = array(
					'active'=> true,
					'code' => $code,
					'shop_promotion_id'=>$this->id,
				);
				
				$this->ShopCoupon->create();
				$this->ShopCoupon->save($coupon);
			}
		}
	}
	
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
	
	
	function generateCodes($nb,$exclude = array()){
		$codes = array();
		for ($i = 0; $i < $nb; $i++) {
			$codes[] = $this->generateCode();
		}
		$codes = array_diff($codes,$exclude);
		$existing = array_filter($this->codesExists($codes));
		$codes = array_diff($codes,array_keys($existing));
		if(count($codes) < $nb){
			$codes = array_merge($codes,$this->generateCodes($nb-count($codes),$codes));
		}
		return $codes;
	}
	
	function generateCode($validate = true){
		$code = '';
		App::import('Lib', 'Shop.ShopConfig');
		$len = ShopConfig::load('promo.codeLen');
		$chars = array(
			'1','2','3','4','5','6','7','8','9','0',
			'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'
		);
		for ($i = 0; $i < $len; $i++) {
			$code .= $chars[rand(0,count($chars)-1)];
		}
		if($validate && $this->codesExists($code)){
			return $this->generateCode();
		}
		return $code;
	}
	
	function codesExists($codes,$full = false,$availableCoupon = false){
		$multi = is_array($codes);
		if(!$multi){
			$codes = array($codes);
		}
		$res = array_combine($codes,array_fill(0, count($codes), false));
		$findOpt = array('conditions'=>array('code'=>$codes),'recursive'=>-1);
		if($full){
			App::import('Lib', 'Shop.SetMulti');
			$existingPromo = $this->find('all',$findOpt);
			if(!empty($existingPromo)){
				$existingPromo = SetMulti::group($existingPromo,'ShopPromotion.code',array('singleArray' => false));
			}
		}else{
			$findOpt['fields'] = array('id','code');
			$existingPromo = $this->find('list',$findOpt);
			if(!empty($existingPromo)){
				$existingPromo = array_combine($existingPromo,array_fill(0, count($existingPromo), true));
			}
		}
		if(!empty($existingPromo)){
			$res = array_merge($res,$existingPromo);
		}
		if(count(array_filter($res)) != count($res)){
			$findOpt = array('fields'=>array('id','code'),'conditions'=>array('code'=>$codes));
			if($availableCoupon){
				$findOpt['conditions'][]=array('or'=>array('ShopCoupon.status not'=>array('used','reserved'),'ShopCoupon.status'=> null));
			}
			if($full){
				App::import('Lib', 'Shop.SetMulti');
				$findOpt['contain'] = array('ShopPromotion');
				$existingCoupon = $this->ShopCoupon->find('all',$findOpt);
				if(!empty($existingCoupon)){
					$existingCoupon = SetMulti::group($existingCoupon,'ShopCoupon.code',array('singleArray' => false));
				}
			}else{
				$findOpt['fields'] = array('id','code');
				$findOpt['recursive'] = -1;
				$existingCoupon = $this->ShopCoupon->find('list',$findOpt);
				if(!empty($existingCoupon)){
					$existingCoupon = array_combine($existingCoupon,array_fill(0, count($existingCoupon), true));
				}
			}
			if(!empty($existingCoupon)){
				$res = array_merge($res,$existingCoupon);
			}
		}
		
		if(!$multi){
			return $res[0];
		}
		return $res;
	}
}
?>