<?php
class ShopAddressesController extends ShopAppController {

	var $name = 'ShopAddresses';

	function index() {
		$this->ShopAddress->recursive = 0;
		$this->set('shopAddresses', $this->paginate());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'shop address'));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('shopAddress', $this->ShopAddress->read(null, $id));
	}

		
	
	function admin_index() {
		$q = null;
		if(isset($this->params['named']['q']) && strlen(trim($this->params['named']['q'])) > 0) {
			$q = $this->params['named']['q'];
		} elseif(isset($this->data['Artist']['q']) && strlen(trim($this->data['Artist']['q'])) > 0) {
			$q = $this->data['Artist']['q'];
			$this->params['named']['q'] = $q;
		}
					
		if($q !== null) {
			$this->paginate['conditions']['OR'] = array('ShopAddress.address LIKE' => '%'.$q.'%',
														'ShopAddress.apt LIKE' => '%'.$q.'%',
														'ShopAddress.city LIKE' => '%'.$q.'%',
														'ShopAddress.province LIKE' => '%'.$q.'%',
														'ShopAddress.postal_code LIKE' => '%'.$q.'%',
														'ShopAddress.country LIKE' => '%'.$q.'%',
														'ShopAddress.phone LIKE' => '%'.$q.'%');
		}

		$this->ShopAddress->recursive = 0;
		$this->set('shopAddresses', $this->paginate());
	}

		
	function admin_add() {
		if (!empty($this->data)) {
			$this->ShopAddress->create();
			if ($this->ShopAddress->save($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'shop address'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'shop address'));
			}
		}
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(sprintf(__('Invalid %s', true), 'shop address'));
			$this->redirect(array('action' => 'index'));
		}
		if (!empty($this->data)) {
			if ($this->ShopAddress->save($this->data)) {
				$this->Session->setFlash(sprintf(__('The %s has been saved', true), 'shop address'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(sprintf(__('The %s could not be saved. Please, try again.', true), 'shop address'));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->ShopAddress->read(null, $id);
		}
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(sprintf(__('Invalid id for %s', true), 'shop address'));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->ShopAddress->delete($id)) {
			$this->Session->setFlash(sprintf(__('%s deleted', true), 'Shop address'));
			$this->redirect(array('action'=>'index'));
		}
		$this->Session->setFlash(sprintf(__('%s was not deleted', true), 'Shop address'));
		$this->redirect(array('action' => 'index'));
	}
	
}
?>