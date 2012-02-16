<?php
class ShopTaxesController extends ShopAppController {

	var $name = 'ShopTaxes';

	function admin_index() {
		$q = null;
		if(isset($this->params['named']['q']) && strlen(trim($this->params['named']['q'])) > 0) {
			$q = $this->params['named']['q'];
		} elseif(isset($this->data['Artist']['q']) && strlen(trim($this->data['Artist']['q'])) > 0) {
			$q = $this->data['Artist']['q'];
			$this->params['named']['q'] = $q;
		}
					
		if($q !== null) {
			$this->paginate['conditions']['OR'] = array('ShopTax.country LIKE' => '%'.$q.'%',
														'ShopTax.province LIKE' => '%'.$q.'%',
														'ShopTax.name LIKE' => '%'.$q.'%');
		}

		$this->ShopTax->recursive = 0;
		$this->set('shopTaxes', $this->paginate());
	}

		
	function admin_add() {
		if (!empty($this->data)) {
			$this->ShopTax->create();
			if ($this->ShopTax->save($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'shop tax'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'shop tax'));
			}
		}
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'shop tax'));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->ShopTax->save($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'shop tax'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'shop tax'));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->ShopTax->read(null, $id);
		}
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid id for %s', true), 'shop tax'));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->ShopTax->delete($id)) {
			$this->Session->setFlash(sprintf(__('%s deleted', true), 'Shop tax'));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(sprintf(__('%s was not deleted', true), 'Shop tax'));
		$this->redirect(array('action' => 'index'));
	}
	
}
?>