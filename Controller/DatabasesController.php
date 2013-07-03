<?php
App::uses('AppsAppController', 'AppController');
/**
 * Databases Controller
 *
 * @property Database $Database
 */
class DatabasesController extends AppsAppController {

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Database->recursive = 0;
		$this->set('databases', $this->paginate());
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		if (!$this->Database->exists($id)) {
			throw new NotFoundException(__('Invalid database'));
		}
		$options = array('conditions' => array('Database.' . $this->Database->primaryKey => $id));
		$this->set('database', $this->Database->find('first', $options));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Database->create();
			if ($this->Database->save($this->request->data)) {
				$this->Session->setFlash(__('The database has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The database could not be saved. Please, try again.'));
			}
		}
		$applications = $this->Database->Application->find('list');
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
		if (!$this->Database->exists($id)) {
			throw new NotFoundException(__('Invalid database'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Database->save($this->request->data)) {
				$this->Session->setFlash(__('The database has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The database could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Database.' . $this->Database->primaryKey => $id));
			$this->request->data = $this->Database->find('first', $options);
		}
		$applications = $this->Database->Application->find('list');
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
		$this->Database->id = $id;
		if (!$this->Database->exists()) {
			throw new NotFoundException(__('Invalid database'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Database->delete()) {
			$this->Session->setFlash(__('Database deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Database was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
