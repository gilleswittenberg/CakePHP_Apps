<?php
App::uses('AppsAppController', 'Apps.Controller');
/**
 * DocumentRoots Controller
 *
 * @property DocumentRoot $DocumentRoot
 */
class DocumentRootsController extends AppsAppController {

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->DocumentRoot->recursive = 0;
		$this->set('documentRoots', $this->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->DocumentRoot->exists($id)) {
			throw new NotFoundException(__('Invalid document root'));
		}
		$options = array('conditions' => array('DocumentRoot.' . $this->DocumentRoot->primaryKey => $id));
		$this->set('documentRoot', $this->DocumentRoot->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->DocumentRoot->create();
			if ($this->DocumentRoot->save($this->request->data)) {
				$this->Session->setFlash(__('The document root has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The document root could not be saved. Please, try again.'));
			}
		}
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
 	/*
	public function edit($id = null) {
		if (!$this->DocumentRoot->exists($id)) {
			throw new NotFoundException(__('Invalid document root'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->DocumentRoot->save($this->request->data)) {
				$this->Session->setFlash(__('The document root has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The document root could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('DocumentRoot.' . $this->DocumentRoot->primaryKey => $id));
			$this->request->data = $this->DocumentRoot->find('first', $options);
		}
	}
	*/

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->DocumentRoot->id = $id;
		if (!$this->DocumentRoot->exists()) {
			throw new NotFoundException(__('Invalid document root'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->DocumentRoot->delete()) {
			$this->Session->setFlash(__('Document root deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Document root was not deleted. Cause it contains Applications.'));
		$this->redirect(array('action' => 'index'));
	}
}
