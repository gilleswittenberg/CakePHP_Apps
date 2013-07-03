<?php
App::uses('AppsAppController', 'Apps.Controller');
/**
 * ServerAliases Controller
 *
 * @property ServerAlias $ServerAlias
 */
class ServerAliasesController extends AppsAppController {

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->ServerAlias->recursive = 0;
		$this->set('serverAliases', $this->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->ServerAlias->exists($id)) {
			throw new NotFoundException(__('Invalid server alias'));
		}
		$options = array('conditions' => array('ServerAlias.' . $this->ServerAlias->primaryKey => $id));
		$this->set('serverAlias', $this->ServerAlias->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->ServerAlias->create();
			if ($this->ServerAlias->save($this->request->data)) {
				$this->Session->setFlash(__('The server alias has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The server alias could not be saved. Please, try again.'));
			}
		}
		$applications = $this->ServerAlias->Application->find('list');
		$this->set(compact('applications'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		if (!$this->ServerAlias->exists($id)) {
			throw new NotFoundException(__('Invalid server alias'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->ServerAlias->save($this->request->data)) {
				$this->Session->setFlash(__('The server alias has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The server alias could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('ServerAlias.' . $this->ServerAlias->primaryKey => $id));
			$this->request->data = $this->ServerAlias->find('first', $options);
		}
		$applications = $this->ServerAlias->Application->find('list');
		$this->set(compact('applications'));
	}

/**
 * delete method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function delete($id = null) {
		$this->ServerAlias->id = $id;
		if (!$this->ServerAlias->exists()) {
			throw new NotFoundException(__('Invalid server alias'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->ServerAlias->delete()) {
			$this->Session->setFlash(__('Server alias deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Server alias was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
